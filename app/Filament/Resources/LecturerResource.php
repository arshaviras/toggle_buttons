<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChairResource\RelationManagers\LecturersRelationManager;
use App\Filament\Resources\LecturerResource\RelationManagers\VotesRelationManager;
use App\Filament\Resources\LecturerResource\Pages;
use App\Filament\Resources\LecturerResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\LecturerResource\RelationManagers\GroupsRelationManager;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;
use Filament\GlobalSearch\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;

class LecturerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Lecturer::class;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    //protected static ?string $recordTitleAttribute = 'first_name';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
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
                                ->required()
                                ->default(null)
                        ),
                        Forms\Components\Select::make('chair_id')
                            ->relationship('chair', 'name')
                            ->hiddenOn(LecturersRelationManager::class)
                            ->required(),
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper(),
                    ])
                    ->columns(3)
                    ->columnSpan(['lg' => fn(?Lecturer $record) => $record === null ? 3 : 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Lecturer $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn(Lecturer $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?Lecturer $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(Lecturer $record): string => "{$record->last_name} {$record->first_name} {$record->father_name}")
            ->columns([
                Tables\Columns\ImageColumn::make('photo_avatar')
                    ->label(__('Photo'))
                    ->action(
                        Tables\Actions\Action::make('enlarged-photo')
                            ->modalWidth(MaxWidth::ExtraSmall)
                            ->modalHeading(fn($record): string => $record->full_name)
                            ->modalDescription(fn($record): string => $record->chair->name)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(fn($record) => new HtmlString('<img src ="/storage/' . $record->photo . '" />'))
                            ->visible(fn($record) => $record->photo)
                    )
                    //->defaultImageUrl(url('/storage/placeholder.jpg'))
                    ->size(60)
                    ->circular(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name', 'father_name'])
                    ->forceSearchCaseInsensitive()
                    ->sortable(['last_name'])
                    ->description(fn($record): string => $record->position),
                Tables\Columns\TextColumn::make('chair.name')
                    ->searchable()
                    ->hiddenOn(LecturersRelationManager::class)
                    ->sortable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GroupsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'view' => Pages\ViewLecturer::route('/{record}'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('Lecturer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Lecturers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Structure');
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->full_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'father_name', 'chair.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Chair') => $record->chair->name,
            __('Position') => $record->position,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return LecturerResource::getUrl('view', ['record' => $record]);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make(__('Edit'))
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }
}
