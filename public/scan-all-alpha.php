<?php
header('Content-Type: text/plain');

$img = imagecreatefrompng(__DIR__ . '/images/dental-chart-new.png');
$w = imagesx($img);
$h = imagesy($img);

// Step 1: Find all non-transparent columns and their vertical extents
// Split into upper half (y < h/2) and lower half (y >= h/2)
$midY = (int)($h / 2);

function findTeethInRow($img, $w, $yStart, $yEnd) {
    $colHasPixel = [];
    for ($x = 0; $x < $w; $x++) {
        $colHasPixel[$x] = false;
        for ($y = $yStart; $y < $yEnd; $y++) {
            $a = (imagecolorat($img, $x, $y) >> 24) & 0x7F;
            if ($a < 127) {
                $colHasPixel[$x] = true;
                break;
            }
        }
    }

    // Find contiguous runs of opaque columns = individual teeth
    $teeth = [];
    $inTooth = false;
    $startX = 0;
    $minGap = 3; // minimum gap between teeth

    for ($x = 0; $x < $w; $x++) {
        if ($colHasPixel[$x] && !$inTooth) {
            $startX = $x;
            $inTooth = true;
        } elseif (!$colHasPixel[$x] && $inTooth) {
            // Check if gap is wide enough to be a real gap
            $gapEnd = $x;
            $realGap = true;
            for ($g = $x; $g < min($x + $minGap, $w); $g++) {
                if ($colHasPixel[$g]) {
                    $realGap = false;
                    break;
                }
            }
            if ($realGap) {
                $teeth[] = ['startX' => $startX, 'endX' => $x - 1];
                $inTooth = false;
            }
        }
    }
    if ($inTooth) {
        $teeth[] = ['startX' => $startX, 'endX' => $w - 1];
    }

    return $teeth;
}

$upperTeeth = findTeethInRow($img, $w, 0, $midY);
$lowerTeeth = findTeethInRow($img, $w, $midY, $h);

echo "Found " . count($upperTeeth) . " upper teeth, " . count($lowerTeeth) . " lower teeth\n\n";

// ADA to FDI mapping
// Upper (ADA 1-16 left to right) = FDI 18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28
$upperFDI = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
// Lower (ADA 32-17 left to right) = FDI 48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38
$lowerFDI = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];

if (count($upperTeeth) != 16) {
    echo "WARNING: Expected 16 upper teeth, found " . count($upperTeeth) . "\n";
    foreach ($upperTeeth as $i => $t) {
        echo "  Tooth $i: x={$t['startX']}..{$t['endX']} (width=" . ($t['endX'] - $t['startX'] + 1) . ")\n";
    }
}
if (count($lowerTeeth) != 16) {
    echo "WARNING: Expected 16 lower teeth, found " . count($lowerTeeth) . "\n";
    foreach ($lowerTeeth as $i => $t) {
        echo "  Tooth $i: x={$t['startX']}..{$t['endX']} (width=" . ($t['endX'] - $t['startX'] + 1) . ")\n";
    }
}

function generateClipPath($img, $toothStartX, $toothEndX, $yStart, $yEnd, $w, $h) {
    $boxX = $toothStartX;
    $boxW = $toothEndX - $toothStartX + 1;
    $boxY = $yStart;
    $boxH = $yEnd - $yStart;

    // For each column, find first and last non-transparent pixel
    $topEdge = [];
    $bottomEdge = [];

    for ($col = 0; $col < $boxW; $col++) {
        $px = $boxX + $col;
        $topEdge[$col] = -1;
        $bottomEdge[$col] = -1;

        // Top edge: scan downward
        for ($row = 0; $row < $boxH; $row++) {
            $py = $boxY + $row;
            if ($py >= $h) break;
            $a = (imagecolorat($img, $px, $py) >> 24) & 0x7F;
            if ($a < 127) {
                $topEdge[$col] = $row;
                break;
            }
        }

        // Bottom edge: scan upward
        for ($row = $boxH - 1; $row >= 0; $row--) {
            $py = $boxY + $row;
            if ($py >= $h || $py < 0) continue;
            $a = (imagecolorat($img, $px, $py) >> 24) & 0x7F;
            if ($a < 127) {
                $bottomEdge[$col] = $row;
                break;
            }
        }
    }

    // Clamp missing columns
    for ($col = 0; $col < $boxW; $col++) {
        if ($topEdge[$col] < 0) {
            $nearest = null;
            for ($d = 1; $d < $boxW; $d++) {
                if ($col-$d >= 0 && $topEdge[$col-$d] >= 0) { $nearest = $topEdge[$col-$d]; break; }
                if ($col+$d < $boxW && $topEdge[$col+$d] >= 0) { $nearest = $topEdge[$col+$d]; break; }
            }
            $topEdge[$col] = $nearest ?? 0;
        }
        if ($bottomEdge[$col] < 0) {
            $nearest = null;
            for ($d = 1; $d < $boxW; $d++) {
                if ($col-$d >= 0 && $bottomEdge[$col-$d] >= 0) { $nearest = $bottomEdge[$col-$d]; break; }
                if ($col+$d < $boxW && $bottomEdge[$col+$d] >= 0) { $nearest = $bottomEdge[$col+$d]; break; }
            }
            $bottomEdge[$col] = $nearest ?? $boxH;
        }
    }

    // Smooth with moving average (window=2)
    $smoothTop = []; $smoothBot = [];
    $win = 2;
    for ($i = 0; $i < $boxW; $i++) {
        $sumT = 0; $sumB = 0; $cnt = 0;
        for ($j = max(0, $i - $win); $j <= min($boxW - 1, $i + $win); $j++) {
            $sumT += $topEdge[$j]; $sumB += $bottomEdge[$j]; $cnt++;
        }
        $smoothTop[$i] = $sumT / $cnt;
        $smoothBot[$i] = $sumB / $cnt;
    }

    // Find valid column range
    $firstCol = -1; $lastCol = -1;
    for ($col = 0; $col < $boxW; $col++) {
        if ($topEdge[$col] >= 0 && $bottomEdge[$col] >= 0) {
            if ($firstCol < 0) $firstCol = $col;
            $lastCol = $col;
        }
    }
    if ($firstCol < 0) { $firstCol = 0; $lastCol = $boxW - 1; }

    $insetFirst = min($firstCol + 1, $lastCol);
    $insetLast = max($lastCol - 1, $firstCol);

    // Sample 30 points
    $numSamples = 30;
    $polyPoints = [];

    for ($s = 0; $s < $numSamples; $s++) {
        $col = $insetFirst + (int)(($s / ($numSamples - 1)) * ($insetLast - $insetFirst));
        $xPct = round(($col / $boxW) * 100, 1);
        $yPct = round(($smoothTop[$col] / $boxH) * 100, 1);
        $polyPoints[] = "$xPct% $yPct%";
    }

    for ($s = $numSamples - 1; $s >= 0; $s--) {
        $col = $insetFirst + (int)(($s / ($numSamples - 1)) * ($insetLast - $insetFirst));
        $xPct = round(($col / $boxW) * 100, 1);
        $yPct = round(($smoothBot[$col] / $boxH) * 100, 1);
        $polyPoints[] = "$xPct% $yPct%";
    }

    return [
        'left' => round($boxX / $w * 100, 2),
        'top' => round($boxY / $h * 100, 1),
        'width' => round($boxW / $w * 100, 2),
        'height' => round($boxH / $h * 100, 1),
        'clip' => 'polygon(' . implode(', ', $polyPoints) . ')',
    ];
}

// Generate for upper teeth
echo "// Upper teeth\n";
foreach ($upperTeeth as $i => $tooth) {
    if ($i >= 16) break;
    $fdi = $upperFDI[$i];
    $data = generateClipPath($img, $tooth['startX'], $tooth['endX'], 0, $midY, $w, $h);
    echo "'$fdi' => ['left' => '{$data['left']}%', 'top' => '{$data['top']}%', 'width' => '{$data['width']}%', 'height' => '{$data['height']}%', 'clip' => '{$data['clip']}'],\n";
}

echo "\n// Lower teeth\n";
foreach ($lowerTeeth as $i => $tooth) {
    if ($i >= 16) break;
    $fdi = $lowerFDI[$i];
    $data = generateClipPath($img, $tooth['startX'], $tooth['endX'], $midY, $h, $w, $h);
    echo "'$fdi' => ['left' => '{$data['left']}%', 'top' => '{$data['top']}%', 'width' => '{$data['width']}%', 'height' => '{$data['height']}%', 'clip' => '{$data['clip']}'],\n";
}
