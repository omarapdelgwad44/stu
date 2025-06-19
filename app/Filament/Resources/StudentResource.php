<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            TextInput::make('name')
                ->label('اسم الطالب')
                ->required(),

            DatePicker::make('birth_date')
                ->label('تاريخ الميلاد')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) {
                        $set('age', '');
                        return;
                    }

                    $birth = \Carbon\Carbon::parse($state);
                    $now = \Carbon\Carbon::now();
                    $diff = $birth->diff($now);

                    $age = $diff->format('%y سنة، %m شهر، %d يوم');
                    $set('age', $age);
                }),

            TextInput::make('age')
                ->label('العمر')
                ->disabled()
                ->dehydrated(false),
        ]);
    }

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('اسم الطالب')
                ->searchable()
                ->sortable(),

            TextColumn::make('birth_date')
                ->label('تاريخ الميلاد')
                ->date('Y-m-d')
                ->sortable(),

            TextColumn::make('age')
                ->label('العمر')
                ->getStateUsing(function ($record) {
                    if (!$record->birth_date) return '-';

                    $diff = now()->diff($record->birth_date);
                    return $diff->format('%d سنة %y شهر %m يوم');
                }),
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
                                ExportBulkAction::make()

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
