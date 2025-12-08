<?php
/**
 * PWA Icon Generator
 *
 * Visit this file in your browser to generate PNG icons from the SVG.
 * After generation, you can delete this file.
 *
 * Requirements: PHP GD extension with FreeType support
 * Alternative: Use an online tool like https://realfavicongenerator.net/
 */

// Prevent direct access in production (remove this check when you need to run it)
// if (!isset($_GET['generate'])) {
//     die('Add ?generate=1 to run the generator');
// }

$iconDir = __DIR__ . '/images/icons/';
$svgFile = $iconDir . 'icon.svg';

// Check if GD is available
if (!extension_loaded('gd')) {
    echo "<h1>GD Extension Required</h1>";
    echo "<p>The GD extension is not installed. You have two options:</p>";
    echo "<ol>";
    echo "<li>Enable GD in your php.ini file</li>";
    echo "<li>Use an online tool to convert the SVG to PNG icons</li>";
    echo "</ol>";
    echo "<h2>Manual Steps:</h2>";
    echo "<ol>";
    echo "<li>Open the SVG file at: <code>/images/icons/icon.svg</code></li>";
    echo "<li>Use an online converter like <a href='https://cloudconvert.com/svg-to-png' target='_blank'>CloudConvert</a></li>";
    echo "<li>Generate these sizes: 192x192 and 512x512</li>";
    echo "<li>Save as: icon-192.png, icon-512.png, icon-maskable-192.png, icon-maskable-512.png</li>";
    echo "</ol>";
    exit;
}

// Icon sizes needed
$sizes = [192, 512];

echo "<!DOCTYPE html><html><head><title>PWA Icon Generator</title>";
echo "<style>body{font-family:sans-serif;padding:20px;max-width:800px;margin:0 auto}";
echo ".success{color:green}.error{color:red}.icon-preview{display:inline-block;margin:10px;text-align:center}";
echo ".icon-preview img{border:1px solid #ccc;border-radius:8px}</style></head><body>";
echo "<h1>PWA Icon Generator</h1>";

// Check if Imagick is available (better SVG support)
if (extension_loaded('imagick')) {
    echo "<p class='success'>Using Imagick extension for better SVG rendering.</p>";

    foreach ($sizes as $size) {
        try {
            // Regular icon
            $imagick = new Imagick();
            $imagick->setBackgroundColor(new ImagickPixel('transparent'));
            $imagick->readImage($svgFile);
            $imagick->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageFormat('png');
            $imagick->writeImage($iconDir . "icon-{$size}.png");
            echo "<p class='success'>Created icon-{$size}.png</p>";

            // Maskable icon (with padding for safe zone)
            $imagick = new Imagick();
            $imagick->setBackgroundColor(new ImagickPixel('#0d6efd'));
            $imagick->readImage($svgFile);

            // For maskable icons, scale down to 80% and center
            $innerSize = (int)($size * 0.8);
            $imagick->resizeImage($innerSize, $innerSize, Imagick::FILTER_LANCZOS, 1);

            // Create canvas with background
            $canvas = new Imagick();
            $canvas->newImage($size, $size, new ImagickPixel('#0d6efd'));
            $canvas->setImageFormat('png');

            // Center the icon
            $offset = (int)(($size - $innerSize) / 2);
            $canvas->compositeImage($imagick, Imagick::COMPOSITE_OVER, $offset, $offset);
            $canvas->writeImage($iconDir . "icon-maskable-{$size}.png");
            echo "<p class='success'>Created icon-maskable-{$size}.png</p>";

        } catch (Exception $e) {
            echo "<p class='error'>Error creating {$size}px icon: " . $e->getMessage() . "</p>";
        }
    }
} else {
    // Fallback: Create simple icons with GD
    echo "<p>Using GD extension (limited SVG support).</p>";
    echo "<p class='error'>For best results, please use an online SVG to PNG converter.</p>";

    foreach ($sizes as $size) {
        // Create a simple branded icon with GD
        $image = imagecreatetruecolor($size, $size);

        // Enable alpha blending
        imagesavealpha($image, true);
        imagealphablending($image, false);

        // Colors
        $blue = imagecolorallocate($image, 13, 110, 253);
        $white = imagecolorallocate($image, 255, 255, 255);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);

        // Fill with blue gradient (simplified)
        imagefill($image, 0, 0, $blue);

        // Draw a simple "T" letter for TVET
        $fontSize = (int)($size * 0.5);
        $fontPath = __DIR__ . '/../resources/fonts/Comfortaa-Bold.ttf';

        if (file_exists($fontPath)) {
            // Use custom font
            $bbox = imagettfbbox($fontSize, 0, $fontPath, 'T');
            $x = ($size - ($bbox[2] - $bbox[0])) / 2;
            $y = ($size + ($bbox[1] - $bbox[7])) / 2;
            imagettftext($image, $fontSize, 0, (int)$x, (int)$y, $white, $fontPath, 'T');
        } else {
            // Use built-in font
            $font = 5; // Largest built-in font
            $textWidth = imagefontwidth($font) * strlen('TV');
            $textHeight = imagefontheight($font);
            $x = ($size - $textWidth) / 2;
            $y = ($size - $textHeight) / 2;
            imagestring($image, $font, (int)$x, (int)$y, 'TV', $white);
        }

        // Save regular icon
        imagepng($image, $iconDir . "icon-{$size}.png");
        echo "<p class='success'>Created icon-{$size}.png (simplified)</p>";

        // Maskable is same for GD version
        imagepng($image, $iconDir . "icon-maskable-{$size}.png");
        echo "<p class='success'>Created icon-maskable-{$size}.png (simplified)</p>";

        imagedestroy($image);
    }
}

echo "<h2>Icon Preview</h2>";
echo "<div class='icon-preview'><img src='/images/icons/icon.svg' width='64' height='64'><br>SVG</div>";

foreach ($sizes as $size) {
    $file = "icon-{$size}.png";
    if (file_exists($iconDir . $file)) {
        $displaySize = min($size, 128);
        echo "<div class='icon-preview'><img src='/images/icons/{$file}' width='{$displaySize}' height='{$displaySize}'><br>{$size}px</div>";
    }
}

echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li>Verify the icons look correct above</li>";
echo "<li>Test your PWA by visiting your site on a mobile device</li>";
echo "<li><strong>Delete this file</strong> (generate-icons.php) after you're done</li>";
echo "</ol>";

echo "<p><strong>Tip:</strong> For professional-quality icons, use <a href='https://realfavicongenerator.net/' target='_blank'>Real Favicon Generator</a> with your SVG file.</p>";

echo "</body></html>";
