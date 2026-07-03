<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use App\Models\Link;
use App\Services\CodeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $generator = app(CodeGenerator::class);
        $data['user_id'] = auth()->id();
        $data['code'] = $generator->generate();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
