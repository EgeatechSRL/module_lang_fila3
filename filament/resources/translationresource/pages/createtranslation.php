<?php

declare(strict_types=1);

namespace Modules\Lang\Filament\Resources\TranslationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Lang\Filament\Resources\TranslationResource;

class CreateTranslation extends CreateRecord
{
    protected static string $resource = TranslationResource::class;
}
