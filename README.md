```markdown
# Filament Image Kit

A powerful image upload component for Filament PHP with automatic thumbnail generation, blur effects, WebP/AVIF conversion, watermarking, and image optimization.

## Features

- ✅ **Automatic Thumbnail Generation** - Create thumbnails automatically
- ✅ **Multiple Thumbnails** - Generate multiple sizes at once
- ✅ **Blur Effect** - Apply Gaussian blur to images
- ✅ **WebP/AVIF Conversion** - Modern image formats for better compression
- ✅ **Image Quality Control** - Set compression quality (1-100)
- ✅ **Watermark** - Add text or image watermarks
- ✅ **Image Filters** - Grayscale, sepia, brightness, contrast
- ✅ **Security Validation** - Min/max dimensions, allowed formats
- ✅ **Responsive Images** - Generate srcset for responsive design
- ✅ **Queue Support** - Process images in background
- ✅ **Cache Support** - Cache processed images
- ✅ **Cropping** - Crop images to exact dimensions
- ✅ **All FileUpload Features** - Inherits all Filament FileUpload methods

## Version Compatibility

| Package Version | Filament Version | PHP Version |
|-----------------|------------------|-------------|
| ^1.0            | v3.x & v4.x      | ^8.1        |

## Installation

```bash
composer require abdullatif/filament-image-kit
```

## Basic Usage

```php
use Abdullatif\FilamentImageKit\Forms\Components\ImageKit;

ImageKit::make('image')
    ->label('Post Image')
    ->image()
    ->directory('posts')
    ->visibility('public')
    ->maxSize(1024)
    ->imagePreviewHeight('200')
    ->previewable(true)
    ->loadingIndicatorPosition('left')
    ->helperText('Normal Image upload for the post (Max 1MB)')
    ->columnSpan(1),
```

## All Usages

### Thumbnail Generation

```php
ImageKit::make('image')->thumbnail(150, 150, '_thumb')
```

### Multiple Thumbnails Generation

```php
ImageKit::make('image')->thumbnails([
    'small' => [100, 100, '_s'],
    'medium' => [300, 200, '_m'],
    'large' => [800, 600, '_l']
])
```

### Blur

```php
ImageKit::make('image')->blur(15)
```

### WebP

```php
ImageKit::make('image')->webp(85)
```

### AVIF

```php
ImageKit::make('image')->avif(75)
```

### Quality

```php
ImageKit::make('image')->quality(80)
```

### Optimize

```php
ImageKit::make('image')->optimize()
```

### Watermark

```php
ImageKit::make('image')->watermark('path/to/logo.png', 70, 9)
```

### Grayscale

```php
ImageKit::make('image')->grayscale()
```

### Sepia

```php
ImageKit::make('image')->sepia()
```

### Brightness

```php
ImageKit::make('image')->brightness(20)
```

### Contrast

```php
ImageKit::make('image')->contrast(15)
```

### Crop

```php
ImageKit::make('image')->crop(1200, 600)
```

### Responsive

```php
ImageKit::make('image')->responsive()
```

### Min Dimensions

```php
ImageKit::make('image')->minDimensions(300, 300)
```

### Max Dimensions

```php
ImageKit::make('image')->maxDimensions(4000, 4000)
```

### Allowed Formats

```php
ImageKit::make('image')->allowedFormats(['jpg', 'png', 'webp'])
```

### Sanitize Filename

```php
ImageKit::make('image')->sanitizeFilename()
```

### Queue

```php
ImageKit::make('image')->queue()
```

### Cache

```php
ImageKit::make('image')->cache(86400)
```

### Lazy Load

```php
ImageKit::make('image')->lazy()
```

### Drag & Drop

```php
ImageKit::make('image')->dragAndDrop()
```

### Live Preview

```php
ImageKit::make('image')->livePreview()
```

### Show Progress

```php
ImageKit::make('image')->showProgress()
```

### Multiple Files

```php
ImageKit::make('image')->multiple()
```

### CDN

```php
ImageKit::make('image')->cdn('https://cdn.example.com')
```

### Cloud Storage

```php
ImageKit::make('image')->cloud('s3')
```

### Preserve EXIF

```php
ImageKit::make('image')->preserveExif()
```

## Complete Example

```php
ImageKit::make('product_image')
    ->image()
    ->directory('products')
    ->thumbnail(300, 200, '_thumb')
    ->blur(15)
    ->webp(85)
    ->quality(80)
    ->minDimensions(800, 600)
    ->maxDimensions(4000, 4000)
    ->allowedFormats(['jpg', 'png', 'webp'])
    ->responsive()
    ->required()
```

## Store Thumbnail Path in Database

```php
ImageKit::make('image')
    ->directory('posts')
    ->thumbnail(60, 60, '_thumb')
    ->afterStateUpdated(function ($state, $set) {
        if ($state && !str_contains($state, 'temp')) {
            $pathInfo = pathinfo($state);
            $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            $set('thumb_image', $thumbnailPath);
        }
    }),

\Filament\Forms\Components\Hidden::make('thumb_image'),
```