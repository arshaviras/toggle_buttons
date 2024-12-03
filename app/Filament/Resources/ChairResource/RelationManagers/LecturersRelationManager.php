<?php

namespace App\Filament\Resources\ChairResource\RelationManagers;

use App\Filament\Resources\LecturerResource;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LecturersRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'lecturers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Lecturers');
    }

    protected static ?string $inverseRelationship = 'chair';

    public function form(Form $form): Form
    {
        return LecturerResource::form($form);
    }

    public function table(Table $table): Table
    {
        return LecturerResource::table($table)
            ->modelLabel(__('Lecturer'))
            ->pluralModelLabel(__('Lecturers'))
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make()
                    ->recordSelectSearchColumns(['last_name', 'first_name', 'father_name'])
                    ->forceSearchCaseInsensitive()
                    ->preloadRecordSelect()
                    ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
