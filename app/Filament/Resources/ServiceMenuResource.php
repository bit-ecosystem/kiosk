<?php

namespace App\Filament\Resources;

use App\Enums\PageCategoryEnum;
use App\Filament\Resources\ServiceMenuResource\Pages;
use App\Models\ServiceMenu;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;

class ServiceMenuResource extends Resource
{
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Menu')
                        ->schema([
                            Forms\Components\Select::make('category')
                                ->label('Category')
                                ->options(PageCategoryEnum::class)
                                ->required(),
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->disk('public') // Specify the disk
                                ->directory('menu'), // Specify the directory
                        ])->grow(false),
                    Section::make('Details')
                        ->schema([
                            Forms\Components\Textarea::make('title')
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Select::make('parent_id')
                                ->label('Parent')
                                ->relationship('parent', 'title')
                                ->nullable(),
                        ]),
                ])->columnSpanFull(),
                Fieldset::make('URL')
                    ->schema([
                        Forms\Components\Select::make('domain_id')
                            ->label('Domain')
                            ->relationship('domain', 'system') // Use the relationship to get the domain name
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state == 1) {
                                    $set('path', null);
                                }
                            }),
                        Forms\Components\TextInput::make('path')
                            ->label('Path')
                            ->visible(fn ($get) => $get('domain_id') != 1),
                        Forms\Components\Select::make('path')
                            ->label('Path')
                            ->options(self::getRouteOptions())
                            ->visible(fn ($get) => $get('domain_id') == 1),
                        Forms\Components\TextInput::make('param'),
                    ])->columns(3),
            ]);
    }

    protected static function getRouteOptions(): array
    {
        $routes = Route::getRoutes();
        $options = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            $uri = $route->uri();
            if ($name !== null && str_starts_with($name, 'filament.staff') && !str_contains($uri, '{')) {
                $options[$uri] = $uri;
            }
        }

        return $options;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('full_url')
                    ->label('Link')
                    ->getStateUsing(function ($record) {
                        return '<' . $record->domain->system . '>' . $record->path . $record->param;
                    })
                    ->searchable()
                    ->sortable()
                    ->color('gray')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\ServiceMenuExporter::class),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultGroup('category');
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
            'index' => Pages\ListServiceMenus::route('/'),
            'create' => Pages\CreateServiceMenu::route('/create'),
            'edit' => Pages\EditServiceMenu::route('/{record}/edit'),
        ];
    }
}
