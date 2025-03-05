<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\LearningMenuResource\Pages;
use App\Models\ServiceMenu;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use App\Filament\Traits\MenuUrlTrait;
class LearningMenuResource extends Resource
{
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Learning';

    protected static ?int $navigationSort = 5;

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
            return MenuUrlTrait::getMenuQuery($parentId, 'Learning');
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

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->weight('bold')
                            ->url(function ($record) {
                                return MenuUrlTrait::getMenuUrl($record);
                            })
                            ->openUrlInNewTab(function ($record) {
                                return MenuUrlTrait::ShouldOpenInNewTab($record);
                            }),
                    ]),
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
                'xl' => 5,
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
            'index' => Pages\ListLearningMenus::route('/'),
        ];
    }
}
