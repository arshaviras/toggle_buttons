<?php

namespace App\Filament\Resources\LecturerResource\RelationManagers;

use App\Enums\GroupType;
use App\Enums\LecturerType;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Groups');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Group'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\ToggleButtons::make('type')
                            ->inline()
                            ->options(GroupType::class)
                            ->default('armenian')
                            ->required(),
                        Forms\Components\Select::make('course_id')
                            ->relationship(
                                'course',
                                'id',
                                modifyQueryUsing: fn(Builder $query) => $query->with('department')
                            )
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn(Model $state) =>
                            "{$state->full_name}")
                            ->required(),
                        Forms\Components\ToggleButtons::make('lecturer_type')
                            ->inline()
                            ->multiple()
                            ->options(LecturerType::class)
                            ->default(["practice"])
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 2]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Group'))
            ->pluralModelLabel(__('Groups'))
            ->heading(__('Groups'))
            ->recordTitle(fn($record): string => "{$record->name}")
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('course.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('lecturer_type')
                    ->formatStateUsing(fn(string $state): string => __($state))
                    ->color(fn(string $state): string => match ($state) {
                        'practice' => 'info',
                        'lecture' => 'warning',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'practice' => 'heroicon-m-beaker',
                        'lecture' => 'heroicon-m-newspaper',
                    })
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->recordSelectSearchColumns(['name'])
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\ToggleButtons::make('lecturer_type')
                            ->inline()
                            ->multiple()
                            ->options(LecturerType::class)
                            ->default(["practice"])
                            ->required(),
                    ])
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                //Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
