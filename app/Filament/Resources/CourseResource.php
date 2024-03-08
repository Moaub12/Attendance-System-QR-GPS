<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                DatePicker::make('year')->required(),
                TextInput::make('code')->required(),
                Select::make('department_id')
                ->relationship('departement', 'name')
                ->nullable(),
                Select::make('year_id')
                ->relationship('year', 'name')
                ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name'),
            TextColumn::make('year'),
            TextColumn::make('code'),
            TextColumn::make('year.name'),
            TextColumn::make('departement.name'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Generate QR Code')
                ->url(fn(Course $record):string => route('gen.qr', ['record' => $record->id]))
                ->openUrlInNewTab()
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
