<?php

namespace App\Filament\Exports;

use App\Models\ServiceMenu;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ServiceMenuExporter extends Exporter
{
    protected static ?string $model = ServiceMenu::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('category'),
            ExportColumn::make('title'),
            ExportColumn::make('description'),
            ExportColumn::make('domain_id'),
            ExportColumn::make('path'),
            ExportColumn::make('param'),
            ExportColumn::make('image'),
            ExportColumn::make('parent_id'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your service menu export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
