<?php
// Redo from original: convert + clean in one pass
$src = imagecreatefrompng('/Users/aatifrashid/Desktop/Gemini_Generated_Image_pu69u3pu69u3pu69.png');
$w = imagesx($src);
$h = imagesy($src);

$dst = imagecreatetruecolor($w, $h);
imagesavealpha($dst, true);
imagealphablending($dst, false);
$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefill($dst, 0, 0, $transparent);

// Star watermark zone
function inStarZone($x, $y) {
    return ($x >= 1280 && $y >= 640);
}

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if (inStarZone($x, $y)) continue; // leave transparent

        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $maxDiff = max(abs($r - $g), abs($g - $b), abs($r - $b));
        $avg = ($r + $g + $b) / 3;

        // Background: neutral grey in checkerboard range
        $isBg = ($maxDiff < 12 && $avg >= 95 && $avg <= 155);

        if (!$isBg) {
            $color = imagecolorallocatealpha($dst, $r, $g, $b, 0);
            imagesetpixel($dst, $x, $y, $color);
        }
    }
}

$outPath = __DIR__ . '/images/dental-chart-new.png';
imagepng($dst, $outPath, 9);
echo "Saved cleaned chart ({$w}x{$h})\n";

// Count
$check = imagecreatefrompng($outPath);
$count = 0;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if (((imagecolorat($check, $x, $y) >> 24) & 0x7F) < 127) $count++;
    }
}
echo "Non-transparent pixels: $count\n";
