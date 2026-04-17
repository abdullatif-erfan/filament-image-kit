<?php

namespace Abdullatif\FilamentImageKit;

if (!function_exists('filamentVersion')) {
    function filamentVersion(): string
    {
        if (class_exists(\Filament\Panel::class) && method_exists(\Filament\Panel::class, 'getPath')) {
            return '4';
        }
        return '3';
    }
}

if (!function_exists('isFilamentV4')) {
    function isFilamentV4(): bool
    {
        return filamentVersion() === '4';
    }
}