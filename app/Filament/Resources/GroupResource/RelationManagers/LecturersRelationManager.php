<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Enums\LecturerType;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class LecturersRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'lecturers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Lecturers');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TranslatableContainer::make(
                            Forms\Components\TextInput::make('first_name')
                                ->required()
                        ),
                        TranslatableContainer::make(
                            Forms\Components\TextInput::make('last_name')
                                ->required()
                        ),
                        TranslatableContainer::make(
                            Forms\Components\TextInput::make('father_name')
                                ->required()
                        ),
                        TranslatableContainer::make(
                            Forms\Components\TextInput::make('position')
                        ),
                        Forms\Components\Select::make('chair_id')
                            ->relationship('chair', 'name')
                            ->required(),
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper(),
                        Forms\Components\ToggleButtons::make('lecturer_type')
                            ->inline()
                            ->multiple()
                            ->default(["practice"])
                            ->options([
                                'practice' => __('Practice'),
                                'lecture' => __('Lecture')
                            ])
                            ->colors([
                                'practice' => 'info',
                                'lecture' => 'warning',
                            ])
                            ->icons([
                                'practice' => 'heroicon-o-pencil',
                                'lecture' => 'heroicon-o-check-circle',
                            ])
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpan(['lg' => fn(?Lecturer $record) => $record === null ? 3 : 2]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Lecturer'))
            ->pluralModelLabel(__('Lecturers'))
            ->recordTitle(fn(Lecturer $record): string => "{$record->full_name}")
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->defaultImageUrl(url('/storage/placeholder.jpg'))
                    ->size(60)
                    ->circular(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('chair.name')
                    //->hiddenOn(LecturersRelationManager::class)
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\ToggleButtons::make('lecturer_type')
                            ->inline()
                            ->multiple()
                            ->options(LecturerType::class)
                            ->default(["practice"])
                            ->required(),
                    ])
                    ->recordSelectSearchColumns(['last_name', 'first_name', 'father_name'])
                    ->forceSearchCaseInsensitive()
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
