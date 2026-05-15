<?php
// Convert checkerboard-background dental chart to true transparency
$src = imagecreatefrompng('/Users/aatifrashid/Desktop/Gemini_Generated_Image_pu69u3pu69u3pu69.png');
$w = imagesx($src);
$h = imagesy($src);

$dst = imagecreatetruecolor($w, $h);
imagesavealpha($dst, true);
imagealphablending($dst, false);
$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefill($dst, 0, 0, $transparent);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // Background detection: neutral grey (r≈g≈b) in the checkerboard range
        $maxDiff = max(abs($r - $g), abs($g - $b), abs($r - $b));
        $avg = ($r + $g + $b) / 3;

        $isBg = ($maxDiff < 12 && $avg >= 95 && $avg <= 155);

        if ($isBg) {
            imagesetpixel($dst, $x, $y, $transparent);
        } else {
            $color = imagecolorallocatealpha($dst, $r, $g, $b, 0);
            imagesetpixel($dst, $x, $y, $color);
        }
    }
}

$outPath = __DIR__ . '/images/dental-chart-new.png';
imagepng($dst, $outPath, 9);
echo "Saved to $outPath ({$w}x{$h})\n";

// Verify alpha
$check = imagecreatefrompng($outPath);
$a1 = (imagecolorat($check, 10, 10) >> 24) & 0x7F;
$a2 = (imagecolorat($check, 400, 300) >> 24) & 0x7F;
echo "Alpha at bg (10,10): $a1 (expect 127)\n";
echo "Alpha at tooth (400,300): $a2 (expect 0)\n";
