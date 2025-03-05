<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\SupportMenuResource\Pages;
use App\Models\ServiceMenu;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use App\Filament\Traits\MenuUrlTrait;
class SupportMenuResource extends Resource
{
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationLabel = 'Support';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {
            $parentId = request()->route('record');
            return MenuUrlTrait::getMenuQuery($parentId, 'Support');
            })->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->height('50%')
                        ->width('50%')
                        ->url(function ($record) {
                            return MenuUrlTrait::getMenuUrl($record);
                        })
                        ->openUrlInNewTab(function ($record) {
                            return MenuUrlTrait::ShouldOpenInNewTab($record);
                        }),
                    //  Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->weight('bold')
                        ->extraAttributes([
                            'style' => 'text-align: center;',
                        ])
                        ->url(function ($record) {
                            return MenuUrlTrait::getMenuUrl($record);
                        })
                        ->openUrlInNewTab(function ($record) {
                            return MenuUrlTrait::ShouldOpenInNewTab($record);
                        }),
                    //    ),
                ])->space(3)
                    ->alignment('center')
                    ->extraAttributes(function ($record) {
                        return [
                            'title' => "{$record->description}", // Tooltip content
                            'style' => "background-color: {$record->color}; padding: 10px; border-radius: 5px;",
                        ];
                    }),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
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
            'index' => Pages\ListSupportMenus::route('/'),
        ];
    }
}
