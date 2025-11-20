# Gumlet Asset Transformer for Craft CMS

Adds Gumlet powered asset transforms to Craft CMS:

1. Drop-in replacement for Craft CMS native image transforms and `.srcset()` method
2. Add additional Gumlet parameters to image transforms
3. Use Gumlet for CP thumbnails
4. Allows `.pdf` files to be rasterized and transformed (unlike Craft CMS transforms)

The only thing you'll need to update is your filesystem Base URL to use your Gumlet domain.

## Requirements

This plugin requires Craft CMS 4.0.0 or later, and PHP 8.0.2 or later.

## Installation

You can install this plugin with Composer.

```bash
composer require gumlet/craft-gumlet-imagetransformer -w && php craft plugin/install gumlet-imagetransformer
```

Then update your filesystem Base URL to use your Gumlet domain.

## Configuration

Copy `config.php` into Craft's config folder and rename it to `gumlet-imagetransformer.php`.

```bash
cp vendor/gumlet/craft-gumlet-imagetransformer/src/config.php config/gumlet-imagetransformer.php
```

Update the `gumletDomain` config setting with your Gumlet domain.

```php
return [
    'gumletDomain' => 'your-domain.gumlet.io',
    'enabled' => true,
    'defaultQuality' => 80,
    'defaultFormat' => 'auto',
    'autoFormat' => true,
];
```

## Usage

This plugin is a drop-in replacement for Craft CMS native image transforms and `.srcset()` method.

You shouldn't have to update any of your templates unless you want to add additional Gumlet parameters.

### Basic Usage

```twig
{# Set the transform #}
{% do asset.setTransform({ 
    width: 300, 
    height: 300,
}) %}

{# Render the tag #}
{{ tag('img', {
  src: asset.url,
  width: asset.width,
  height: asset.height,
  srcset: asset.getSrcset(['1.5x', '2x', '3x']),
  alt: asset.title,
}) }}
```

### Adding additional transform parameters

In addition to the standard Craft CMS transform options:

* `mode`
* `width`
* `height`
* `quality`
* `format`
* `position`
* `fill`

You can also apply additional Gumlet parameters to your image transforms by adding them to the transform options under the `gumlet` object key.

```twig
{# Set the transform #}
{% do asset.setTransform({ 
    width: 300, 
    height: 300,
    gumlet: {
        blur: 20,
        brightness: 10,
        contrast: 5,
    },
}) %}

{# Render the tag #}
{{ tag('img', {
  src: asset.url,
  width: asset.width,
  height: asset.height,
  srcset: asset.getSrcset(['1.5x', '2x', '3x']),
  alt: asset.title,
}) }}
```

### Available Gumlet Parameters

Gumlet supports many transformation parameters. Some common ones include:

* `blur` - Apply blur effect (0-100)
* `brightness` - Adjust brightness (-100 to 100)
* `contrast` - Adjust contrast (-100 to 100)
* `saturation` - Adjust saturation (-100 to 100)
* `sharpen` - Sharpen the image (0-100)
* `rotate` - Rotate image (degrees)
* `flip` - Flip image ('h' for horizontal, 'v' for vertical)
* `watermark` - Add watermark
* `text` - Add text overlay

For a complete list of available parameters, see the [Gumlet Image Transformation API documentation](https://docs.gumlet.com/docs/image-transform-apis).

**Note:** This plugin uses Gumlet's standard short parameter names (`w` for width, `h` for height, `q` for quality, `f` for format). These are the default parameter names used by Gumlet's image transformation API.

#### `gumlet` object key values

In Craft CMS < v5.6.0 the additional `gumlet` object key values may be lost when calling `.srcset()`. This is a limitation of how Craft CMS handles transform parameters.

## Mapping

### Transform Modes

Craft CMS transform modes are mapped to Gumlet fit parameters:

* `crop` → `crop`
* `fit` → `clip`
* `stretch` → `scale`
* `letterbox` → `clip`

### Position

Craft CMS positions are mapped to Gumlet crop positions:

* `top-left` → `top-left`
* `top-center` → `top`
* `top-right` → `top-right`
* `center-left` → `left`
* `center-center` → `center`
* `center-right` → `right`
* `bottom-left` → `bottom-left`
* `bottom-center` → `bottom`
* `bottom-right` → `bottom-right`

## About

Gumlet asset transforms for Craft CMS

