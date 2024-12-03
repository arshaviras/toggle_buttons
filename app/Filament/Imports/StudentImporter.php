<?php

namespace App\Filament\Imports;

use App\Models\Student;
use Illuminate\Support\Str;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('father_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('group')
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->requiredMapping()
                ->rules(['required', 'max:50'])
                ->castStateUsing(function ($state) {
                    if (blank($state)) {
                        return null;
                    } elseif (Str::startsWith($state, '0')) {
                        $new_state = Str::of($state)->replaceFirst('0', '+374')->squish();
                        return Str::replace(' ', '', $new_state);
                    }

                    return Str::replace(' ', '', $state);
                }),
            ImportColumn::make('is_leader')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean'])
                ->castStateUsing(function ($state) {
                    if (blank($state)) {
                        return false;
                    }

                    return $state;
                }),
        ];
    }

    public function resolveRecord(): ?Student
    {
        return Student::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'email' => $this->data['email'],
        ]);

        //return new Student();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
