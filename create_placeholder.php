<?php
// Generate a simple grey no-image SVG placeholder as PNG
$width = 300;
$height = 300;

$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 240, 240, 240);
$border = imagecolorallocate($img, 200, 200, 200);
$textColor = imagecolorallocate($img, 150, 150, 150);

imagefill($img, 0, 0, $bg);
imagerectangle($img, 0, 0, $width-1, $height-1, $border);

// Draw a simple image icon
$cx = $width / 2;
$cy = $height / 2;
imagefilledellipse($img, $cx - 40, $cy - 20, 50, 50, $textColor);
imagerectangle($img, $cx - 80, $cy - 60, $cx + 80, $cy + 60, $textColor);

// Add text
imagestring($img, 5, $cx - 38, $cy + 30, 'No Image', $textColor);

// Save to multiple locations
$paths = [
    __DIR__ . '/shop_admin/uploads/no-image.png',
    __DIR__ . '/assets/no-image.png',
];

foreach ($paths as $path) {
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    imagepng($img, $path);
    echo "Saved: $path\n";
}
imagedestroy($img);
echo "Done!\n";
?>
