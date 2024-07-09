<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoordinateResource\Pages;
use App\Filament\Resources\CoordinateResource\RelationManagers;
use App\Forms\Components\Coordinates;
use App\Models\Coordinate;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoordinateResource extends Resource
{
    protected static ?string $model = Coordinate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Section::make('')->schema([Placeholder::make('Please Create Coordinate to place location ')->translateLabel()])->visibleOn('create'),

                Section::make('')->schema([Coordinates::make('latt_long')->label('Coordinates')->translateLabel()->visibleOn('edit'),]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             TextColumn::make('name'),
             TextColumn::make('longitude'),
             TextColumn::make('latitude'),
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
            'index' => Pages\ListCoordinates::route('/'),
            'create' => Pages\CreateCoordinate::route('/create'),
            'edit' => Pages\EditCoordinate::route('/{record}/edit'),
        ];
    }
}
