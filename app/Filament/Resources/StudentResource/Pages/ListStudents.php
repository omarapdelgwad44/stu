<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Student;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getTableQuery(): Builder
    {
        return Student::query();
    }

    protected function getTableColumns(): array
    {
        return [
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
                ->getStateUsing(function (Student $record) {
                    if (!$record->birth_date) return '-';

                    $diff = now()->diff($record->birth_date);
                    return $diff->format('%y سنة، %m شهر، %d يوم');
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),            
        ];
    }
}
