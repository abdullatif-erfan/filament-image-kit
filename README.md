## Filament Image Kit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdullatif/filament-image-kit.svg?style=flat-square)](https://packagist.org/packages/abdullatif/filament-image-kit)
[![Total Downloads](https://img.shields.io/packagist/dt/abdullatif/filament-image-kit.svg?style=flat-square)](https://packagist.org/packages/abdullatif/filament-image-kit)


Complete image management for Filament PHP. Automatically generate thumbnails, apply blur effects, convert to WebP/AVIF, add watermarks, crop images, validate dimensions, and more. All FileUpload features included. Works with Filament v3 & v4.


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


## Production Ready

- ✅ Used with Filament v3 and v4
- ✅ Supports Laravel 10+
- ✅ Queue processing for large images
- ✅ Cache support for performance
- ✅ CDN and cloud storage ready
- ✅ All FileUpload features inherited


## Installation

```bash
composer require abdullatif/filament-image-kit
// OR 
composer require abdullatif/filament-image-kit:1.0.0

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

### 📸 Automatic Thumbnail Generation
Create thumbnails automatically without writing any Intervention Image code. Just call `->thumbnail(150, 150, '_thumb')` and the package handles everything - resizing, saving, and organizing.

```php
ImageKit::make('image')->thumbnail(150, 150, '_thumb')
```

### 🖼️ Multiple Thumbnails Generation
Generate multiple thumbnail sizes in one line. Perfect for e-commerce where you need cart thumbnails (100x100), grid view (300x300), and zoom view (800x800) from a single upload.

```php
ImageKit::make('image')->thumbnails([
    'small' => [100, 100, '_s'],
    'medium' => [300, 200, '_m'],
    'large' => [800, 600, '_l']
])
```

### 🌫️ Blur Effect
Apply Gaussian blur to images with `->blur(15)`. Perfect for background images where you need text overlay readability. Intensity values 1-20 give you full control from subtle to heavy blur.

```php
ImageKit::make('image')->blur(15)
```

### 🚀 WebP/AVIF Conversion
Automatically convert uploaded images to modern formats. WebP reduces file size by 30-40% compared to JPEG, while AVIF reduces by 50-60%. This means faster loading times, better SEO, and happier users. In our tests, a 1.5MB image was compressed down to just 200KB without losing quality.

```php
ImageKit::make('image')
        ->quality(50)
        ->webp(50)  // OR ->avif(75)
```


### 🎯 Image Quality Control
Set compression quality from 1-100. Lower quality = smaller files. Find the perfect balance for your use case. Quality 85 is the sweet spot for most web images.

```php
ImageKit::make('image')->quality(85)
```

### 🎯 Image Quality Control
Set compression quality from 1-100. Lower quality = smaller files. Find the perfect balance for your use case. Quality 85 is the sweet spot for most web images.

```php
ImageKit::make('image')->optimize()
```

### 💧 Watermark
Protect your images with watermarks. Add your logo with custom opacity (0-100) and position (1-9 numpad layout). Perfect for photo galleries and brand protection

```php
ImageKit::make('image')->watermark('path/to/logo.png', 70, 9)
```

### 🎨 Image Filters
Apply professional filters instantly: grayscale for vintage looks, sepia for old photos, brightness adjustment (-100 to +100), contrast adjustment (-100 to +100). All chainable in one fluent call.

```php
ImageKit::make('image')->grayscale()
                       //   ->sepia()
                       //   ->brightness(20)
                       //   ->contrast(15)
```



### ✂️ Cropping
Crop images to exact dimensions with `->crop(1200, 600)`. Perfect for banners, hero images, and any situation requiring precise dimensions. Works seamlessly with other features.

```php
ImageKit::make('image')->crop(1200, 600)
```

### 📱 Responsive Images
Generate responsive srcset automatically for modern responsive design. Pass an array of widths [640, 768, 1024, 1280, 1920] and the package creates all versions. Perfect for hero images and responsive layouts.

```php
ImageKit::make('image')->responsive([640, 768, 1024, 1280, 1920])
```

### 🔒 Security Validation
Protect your application from malicious uploads. Set minimum dimensions (min 300x300), maximum dimensions (max 4000x4000), allowed formats (only jpg, png, webp), and file size limits and sanitization. All validation happens automatically.

```php
ImageKit::make('image')
    ->minDimensions(300, 300)
    ->maxDimensions(4000, 4000)
    ->allowedFormats(['jpg', 'png', 'webp'])
    ->sanitizeFilename()
```


### ⚡ Queue Support
Process large images in the background. Users don't wait for image processing - they continue working while thumbnails generate in the queue. Essential for high-traffic applications.

```php
ImageKit::make('image')->queue()
```

### 💾 Cache Support
Cache processed images to avoid reprocessing. Subsequent requests load instantly from cache. Configurable TTL (default 24 hours) gives you full control.

```php
ImageKit::make('image')->cache(86400)
```


### CDN Integration
Serve images from your Content Delivery Network for lightning-fast global delivery. `->cdn('https://cdn.example.com')` rewrites image URLs to your CDN domain. Reduce server load, decrease latency, improve international performance. Uploads one copy to local disk and one copy to the CDN.

```php
ImageKit::make('image')->cdn('https://cdn.example.com')
```

### Cloud Storage
Store images directly on cloud storage providers like AWS S3, DigitalOcean Spaces, or Cloudflare R2. `->cloud('s3')` uses your configured filesystem disk. Perfect for scalable applications, multi-server environments, and offloading storage costs.

```php
ImageKit::make('image')->cloud('s3')
```

### 🔧 All FileUpload Features
Inherits every Filament FileUpload method. Everything you love about FileUpload - image editor, circle cropper, multiple files, reorderable, downloadable, openable, previewable - all still works. You just get more features on top.


## Complete Example

```php
ImageKit::make('product_image')
    ->image()
    ->directory('products')
    ->visibility('public')
    ->thumbnail(300, 200, '_thumb')
    ->thumbnails([
        'cart' => [100, 100, '_cart'],
        'grid' => [300, 200, '_grid'],
        'zoom' => [800, 600, '_zoom']
    ])
    ->blur(15)
    ->webp(85)
    ->avif(75)
    ->quality(80)
    ->optimize()
    ->watermark(public_path('logo.png'), 70, 9)
    ->grayscale()
    ->brightness(10)
    ->contrast(5)
    ->crop(1200, 800)
    ->responsive([640, 768, 1024, 1280])
    ->minDimensions(800, 600)
    ->maxDimensions(4000, 4000)
    ->allowedFormats(['jpg', 'png', 'webp'])
    ->sanitizeFilename()
    ->queue()
    ->cache(86400)
    ->lazy()
    ->dragAndDrop()
    ->livePreview()
    ->showProgress()
    ->cdn('https://cdn.example.com')
    ->cloud('s3')
    ->preserveExif()
    ->imageEditor()
    ->circleCropper()
    ->downloadable()
    ->openable()
    ->multiple()
    ->reorderable()
    ->required()
    ->maxSize(5120)
    ->helperText('Upload product image (Max 5MB, recommended size: 1200x800)')
    ->columnSpan(2)
```

### No Migration Required

Unlike Spatie Media Library, this package does not require you to create any specific migrations. You don't need a separate `media` table or any additional database structure.

Based on your needs, the package uploads images and stores the paths directly in your existing table - whether it's a `products` table, `posts` table, or any other table you have already created.

## How It Works

You can upload a single image and automatically generate multiple versions (thumbnail, blurred, WebP, etc.) while storing all paths in separate columns of your existing table. This is exactly the purpose this package was built for.

### Example Database Table

Here's how your existing table can store all image versions:

| id | product_name | product_image | product_thumb_image | product_blurred_image | quantity | unit_price |...|
|----|--------------|---------------|---------------------|----------------------|----------|------------|----|
| 1  | Laptop | products/laptop.png | products/laptop_thumb.png | products/laptop_blur.png | 10 | 999.99 |....|
| 2  | Mouse  | products/mouse.png  | products/mouse_thumb.png  | products/mouse_blur.png  | 50 | 29.99  |....|

### Add Columns to Your Existing Table

```php
Schema::table('products', function (Blueprint $table) {
    $table->string('product_image')->nullable();
    $table->string('product_thumb_image')->nullable();
    $table->string('product_blurred_image')->nullable();
});
```

### Example

```php
ImageKit::make('product_image')
    ->label('Product Image')
    ->image()
    ->directory('products')
    ->thumbnail(100, 100, '_thumb')
    ->blur(15)
    ->webp(85)
    ->quality(80)
    ->afterStateUpdated(function ($state, $set) {
        if ($state && !str_contains($state, 'temp')) {
            // Get the original image path
            $pathInfo = pathinfo($state);
            
            // Generate thumbnail path (same directory, adds _thumb before extension)
            $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            
            // Generate blurred image path (same directory, adds _blur before extension)
            $blurredPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_blur.' . $pathInfo['extension'];
            
            // Store paths in hidden fields
            $set('product_thumb_image', $thumbnailPath);
            $set('product_blurred_image', $blurredPath);
        }
    }),

// Hidden fields to store generated image paths in your database
\Filament\Forms\Components\Hidden::make('product_thumb_image'),
\Filament\Forms\Components\Hidden::make('product_blurred_image'),
```

### Table Column

```php
use Filament\Tables\Columns\ImageColumn;

ImageColumn::make('product_image')
            ->label('Product Image')
            ->circular()
            ->disk('public')
            ->width(50)
            ->height(50),
```


## Credits

- [Abdul Latif "Erfan"](https://github.com/Abdullatif-Erfan)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.