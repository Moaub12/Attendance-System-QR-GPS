<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessorResource\Pages;
use App\Filament\Resources\ProfessorResource\RelationManagers;
use App\Models\Professor;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('file_number')->required(),
                DatePicker::make('dob')->required(),
                Select::make('user_id')
                ->relationship('user', 'name')
                ->nullable(),
                FileUpload::make('image')
                    ->disk('public')->directory('images/students')
                    ->image()
                    ->label(__('Image')),
                    Select::make('courses_id')
                    ->relationship('courses', 'name')
                    ->multiple(true)
                    ->required(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('user.email'),
                TextColumn::make('file_number')->sortable()->searchable()->label(__('File Number')),
                TextColumn::make('dob')->sortable()->searchable()->label(__('Date of Birth')),
                ImageColumn::make('image')->label(__('Image')),
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
            'index' => Pages\ListProfessors::route('/'),
            'create' => Pages\CreateProfessor::route('/create'),
            'edit' => Pages\EditProfessor::route('/{record}/edit'),
        ];
    }
}
