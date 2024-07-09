<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
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

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
    
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }
    
        // Check if the user is a professor
        if ($user->professor) {
            // Get the IDs of the courses the professor teaches
            $courseIds = $user->professor->courses()->pluck('courses.id');
            
            // Filter the students who are enrolled in the same year, department, and semester as the courses the professor teaches
            return Student::whereHas('year', function ($query) use ($courseIds) {
                $query->whereIn('years.id', function ($query) use ($courseIds) {
                    $query->select('year_id')->from('courses')->whereIn('courses.id', $courseIds);
                });
            })->whereHas('departement', function ($query) use ($courseIds) {
                $query->whereIn('departements.id', function ($query) use ($courseIds) {
                    $query->select('departement_id')->from('courses')->whereIn('courses.id', $courseIds);
                });
            })->whereHas('semester', function ($query) use ($courseIds) {
                $query->whereIn('semesters.id', function ($query) use ($courseIds) {
                    $query->select('semester_id')->from('courses')->whereIn('courses.id', $courseIds);
                });
            });
        }
    
        // If the user is neither a super admin nor a professor, return no students
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
    
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('file_number')->required(),
                DatePicker::make('dob')->required(),
                Select::make('user_id')
                ->relationship('user', 'name')
               ->required(),
                Select::make('departement_id')
                ->relationship('departement', 'name')
               ->required(),
                Select::make('year_id')
                ->relationship('year', 'name')
               ->required(),
               Select::make('semester_id')
               ->relationship('semester', 'name')
              ->required(),
                FileUpload::make('image')
                    ->disk('public')->directory('images/students')
                    ->image()
                    ->label(__('Image')),
                // Components\FileUpload::make('image')->required(),
                // Components\BelongsToSelect::make('user_id')->relationship('user', 'name')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('file_number')->sortable()->searchable()->label(__('File Number')),
                TextColumn::make('dob')->sortable()->searchable()->label(__('Date of Birth')),
                ImageColumn::make('image')->label(__('Image')),
                TextColumn::make('year.name'),
                TextColumn::make('departement.name'),
                TextColumn::make('semester.name'),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
