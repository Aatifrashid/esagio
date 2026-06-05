<?php
header('Content-Type: text/plain');

$img = imagecreatefrompng(__DIR__ . '/images/dental-chart.png');
$w = imagesx($img);
$h = imagesy($img);

$lowerTeeth = [
    '48' => ['left' => 0.0969, 'width' => 0.0562],
    '47' => ['left' => 0.1643, 'width' => 0.0548],
    '46' => ['left' => 0.2317, 'width' => 0.0562],
    '45' => ['left' => 0.2992, 'width' => 0.0393],
    '44' => ['left' => 0.3497, 'width' => 0.0365],
    '43' => ['left' => 0.3961, 'width' => 0.0351],
    '42' => ['left' => 0.4410, 'width' => 0.0295],
    '41' => ['left' => 0.4789, 'width' => 0.0281],
    '31' => ['left' => 0.5211, 'width' => 0.0281],
    '32' => ['left' => 0.5576, 'width' => 0.0295],
    '33' => ['left' => 0.5969, 'width' => 0.0337],
    '34' => ['left' => 0.6404, 'width' => 0.0379],
    '35' => ['left' => 0.6882, 'width' => 0.0393],
    '36' => ['left' => 0.7402, 'width' => 0.0548],
    '37' => ['left' => 0.8076, 'width' => 0.0548],
    '38' => ['left' => 0.8750, 'width' => 0.0562],
];

// Scan region: start high enough to catch crown tops
$topFrac = 0.46;
$heightFrac = 0.29;
$bgThreshold = 30;
// Minimum consecutive non-white pixels vertically to count as "tooth" (filters out number labels)
$minRunLength = 14;

function isNonWhite($img, $px, $py, $w, $h, $threshold) {
    if ($px >= $w || $py >= $h || $px < 0 || $py < 0) return false;
    $rgb = imagecolorat($img, $px, $py);
    $r = ($rgb >> 16) & 0xFF; $g = ($rgb >> 8) & 0xFF; $b = $rgb & 0xFF;
    return (abs(255-$r)+abs(255-$g)+abs(255-$b)) > $threshold;
}

foreach ($lowerTeeth as $tooth => $pos) {
    $boxX = (int)($pos['left'] * $w);
    $boxW = (int)($pos['width'] * $w);
    $boxY = (int)($topFrac * $h);
    $boxH = (int)($heightFrac * $h);

    // Scan top edge - require minRunLength consecutive non-white pixels below
    $topEdge = [];
    for ($col = 0; $col < $boxW; $col++) {
        $px = $boxX + $col;
        $found = false;
        for ($row = 0; $row < $boxH - $minRunLength; $row++) {
            $py = $boxY + $row;
            if (!isNonWhite($img, $px, $py, $w, $h, $bgThreshold)) continue;
            // Check if there are minRunLength consecutive non-white pixels below
            $runOk = true;
            for ($r2 = 1; $r2 < $minRunLength; $r2++) {
                if (!isNonWhite($img, $px, $py + $r2, $w, $h, $bgThreshold)) {
                    $runOk = false;
                    break;
                }
            }
            if ($runOk) {
                $topEdge[$col] = $row;
                $found = true;
                break;
            }
        }
        if (!$found) $topEdge[$col] = -1;
    }

    // Scan bottom edge (up to 90% of box height)
    $scanLimit = (int)($boxH * 0.90);
    $bottomEdge = [];
    for ($col = 0; $col < $boxW; $col++) {
        $px = $boxX + $col;
        $found = false;
        for ($row = $scanLimit; $row >= 0; $row--) {
            $py = $boxY + $row;
            if (!isNonWhite($img, $px, $py, $w, $h, $bgThreshold)) continue;
            // Check upward run too
            $runOk = true;
            for ($r2 = 1; $r2 < min($minRunLength, 4); $r2++) {
                if (!isNonWhite($img, $px, $py - $r2, $w, $h, $bgThreshold)) {
                    $runOk = false;
                    break;
                }
            }
            if ($runOk) {
                $bottomEdge[$col] = $row;
                $found = true;
                break;
            }
        }
        if (!$found) $bottomEdge[$col] = -1;
    }

    // Find the range of columns that have actual tooth pixels
    $firstCol = -1; $lastCol = -1;
    for ($col = 0; $col < $boxW; $col++) {
        if ($topEdge[$col] >= 0 && $bottomEdge[$col] >= 0) {
            if ($firstCol < 0) $firstCol = $col;
            $lastCol = $col;
        }
    }
    if ($firstCol < 0) { $firstCol = 0; $lastCol = $boxW - 1; }

    // Clamp missing columns to nearest valid value
    for ($col = 0; $col < $boxW; $col++) {
        if ($topEdge[$col] < 0) {
            $nearest = null;
            for ($d = 1; $d < $boxW; $d++) {
                if ($col-$d >= 0 && $topEdge[$col-$d] >= 0) { $nearest = $topEdge[$col-$d]; break; }
                if ($col+$d < $boxW && $topEdge[$col+$d] >= 0) { $nearest = $topEdge[$col+$d]; break; }
            }
            $topEdge[$col] = $nearest ?? $boxH;
        }
        if ($bottomEdge[$col] < 0) {
            $nearest = null;
            for ($d = 1; $d < $boxW; $d++) {
                if ($col-$d >= 0 && $bottomEdge[$col-$d] >= 0) { $nearest = $bottomEdge[$col-$d]; break; }
                if ($col+$d < $boxW && $bottomEdge[$col+$d] >= 0) { $nearest = $bottomEdge[$col+$d]; break; }
            }
            $bottomEdge[$col] = $nearest ?? 0;
        }
    }

    // Smooth with moving average (window=5)
    $smoothTop = []; $smoothBot = [];
    $win = 5;
    for ($i = 0; $i < $boxW; $i++) {
        $sumT = 0; $sumB = 0; $cnt = 0;
        for ($j = max(0, $i - $win); $j <= min($boxW - 1, $i + $win); $j++) {
            $sumT += $topEdge[$j]; $sumB += $bottomEdge[$j]; $cnt++;
        }
        $smoothTop[$i] = $sumT / $cnt;
        $smoothBot[$i] = $sumB / $cnt;
    }

    // Spike removal: clamp any top-edge point that's far above its local neighbours
    $topVals = array_values($smoothTop);
    sort($topVals);
    $medianTop = $topVals[(int)(count($topVals) * 0.4)]; // 40th percentile = typical crown top
    $spikeThreshold = $boxH * 0.06;
    for ($i = 0; $i < $boxW; $i++) {
        if ($smoothTop[$i] < $medianTop - $spikeThreshold) {
            $smoothTop[$i] = $medianTop;
        }
    }

    // Re-smooth after spike removal
    $smoothTop2 = [];
    for ($i = 0; $i < $boxW; $i++) {
        $sumT = 0; $cnt = 0;
        for ($j = max(0, $i - $win); $j <= min($boxW - 1, $i + $win); $j++) {
            $sumT += $smoothTop[$j]; $cnt++;
        }
        $smoothTop2[$i] = $sumT / $cnt;
    }
    $smoothTop = $smoothTop2;

    // Sample points within the tooth range
    $numSamples = 20;
    $polyPoints = [];

    // Top edge: left to right
    for ($s = 0; $s < $numSamples; $s++) {
        $col = $firstCol + (int)(($s / ($numSamples - 1)) * ($lastCol - $firstCol));
        $xPct = round(($col / $boxW) * 100, 1);
        $yPct = round(($smoothTop[$col] / $boxH) * 100, 1);
        $polyPoints[] = "$xPct% $yPct%";
    }

    // Bottom edge: right to left
    for ($s = $numSamples - 1; $s >= 0; $s--) {
        $col = $firstCol + (int)(($s / ($numSamples - 1)) * ($lastCol - $firstCol));
        $xPct = round(($col / $boxW) * 100, 1);
        $yPct = round(($smoothBot[$col] / $boxH) * 100, 1);
        $polyPoints[] = "$xPct% $yPct%";
    }

    $polygon = 'polygon(' . implode(', ', $polyPoints) . ')';
    $leftPct = round($pos['left'] * 100, 2);
    $widthPct = round($pos['width'] * 100, 2);
    $topPct = round($topFrac * 100, 1);
    $heightPct = round($heightFrac * 100, 1);
    echo "'$tooth' => ['left' => '{$leftPct}%', 'top' => '{$topPct}%', 'width' => '{$widthPct}%', 'height' => '{$heightPct}%', 'clip' => '$polygon'],\n";
}
