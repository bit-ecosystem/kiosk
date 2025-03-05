<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\EmployeeHandbookResource\Pages;
use App\Models\HR\EmployeeHandbook;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeHandbookResource extends Resource
{
    protected static ?string $navigationGroup = 'Editor';

    protected static ?string $model = EmployeeHandbook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->columns([
                //
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
            'index' => Pages\ListEmployeeHandbooks::route('/'),
            'create' => Pages\CreateEmployeeHandbook::route('/create'),
            'edit' => Pages\EditEmployeeHandbook::route('/{record}/edit'),
        ];
    }
}
