<?php
header('Content-Type: text/plain');

$img = imagecreatefrompng(__DIR__ . '/images/dental-chart-v2.png');
$w = imagesx($img);
$h = imagesy($img);

$midY = (int)($h / 2);
$brightThreshold = 80;

function isBright($img, $x, $y, $w, $h, $threshold) {
    if ($x < 0 || $x >= $w || $y < 0 || $y >= $h) return false;
    $rgb = imagecolorat($img, $x, $y);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    return (($r + $g + $b) / 3) > $threshold;
}

function findTeethInRow($img, $w, $yStart, $yEnd, $threshold, $maxX = null) {
    if ($maxX === null) $maxX = $w;
    $h = imagesy($img);
    $colHasPixel = [];
    for ($x = 0; $x < $maxX; $x++) {
        $colHasPixel[$x] = false;
        for ($y = $yStart; $y < $yEnd; $y++) {
            if (isBright($img, $x, $y, $w, $h, $threshold)) {
                $colHasPixel[$x] = true;
                break;
            }
        }
    }

    $teeth = [];
    $inTooth = false;
    $startX = 0;
    $minGap = 3;

    for ($x = 0; $x < $maxX; $x++) {
        if ($colHasPixel[$x] && !$inTooth) {
            $startX = $x;
            $inTooth = true;
        } elseif (!$colHasPixel[$x] && $inTooth) {
            $realGap = true;
            for ($g = $x; $g < min($x + $minGap, $maxX); $g++) {
                if ($colHasPixel[$g]) { $realGap = false; break; }
            }
            if ($realGap) {
                $teeth[] = ['startX' => $startX, 'endX' => $x - 1];
                $inTooth = false;
            }
        }
    }
    if ($inTooth) {
        $teeth[] = ['startX' => $startX, 'endX' => min($maxX - 1, $w - 1)];
    }

    return $teeth;
}

// Exclude star watermark (bottom-right, x >= 1330)
$upperTeeth = findTeethInRow($img, $w, 0, $midY, $brightThreshold);
$lowerTeeth = findTeethInRow($img, $w, $midY, $h, $brightThreshold, 1340);

$upperFDI = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
$lowerFDI = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];

echo "Found " . count($upperTeeth) . " upper teeth, " . count($lowerTeeth) . " lower teeth\n\n";

if (count($upperTeeth) != 16 || count($lowerTeeth) != 16) {
    echo "WARNING: Expected 16+16\n";
    foreach ($upperTeeth as $i => $t) {
        echo "  Upper $i: x={$t['startX']}..{$t['endX']} (w=" . ($t['endX'] - $t['startX'] + 1) . ")\n";
    }
    foreach ($lowerTeeth as $i => $t) {
        echo "  Lower $i: x={$t['startX']}..{$t['endX']} (w=" . ($t['endX'] - $t['startX'] + 1) . ")\n";
    }
    exit;
}

function generateClipPath($img, $toothStartX, $toothEndX, $yStart, $yEnd, $w, $h, $brightThreshold) {
    $boxX = $toothStartX;
    $boxW = $toothEndX - $toothStartX + 1;
    $boxY = $yStart;
    $boxH = $yEnd - $yStart;

    $topEdge = [];
    $bottomEdge = [];

    for ($col = 0; $col < $boxW; $col++) {
        $px = $boxX + $col;
        $topEdge[$col] = -1;
        $bottomEdge[$col] = -1;

        for ($row = 0; $row < $boxH; $row++) {
            $py = $boxY + $row;
            if (isBright($img, $px, $py, $w, $h, $brightThreshold)) {
                $topEdge[$col] = $row;
                break;
            }
        }

        for ($row = $boxH - 1; $row >= 0; $row--) {
            $py = $boxY + $row;
            if (isBright($img, $px, $py, $w, $h, $brightThreshold)) {
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

echo "// Upper teeth\n";
foreach ($upperTeeth as $i => $tooth) {
    if ($i >= 16) break;
    $fdi = $upperFDI[$i];
    $data = generateClipPath($img, $tooth['startX'], $tooth['endX'], 0, $midY, $w, $h, $brightThreshold);
    echo "'$fdi' => ['left' => '{$data['left']}%', 'top' => '{$data['top']}%', 'width' => '{$data['width']}%', 'height' => '{$data['height']}%', 'clip' => '{$data['clip']}'],\n";
}

echo "\n// Lower teeth\n";
foreach ($lowerTeeth as $i => $tooth) {
    if ($i >= 16) break;
    $fdi = $lowerFDI[$i];
    $data = generateClipPath($img, $tooth['startX'], $tooth['endX'], $midY, $h, $w, $h, $brightThreshold);
    echo "'$fdi' => ['left' => '{$data['left']}%', 'top' => '{$data['top']}%', 'width' => '{$data['width']}%', 'height' => '{$data['height']}%', 'clip' => '{$data['clip']}'],\n";
}
