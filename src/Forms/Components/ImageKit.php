<?php

namespace Abdullatif\FilamentImageKit\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageKit extends FileUpload
{
    // ========== CORE PROPERTIES (renamed to avoid conflicts) ==========
    protected bool|\Closure $generateThumbnail = false;
    protected int|\Closure|null $thumbnailWidthValue = null;
    protected int|\Closure|null $thumbnailHeightValue = null;
    protected string|\Closure|null $thumbnailSuffixValue = null;
    protected array|\Closure $multipleThumbnailsConfig = [];
    protected bool|\Closure $blurImageEnabled = false;
    protected int|\Closure|null $blurIntensityValue = null;
    protected bool|\Closure $responsiveEnabled = false;
    protected array|\Closure $responsiveSizesConfig = [];
    protected bool|\Closure $cropEnabled = false;
    protected int|\Closure|null $cropWidthValue = null;
    protected int|\Closure|null $cropHeightValue = null;
    
    // ========== OPTIMIZATION PROPERTIES ==========
    protected bool|\Closure $webpEnabled = false;
    protected bool|\Closure $avifEnabled = false;
    protected int|\Closure|null $qualityValue = null;
    protected bool|\Closure $optimizeEnabled = false;
    
    // ========== FILTERS & WATERMARK ==========
    protected bool|\Closure $watermarkEnabled = false;
    protected string|\Closure|null $watermarkPathValue = null;
    protected int|\Closure $watermarkPositionValue = 9;
    protected int|\Closure $watermarkOpacityValue = 70;
    protected bool|\Closure $grayscaleEnabled = false;
    protected bool|\Closure $sepiaEnabled = false;
    protected int|\Closure|null $brightnessValue = null;
    protected int|\Closure|null $contrastValue = null;
    
    // ========== SECURITY PROPERTIES (renamed) ==========
    protected int|\Closure|null $minImageWidth = null;
    protected int|\Closure|null $minImageHeight = null;
    protected int|\Closure|null $maxImageWidth = null;
    protected int|\Closure|null $maxImageHeight = null;
    protected array|\Closure $allowedImageFormats = [];
    protected bool|\Closure $sanitizeFilenamesEnabled = true;
    
    // ========== PERFORMANCE PROPERTIES ==========
    protected bool|\Closure $useQueueProcessing = false;
    protected bool|\Closure $useImageCache = false;
    protected int|\Closure $cacheTtlValue = 86400;
    protected bool|\Closure $lazyLoadEnabled = false;
    
    // ========== UX PROPERTIES ==========
    protected bool|\Closure $dragAndDropEnabled = true;
    protected bool|\Closure $livePreviewEnabled = true;
    protected bool|\Closure $showProgressEnabled = true;
    
    // ========== ADVANCED PROPERTIES ==========
    protected bool|\Closure $cdnEnabled = false;
    protected string|\Closure|null $cdnUrlValue = null;
    protected bool|\Closure $preserveExifEnabled = false;
    protected string|\Closure|null $cloudDiskName = null;


    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loadConfigDefaults();
        
        // Use saveRelationshipsUsing for reliable processing
        $this->saveRelationshipsUsing(function ($component, $state) {
            if (!$state) return;
            $this->processImages($state, $component);
        });
        
        // Add image validation - CORRECT WAY
        $this->image();
        $this->maxSize(5120);
    }
    
    // ========== CORE FEATURES ==========
    
    public function thumbnail(int|\Closure $width, int|\Closure $height, ?string $suffix = null): static
    {
        $this->generateThumbnail = true;
        $this->thumbnailWidthValue = $width;
        $this->thumbnailHeightValue = $height;
        
        if ($suffix) {
            $this->thumbnailSuffixValue = $suffix;
        }
        
        return $this;
    }
    
    public function thumbnails(array|\Closure $sizes): static
    {
        $this->generateThumbnail = true;
        $this->multipleThumbnailsConfig = $sizes;
        return $this;
    }
    
    public function blur(int|\Closure $intensity = 15): static
    {
        $this->blurImageEnabled = true;
        $this->blurIntensityValue = $intensity;
        return $this;
    }
    
    public function responsive(array|\Closure $sizes = [640, 768, 1024, 1280]): static
    {
        $this->responsiveEnabled = true;
        $this->responsiveSizesConfig = $sizes;
        return $this;
    }
    
    public function crop(int|\Closure $width, int|\Closure $height): static
    {
        $this->cropEnabled = true;
        $this->cropWidthValue = $width;
        $this->cropHeightValue = $height;
        return $this;
    }
    
    // ========== IMAGE OPTIMIZATION ==========
    
    public function webp(int|\Closure $quality = 80): static
    {
        $this->webpEnabled = true;
        $this->qualityValue = $quality;
        return $this;
    }
    
    public function avif(int|\Closure $quality = 75): static
    {
        $this->avifEnabled = true;
        $this->qualityValue = $quality;
        return $this;
    }
    
    public function quality(int|\Closure $quality): static
    {
        $this->qualityValue = $quality;
        return $this;
    }
    
    public function optimize(bool|\Closure $enabled = true): static
    {
        $this->optimizeEnabled = $enabled;
        return $this;
    }
    
    // ========== FILTERS & WATERMARK ==========
    
    public function watermark(string|\Closure $imagePath, int|\Closure $opacity = 70, int|\Closure $position = 9): static
    {
        $this->watermarkEnabled = true;
        $this->watermarkPathValue = $imagePath;
        $this->watermarkOpacityValue = $opacity;
        $this->watermarkPositionValue = $position;
        return $this;
    }
    
    public function grayscale(): static
    {
        $this->grayscaleEnabled = true;
        return $this;
    }
    
    public function sepia(): static
    {
        $this->sepiaEnabled = true;
        return $this;
    }
    
    public function brightness(int|\Closure $level): static
    {
        $this->brightnessValue = $level;
        return $this;
    }
    
    public function contrast(int|\Closure $level): static
    {
        $this->contrastValue = $level;
        return $this;
    }
    
    // ========== SECURITY ==========
    
    public function minDimensions(int|\Closure $width, int|\Closure $height): static
    {
        $this->minImageWidth = $width;
        $this->minImageHeight = $height;
        return $this;
    }
    
    public function maxDimensions(int|\Closure $width, int|\Closure $height): static
    {
        $this->maxImageWidth = $width;
        $this->maxImageHeight = $height;
        return $this;
    }
    
    public function allowedFormats(array|\Closure $formats): static
    {
        $this->allowedImageFormats = $formats;
        return $this;
    }
    
    public function sanitizeFilename(bool|\Closure $sanitize = true): static
    {
        $this->sanitizeFilenamesEnabled = $sanitize;
        return $this;
    }
    
    // ========== PERFORMANCE ==========
    
    public function queue(bool|\Closure $enabled = true): static
    {
        $this->useQueueProcessing = $enabled;
        return $this;
    }
    
    public function cache(int|\Closure $ttl = 86400): static
    {
        $this->useImageCache = true;
        $this->cacheTtlValue = $ttl;
        return $this;
    }
    
    public function lazy(bool|\Closure $enabled = true): static
    {
        $this->lazyLoadEnabled = $enabled;
        return $this;
    }
    
    // ========== UX FEATURES ==========
    
    // ========== UX FEATURES ==========

    public function dragAndDrop(bool|\Closure $enabled = true): static
    {
        $this->dragAndDropEnabled = $enabled;
        // Drag and drop is enabled by default in FileUpload
        return $this;
    }

    public function livePreview(bool|\Closure $enabled = true): static
    {
        $this->livePreviewEnabled = $enabled;
        
        if ($enabled) {
            // This exists in Filament FileUpload
            $this->previewable(true);
        }
        
        return $this;
    }

    public function showProgress(bool|\Closure $enabled = true): static
    {
        $this->showProgressEnabled = $enabled;
        // Progress indicator is automatically shown in FileUpload
        // No additional method needed
        return $this;
    }
    
    // ========== ADVANCED FEATURES ==========
    
    public function cdn(string|\Closure $cdnUrl): static
    {
        $this->cdnEnabled = true;
        $this->cdnUrlValue = $cdnUrl;
        return $this;
    }
    
    public function cloud(string|\Closure $disk = 's3'): static
    {
        $this->cloudDiskName = $disk;
        return $this;
    }
    
    public function preserveExif(bool|\Closure $preserve = true): static
    {
        $this->preserveExifEnabled = $preserve;
        return $this;
    }
    
    // ========== PROCESSING METHODS ==========
    
    protected function processImages($state, $component): void
    {
        try {
            $paths = $this->getPathsFromState($state);
            
            foreach ($paths as $originalPath) {
                $fullPath = Storage::disk($component->getDiskName())->path($originalPath);
                
                if (!file_exists($fullPath)) continue;
                
                // Apply security validation
                $this->validateSecurity($fullPath);
                
                // Sanitize filename
                if ($this->sanitizeFilenamesEnabled) {
                    $originalPath = $this->sanitizeFileNameString($originalPath);
                }
                
                // Process based on queue setting
                if ($this->useQueueProcessing) {
                    // Dispatch queue job - you'll need to create this
                } else {
                    $this->applyAllProcessing($fullPath, $originalPath, $component);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ImageKit processing failed: ' . $e->getMessage());
        }
    }
    
    protected function applyAllProcessing($fullPath, $originalPath, $component): void
    {
        // Get quality setting (default 85)
        $quality = $this->evaluate($this->qualityValue) ?? 85;
        
        // Create image manager
        $manager = new ImageManager(['driver' => config('filament-image-kit.driver', 'gd')]);
        $image = $manager->make($fullPath);
        
        // \Illuminate\Support\Facades\Log::info('ImageKit Processing', [
        //     'quality' => $quality,
        //     'webp_enabled' => $this->webpEnabled,
        //     'original_path' => $originalPath,
        //     'mime' => $image->mime()
        // ]);
        
        // Apply cropping first
        if ($this->cropEnabled && $this->cropWidthValue && $this->cropHeightValue) {
            $image->crop($this->evaluate($this->cropWidthValue), $this->evaluate($this->cropHeightValue));
        }
        
        // Apply filters
        if ($this->grayscaleEnabled) $image->greyscale();
        if ($this->sepiaEnabled) $image->sepia();
        if ($this->brightnessValue) $image->brightness($this->evaluate($this->brightnessValue));
        if ($this->contrastValue) $image->contrast($this->evaluate($this->contrastValue));
        
        // Apply blur
        if ($this->blurImageEnabled) {
            $intensity = min(20, max(1, $this->evaluate($this->blurIntensityValue)));
            $image->blur($intensity);
        }
        
        // Apply watermark
        if ($this->watermarkEnabled && $this->watermarkPathValue) {
            $watermarkImg = $manager->make($this->evaluate($this->watermarkPathValue));
            $watermarkImg->opacity($this->evaluate($this->watermarkOpacityValue));
            $image->insert($watermarkImg, $this->evaluate($this->watermarkPositionValue));
        }
        
        // Save original with quality compression
        $this->saveImageWithQuality($image, $fullPath, $quality);
        
        // IMPORTANT: Re-create the image instance for WebP/AVIF
        // because the previous instance might be modified
        $image = null;
        
        // Generate WebP version from the optimized original
        if ($this->webpEnabled) {
            $this->convertToWebp($originalPath, $component, $quality);
        }
        
        // Generate AVIF version from the optimized original
        if ($this->avifEnabled) {
            $this->convertToAvif($originalPath, $component, $quality);
        }
        
        // Generate thumbnails (pass quality)
        if ($this->generateThumbnail) {
            $this->generateAllThumbnails($originalPath, $component, $quality);
        }
        
        // Generate responsive images (pass quality)
        if ($this->responsiveEnabled) {
            $this->generateResponsiveImages($originalPath, $component, $quality);
        }
    }
    
    protected function generateAllThumbnails($originalPath, $component, $quality): void
    {
        // Single thumbnail
        if (!empty($this->thumbnailWidthValue)) {
            $this->createThumbnail($originalPath, $component, 
                $this->evaluate($this->thumbnailWidthValue), 
                $this->evaluate($this->thumbnailHeightValue), 
                $this->evaluate($this->thumbnailSuffixValue) ?? '_thumb',
                $quality
            );
        }
        
        // Multiple thumbnails
        $thumbnails = $this->evaluate($this->multipleThumbnailsConfig);
        foreach ($thumbnails as $name => $config) {
            if (is_array($config) && count($config) >= 2) {
                [$width, $height, $suffix] = $config + [null, null, '_' . $name];
                $this->createThumbnail($originalPath, $component, $width, $height, $suffix, $quality);
            }
        }
    }
    
    protected function createThumbnail($originalPath, $component, $width, $height, $suffix, $quality): void
    {
        try {
            $disk = $component->getDiskName();
            $fullOriginalPath = Storage::disk($disk)->path($originalPath);
            
            $thumbnailPath = $this->getCustomThumbnailPath($originalPath, $suffix);
            $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
            
            $manager = new ImageManager(['driver' => config('filament-image-kit.driver', 'gd')]);
            $image = $manager->make($fullOriginalPath);
            
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $image->save($fullThumbnailPath, $quality);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Thumbnail creation failed: ' . $e->getMessage());
        }
    }
    
    protected function generateResponsiveImages($originalPath, $component): void
    {
        try {
            $disk = $component->getDiskName();
            $fullOriginalPath = Storage::disk($disk)->path($originalPath);
            $dirname = dirname($originalPath);
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
            
            $sizes = $this->evaluate($this->responsiveSizesConfig);
            foreach ($sizes as $size) {
                $responsivePath = $dirname . '/' . $filename . '-' . $size . 'w.' . $extension;
                $fullResponsivePath = Storage::disk($disk)->path($responsivePath);
                
                $manager = new ImageManager(['driver' => config('filament-image-kit.driver', 'gd')]);
                $image = $manager->make($fullOriginalPath);
                $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                
                $quality = $this->evaluate($this->qualityValue) ?? 80;
                $image->save($fullResponsivePath, $quality);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Responsive generation failed: ' . $e->getMessage());
        }
    }
    
    protected function validateSecurity($imagePath): void
    {
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) return;
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];
        
        // Validate dimensions
        if ($this->minImageWidth && $width < $this->evaluate($this->minImageWidth)) {
            throw new \Exception("Image width must be at least {$this->minImageWidth}px");
        }
        if ($this->minImageHeight && $height < $this->evaluate($this->minImageHeight)) {
            throw new \Exception("Image height must be at least {$this->minImageHeight}px");
        }
        if ($this->maxImageWidth && $width > $this->evaluate($this->maxImageWidth)) {
            throw new \Exception("Image width cannot exceed {$this->maxImageWidth}px");
        }
        if ($this->maxImageHeight && $height > $this->evaluate($this->maxImageHeight)) {
            throw new \Exception("Image height cannot exceed {$this->maxImageHeight}px");
        }
        
        // Validate format
        $allowedFormats = $this->evaluate($this->allowedImageFormats);
        if (!empty($allowedFormats)) {
            $format = str_replace('image/', '', $mime);
            if (!in_array($format, $allowedFormats)) {
                throw new \Exception("Format {$format} not allowed. Allowed: " . implode(', ', $allowedFormats));
            }
        }
    }
    
    protected function sanitizeFileNameString($path): string
    {
        $filename = basename($path);
        $sanitized = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return str_replace($filename, $sanitized, $path);
    }
    
    // protected function saveImage($image, $path): void
    // {
    //     $quality = $this->evaluate($this->qualityValue) ?? 90;
    //     $image->save($path, $quality);
    // }
    protected function saveImageWithQuality($image, $path, $quality): void
    {
        // Get original file size for logging
        $originalSize = file_exists($path) ? filesize($path) : 0;
        
        // Get file extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        // Apply quality based on format
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image->save($path, $quality);
        } 
        elseif ($extension === 'png') {
            // PNG: Re-encode with compression
            // For PNG, we need to re-encode the image
            $pngQuality = $this->convertQualityToPngCompression($quality);
            
            // Re-encode PNG with compression
            $encoded = $image->encode('png', $pngQuality);
            
            // Save the compressed PNG
            file_put_contents($path, $encoded);
            
            // Alternative: Convert PNG to JPEG for better compression
            // Uncomment this if you want to convert PNG to JPG
            /*
            $jpegPath = preg_replace('/\.png$/i', '.jpg', $path);
            $image->encode('jpg', $quality);
            $image->save($jpegPath);
            // Delete original PNG
            unlink($path);
            $path = $jpegPath;
            */
        } 
        else {
            $image->save($path, $quality);
        }
        
        // Log compression results
        $newSize = file_exists($path) ? filesize($path) : 0;
        $saved = $originalSize - $newSize;
        $percent = $originalSize > 0 ? round(($saved / $originalSize) * 100) : 0;
        
        // \Illuminate\Support\Facades\Log::info('Image compressed', [
        //     'format' => $extension,
        //     'quality' => $quality,
        //     'original_size' => $originalSize . ' bytes',
        //     'new_size' => $newSize . ' bytes',
        //     'saved' => $saved . ' bytes (' . $percent . '%)'
        // ]);
    }
    
    protected function convertQualityToPngCompression($quality): int
    {
        // PNG uses 0-9 compression level
        // 0 = no compression (largest file, fastest)
        // 9 = max compression (smallest file, slowest)
        // Quality 100 = no compression (0)
        // Quality 0 = max compression (9)
        $compression = max(0, min(9, floor((100 - $quality) / 11.11)));
        
        return (int)$compression;
    }
    
    protected function convertToWebp($originalPath, $component, $quality): void
    {
        try {
            $disk = $component->getDiskName();
            $fullPath = Storage::disk($disk)->path($originalPath);
            
            // Check if file exists and is readable
            if (!file_exists($fullPath)) {
                \Illuminate\Support\Facades\Log::error('WebP conversion: Source file not found', ['path' => $fullPath]);
                return;
            }
            
            // Generate WebP filename
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);
            
            // Create a fresh image instance from the file
            $manager = new ImageManager(['driver' => config('filament-image-kit.driver', 'gd')]);
            $image = $manager->make($fullPath);
            
            // Get original size
            $originalSize = filesize($fullPath);
            
            // Encode as WebP with quality
            $encoded = $image->encode('webp', $quality);
            
            // Save the WebP file
            file_put_contents($webpPath, $encoded);
            
            // Free memory
            $image->destroy();
            
            // Log WebP results
            $webpSize = filesize($webpPath);
            $saved = $originalSize - $webpSize;
            $percent = $originalSize > 0 ? round(($saved / $originalSize) * 100) : 0;
            
            // \Illuminate\Support\Facades\Log::info('WebP created', [
            //     'quality' => $quality,
            //     'original_size' => $originalSize . ' bytes',
            //     'webp_size' => $webpSize . ' bytes',
            //     'saved' => $saved . ' bytes (' . $percent . '%)'
            // ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WebP conversion failed: ' . $e->getMessage(), [
                'original_path' => $originalPath,
                'quality' => $quality
            ]);
        }
    }
    
    protected function convertToAvif($originalPath, $component, $quality): void
    {
        try {
            $disk = $component->getDiskName();
            $fullPath = Storage::disk($disk)->path($originalPath);
            
            // Check if file exists and is readable
            if (!file_exists($fullPath)) {
                \Illuminate\Support\Facades\Log::error('AVIF conversion: Source file not found', ['path' => $fullPath]);
                return;
            }
            
            // Generate AVIF filename
            $avifPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.avif', $fullPath);
            
            // Create a fresh image instance from the file
            $manager = new ImageManager(['driver' => config('filament-image-kit.driver', 'gd')]);
            $image = $manager->make($fullPath);
            
            // Get original size
            $originalSize = filesize($fullPath);
            
            // Encode as AVIF with quality
            $encoded = $image->encode('avif', $quality);
            
            // Save the AVIF file
            file_put_contents($avifPath, $encoded);
            
            // Free memory
            $image->destroy();
            
            // Log AVIF results
            $avifSize = filesize($avifPath);
            $saved = $originalSize - $avifSize;
            $percent = $originalSize > 0 ? round(($saved / $originalSize) * 100) : 0;
            
            // \Illuminate\Support\Facades\Log::info('AVIF created', [
            //     'quality' => $quality,
            //     'original_size' => $originalSize . ' bytes',
            //     'avif_size' => $avifSize . ' bytes',
            //     'saved' => $saved . ' bytes (' . $percent . '%)'
            // ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AVIF conversion failed: ' . $e->getMessage(), [
                'original_path' => $originalPath,
                'quality' => $quality
            ]);
        }
    }
    
    protected function getCustomThumbnailPath($originalPath, $suffix): string
    {
        $directory = dirname($originalPath);
        $filename = basename($originalPath);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        return $directory . '/' . $basename . $suffix . '.' . $extension;
    }
    
    protected function getPathsFromState($state): array
    {
        if (empty($state)) return [];
        if (is_string($state)) return [$state];
        if (is_array($state)) return $state;
        return [];
    }
    
    protected function loadConfigDefaults(): void
    {
        $this->thumbnailWidthValue = config('filament-image-kit.thumbnail.width', 150);
        $this->thumbnailHeightValue = config('filament-image-kit.thumbnail.height', 150);
        $this->thumbnailSuffixValue = config('filament-image-kit.thumbnail.suffix', '_thumb');
        $this->qualityValue = config('filament-image-kit.quality', 85);
    }
    
    protected function registerValidationRules(): void
    {
        $this->rules([
            'file' => 'sometimes|image|max:5120',
        ]);
    }
    
    // ========== GETTERS FOR BLADE VIEW ==========
    public function shouldGenerateThumbnail(): bool { return $this->evaluate($this->generateThumbnail); }
    public function shouldBlur(): bool { return $this->evaluate($this->blurImageEnabled); }
    public function getThumbnailWidth(): int { return $this->evaluate($this->thumbnailWidthValue); }
    public function getThumbnailHeight(): int { return $this->evaluate($this->thumbnailHeightValue); }
    public function hasWatermark(): bool { return $this->evaluate($this->watermarkEnabled); }
    public function isCroppable(): bool { return $this->evaluate($this->cropEnabled); }
    public function isResponsive(): bool { return $this->evaluate($this->responsiveEnabled); }
    public function useLazyLoad(): bool { return $this->evaluate($this->lazyLoadEnabled); }
    public function getCdnUrl(): ?string { return $this->evaluate($this->cdnUrlValue); }
}