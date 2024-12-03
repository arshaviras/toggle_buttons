<?php

namespace App\Filament\Resources\LecturerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Comments');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('comment')
                    ->autosize()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Comment'))
            ->pluralModelLabel(__('Comments'))
            ->recordTitleAttribute('comment')
            ->recordTitle(__('Comment'))
            ->columns([
                Tables\Columns\TextColumn::make('student.username'),
                Tables\Columns\TextColumn::make('student.group.name'),
                Tables\Columns\TextColumn::make('academicTerm')
                    ->formatStateUsing(fn(Model $state) =>
                    "{$state->year} - {$state->semester} "),
                Tables\Columns\TextColumn::make('comment')
                    ->wrap()
                    ->words(10)
                    ->copyable()
                    ->copyMessage(__('Comment copied'))
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('lecturer_type')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_term_id')
                    ->relationship('academicTerm', 'id')
                    ->getOptionLabelFromRecordUsing(fn(Model $state) =>
                    "{$state->year} - {$state->semester} ")
                    ->native(false),
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
                //Tables\Actions\ForceDeleteAction::make(),
                //Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                    //Tables\Actions\RestoreBulkAction::make(),
                    //Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
