<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\HelpdeskResource\Pages;
use App\Models\ServiceMenu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use App\Filament\Traits\MenuUrlTrait;
class HelpdeskResource extends Resource
{
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationGroup = 'Manager';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Helpdesk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category')
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(1024)
                    ->columnSpanFull(),
                Forms\Components\ColorPicker::make('color')
                    ->hex()
                    ->hexColor(),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('parent_id')
                    ->numeric(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title'),
                ColorEntry::make('color'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('url')
                    ->label('URL')
                    ->columnSpanFull()
                    ->url(fn (ServiceMenu $record): string => '#'.urlencode($record->url)),
                ImageEntry::make('image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $query = static::getModel()::query()->where('category', 'sh');

        return $table
            ->query($query)
            ->columns([
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
                            ->weight(FontWeight::Bold)
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
                //
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
            'index' => Pages\ListHelpdesks::route('/'),
        ];
    }
}
