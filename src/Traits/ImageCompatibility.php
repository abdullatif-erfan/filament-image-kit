<?php

namespace Abdullatif\FilamentImageKit\Traits;

use Intervention\Image\ImageManager;

trait ImageCompatibility
{
    protected function getImageManager(): ImageManager
    {
        $driver = config('filament-image-kit.driver', 'gd');
        
        // Check if using v3 (ImageManager has different constructor)
        if (class_exists(\Intervention\Image\Drivers\Gd\Driver::class)) {
            // v3 syntax
            return new ImageManager(['driver' => $driver]);
        } else {
            // v2 syntax
            return new ImageManager(['driver' => $driver]);
        }
    }
    
    protected function processImage($imagePath, callable $callback)
    {
        $manager = $this->getImageManager();
        $image = $manager->make($imagePath);
        
        $callback($image);
        
        // v2 and v3 both have save() method
        $image->save($imagePath);
    }
    
    protected function applyBlurToImage($image, int $intensity)
    {
        // Check method exists for version compatibility
        if (method_exists($image, 'blur')) {
            // v2 and v3 both support blur
            $image->blur($intensity);
        }
        return $image;
    }
    
    protected function resizeImage($image, int $width, int $height)
    {
        // Both versions support resize with closure
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        return $image;
    }
}