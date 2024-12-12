<?php

declare(strict_types=1);

namespace Modules\Lang\Filament\Resources;

use Filament\Forms\Form;
use Modules\Lang\Filament\Resources\TranslationResource\Pages;
use Modules\Lang\Models\Translation;
use Modules\Xot\Filament\Resources\XotBaseResource;

class TranslationResource extends XotBaseResource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
