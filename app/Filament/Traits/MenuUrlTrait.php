<?php

namespace App\Filament\Traits;

use App\Models\ServiceMenu;

trait MenuUrlTrait
{
    public static function getMenuUrl($record)
    {
        if ($record->children->isEmpty()) {
            if (str_starts_with($record->url, 'http')) {
                return $record->url;
            } else {
                return rtrim(config('app.url'), '/') . '/' . ltrim($record->url, '/');
            }
        } else {
            return route('filament.staff.resources.service-menus.index', $record);
        }
    }

    public static function ShouldOpenInNewTab($record)
    {
        return $record->children->isEmpty() && str_starts_with($record->url, 'http');
    }
    public static function getMenuQuery($parentId, $category)
    {
        return ServiceMenu::query()
            ->when($parentId, function ($query, $parentId) {
                // If parentId is present, filter by parent_id
                return $query->where('parent_id', $parentId);
            }, function ($query) use ($category) {
                // If parentId is not present, filter by category
                return $query->whereNull('parent_id')
                    ->where('category', $category);
            });
    }
}
