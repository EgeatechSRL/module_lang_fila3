<?php

declare(strict_types=1);

namespace Modules\Lang\Filament\Resources\TranslationResource\Pages;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Modules\Lang\Filament\Resources\TranslationResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;

class ListTranslations extends XotBaseListRecords
{
    protected static string $resource = TranslationResource::class;

    /**
     * Get list table columns.
     *
     * @return array<Tables\Columns\Column>
     */
    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('lang'),
            TextColumn::make('value'),
            TextColumn::make('namespace'),
            TextColumn::make('group'),
            TextColumn::make('item'),
        ];
    }
}
