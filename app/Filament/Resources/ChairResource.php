<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChairResource\Pages;
use App\Filament\Resources\ChairResource\RelationManagers;
use App\Filament\Resources\ChairResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\ChairResource\RelationManagers\LecturersRelationManager;
use App\Filament\Resources\ChairResource\RelationManagers\VotesRelationManager;
use App\Models\Chair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mvenghaus\FilamentPluginTranslatableInline\Forms\Components\TranslatableContainer;

class ChairResource extends Resource
{
    use Translatable;

    protected static ?string $model = Chair::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'department.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Department') => $record->department->name,
        ];
    }

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Chair'))
                    ->schema([
                        Forms\Components\TextInput::make('classifier')
                            ->required(),
                        TranslatableContainer::make(
                            Forms\Components\TextInput::make('name')
                                ->required()
                        ),
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpan(['lg' => 3]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->with('lecturers');
            })
            ->columns([
                Tables\Columns\TextColumn::make('classifier')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->forceSearchCaseInsensitive()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('lecturer_photos')
                    ->label(__('Lecturers'))
                    ->circular()
                    ->stacked()
                    ->limit(5)
                    ->limitedRemainingText(),
                Tables\Columns\TextColumn::make('department.name')
                    ->searchable()
                    ->forceSearchCaseInsensitive()
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
            LecturersRelationManager::class,
            CommentsRelationManager::class,
            VotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChairs::route('/'),
            'create' => Pages\CreateChair::route('/create'),
            'view' => Pages\ViewChair::route('/{record}'),
            'edit' => Pages\EditChair::route('/{record}/edit'),
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
        return __('Chair');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Chairs');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Structure');
    }
}
