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

$topFrac = 0.46;
$heightFrac = 0.42;
$bgThreshold = 25;
$minRunLength = 10;

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

    // For each column, find first and last non-white pixel with run-length filter
    $topEdge = [];
    $bottomEdge = [];

    for ($col = 0; $col < $boxW; $col++) {
        $px = $boxX + $col;

        // Top edge: scan downward, require minRunLength consecutive non-white
        $topEdge[$col] = -1;
        for ($row = 0; $row < $boxH - $minRunLength; $row++) {
            $py = $boxY + $row;
            if (!isNonWhite($img, $px, $py, $w, $h, $bgThreshold)) continue;
            $runOk = true;
            for ($r2 = 1; $r2 < $minRunLength; $r2++) {
                if (!isNonWhite($img, $px, $py + $r2, $w, $h, $bgThreshold)) {
                    $runOk = false;
                    break;
                }
            }
            if ($runOk) {
                $topEdge[$col] = $row;
                break;
            }
        }

        // Bottom edge: scan upward from bottom
        $bottomEdge[$col] = -1;
        for ($row = $boxH - 1; $row >= 0; $row--) {
            $py = $boxY + $row;
            if (!isNonWhite($img, $px, $py, $w, $h, $bgThreshold)) continue;
            // Verify with short upward run
            $runOk = true;
            for ($r2 = 1; $r2 < min($minRunLength, 3); $r2++) {
                if (!isNonWhite($img, $px, $py - $r2, $w, $h, $bgThreshold)) {
                    $runOk = false;
                    break;
                }
            }
            if ($runOk) {
                $bottomEdge[$col] = $row;
                break;
            }
        }
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

    // Clamp missing columns to nearest valid value
    for ($col = 0; $col < $boxW; $col++) {
        foreach (['topEdge', 'bottomEdge'] as $edgeName) {
            $edge = &$$edgeName;
            if ($edge[$col] < 0) {
                $nearest = null;
                for ($d = 1; $d < $boxW; $d++) {
                    if ($col-$d >= 0 && $edge[$col-$d] >= 0) { $nearest = $edge[$col-$d]; break; }
                    if ($col+$d < $boxW && $edge[$col+$d] >= 0) { $nearest = $edge[$col+$d]; break; }
                }
                $edge[$col] = $nearest ?? ($edgeName === 'topEdge' ? $boxH : 0);
            }
        }
    }

    // Smooth with moving average (window=3 for tighter fit)
    $win = 3;
    $smoothTop = []; $smoothBot = [];
    for ($i = 0; $i < $boxW; $i++) {
        $sumT = 0; $sumB = 0; $cnt = 0;
        for ($j = max(0, $i - $win); $j <= min($boxW - 1, $i + $win); $j++) {
            $sumT += $topEdge[$j]; $sumB += $bottomEdge[$j]; $cnt++;
        }
        $smoothTop[$i] = $sumT / $cnt;
        $smoothBot[$i] = $sumB / $cnt;
    }

    // Spike removal for top edge only: clamp points far above the 30th percentile
    $topVals = array_values($smoothTop);
    sort($topVals);
    $medianTop = $topVals[(int)(count($topVals) * 0.30)];
    $spikeThreshold = $boxH * 0.05;
    for ($i = 0; $i < $boxW; $i++) {
        if ($smoothTop[$i] < $medianTop - $spikeThreshold) {
            $smoothTop[$i] = $medianTop;
        }
    }

    // Re-smooth after spike removal (window=2 for minimal blur)
    $win2 = 2;
    $smoothTop2 = [];
    for ($i = 0; $i < $boxW; $i++) {
        $sumT = 0; $cnt = 0;
        for ($j = max(0, $i - $win2); $j <= min($boxW - 1, $i + $win2); $j++) {
            $sumT += $smoothTop[$j]; $cnt++;
        }
        $smoothTop2[$i] = $sumT / $cnt;
    }
    $smoothTop = $smoothTop2;

    // Sample 30 points (more than before) within the tooth range
    $numSamples = 30;
    $polyPoints = [];

    // Inset by 1 pixel from edges for tighter fit
    $insetFirst = min($firstCol + 1, $lastCol);
    $insetLast = max($lastCol - 1, $firstCol);

    // Top edge: left to right
    for ($s = 0; $s < $numSamples; $s++) {
        $col = $insetFirst + (int)(($s / ($numSamples - 1)) * ($insetLast - $insetFirst));
        $xPct = round(($col / $boxW) * 100, 1);
        $yPct = round(($smoothTop[$col] / $boxH) * 100, 1);
        $polyPoints[] = "$xPct% $yPct%";
    }

    // Bottom edge: right to left
    for ($s = $numSamples - 1; $s >= 0; $s--) {
        $col = $insetFirst + (int)(($s / ($numSamples - 1)) * ($insetLast - $insetFirst));
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
