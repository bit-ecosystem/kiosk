<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\ServiceMenuResource\Pages;
use App\Models\ServiceMenu;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use App\Filament\Traits\MenuUrlTrait;
class ServiceMenuResource extends Resource
{
    // use MenuUrlTrait;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Self Service';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $parentId = request()->route('record');
                return MenuUrlTrait::getMenuQuery($parentId, 'Staff Self Service');
            })->columns([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\ImageColumn::make('image')
                            ->height('50%')
                            ->width('50%')
                            ->extraAttributes([
                                'style' => 'display: flex; justify-content: center; align-items: center;'
                            ])
                            ->url(function ($record) {
                                return MenuUrlTrait::getMenuUrl($record);
                            })
                            ->openUrlInNewTab(function ($record) {
                                return MenuUrlTrait::ShouldOpenInNewTab($record);
                            }),
                        Tables\Columns\TextColumn::make('title')
                            ->weight('bold')
                            ->searchable()
                            ->extraAttributes([
                                'style' => 'text-align: center;',
                            ])
                            ->url(function ($record) {
                                return MenuUrlTrait::getMenuUrl($record);
                            })
                            ->openUrlInNewTab(function ($record) {
                                return MenuUrlTrait::ShouldOpenInNewTab($record);
                            }),
                    ])->space(3)
                        ->alignment('center')
                        ->extraAttributes(function ($record) {
                            return [
                                'title' => "{$record->description}", // Tooltip content
                                'style' => "background-color: {$record->color}; padding: 5px; border-radius: 5px;",
                            ];
                        }),
                ])
            ->filters([
                // Add filters if needed
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 5,
            ])
            ->paginated(false)
            ->actions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceMenus::route('/{record?}'),
        ];
    }
}
