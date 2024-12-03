<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $inverseRelationship = 'group';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Students');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Student Information'))
                    ->description(__('Binding Group to Student'))
                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        Forms\Components\TextInput::make('last_name')
                            ->required(),
                        Forms\Components\TextInput::make('first_name')
                            ->required(),
                        Forms\Components\TextInput::make('father_name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->prefixIcon('heroicon-o-envelope')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->prefixIcon('icon-whatsapp')
                            ->telRegex('/^\+[1-9]\d{1,14}$/')
                            ->placeholder('+37499112233')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpan(['lg' => 3]),
                Forms\Components\Section::make(__('Student Credentials'))
                    ->description(__('Optional'))
                    ->icon('heroicon-o-arrow-right-end-on-rectangle')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            //->required()
                            ->autocomplete(false)
                            ->maxLength(255),
                        Password::make('password')
                            //->required()
                            ->autocomplete(false)
                            ->autocomplete('new-password')
                            ->hidePasswordManagerIcons()
                            ->regeneratePassword(notify: false)
                            ->maxLength(10),
                    ])
                    ->columnSpan(['lg' => 3]),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Student'))
            ->pluralModelLabel(__('Students'))
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name', 'father_name'])
                    ->sortable(['last_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_voted')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_sent')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_leader')
                    ->sortable()
                    ->beforeStateUpdated(function (Student $record) {
                        Student::where('id', '!=', $record->id)
                            ->where('group_id', '=', $record->group_id)
                            ->update(['is_leader' => false]);
                    }),
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
                Tables\Actions\AssociateAction::make()
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
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
