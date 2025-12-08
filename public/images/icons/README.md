# PWA Icons

This directory contains icons for the Progressive Web App (PWA).

## Required Files

You need to create the following PNG files:

1. `icon-192.png` - 192x192 pixels
2. `icon-512.png` - 512x512 pixels
3. `icon-maskable-192.png` - 192x192 pixels (with padding for safe area)
4. `icon-maskable-512.png` - 512x512 pixels (with padding for safe area)

## How to Generate Icons

### Option 1: Use the SVG file
The `icon.svg` file in this directory is the master icon. You can convert it to PNG using:
- [CloudConvert](https://cloudconvert.com/svg-to-png)
- [SVGOMG](https://jakearchibald.github.io/svgomg/)
- Adobe Illustrator or Inkscape

### Option 2: Use Real Favicon Generator
1. Visit [realfavicongenerator.net](https://realfavicongenerator.net/)
2. Upload the `icon.svg` file
3. Configure your settings
4. Download and extract the generated icons

### Option 3: Use the Generator Script
1. Make sure PHP GD or Imagick extension is enabled
2. Visit `/generate-icons.php` in your browser
3. The script will create the icons automatically
4. Delete `generate-icons.php` after use

## Maskable Icons

Maskable icons should have the main content within a "safe zone" - about 80% of the icon size, centered. This allows different platforms to crop the icon into circles, rounded squares, etc.

## Testing

After creating the icons, test your PWA:
1. Visit your site on a mobile device
2. Add to Home Screen
3. Verify the icon appears correctly
