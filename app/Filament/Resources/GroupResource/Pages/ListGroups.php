<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All'))
                ->icon('heroicon-o-square-2-stack'),
            'armenian' => Tab::make(__('Armenian'))
                ->icon('icon-am-flag')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'armenian'))
                ->badge(Group::query()->where('type', 'armenian')->count())
                ->badgeColor('success'),
            'english' => Tab::make(__('English'))
                ->icon('icon-en-flag')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'english'))
                ->badge(Group::query()->where('type', 'english')->count())
                ->badgeColor('info'),
            'russian' => Tab::make(__('Russian'))
                ->icon('icon-ru-flag')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'russian'))
                ->badge(Group::query()->where('type', 'russian')->count())
                ->badgeColor('warning'),
        ];
    }
}
