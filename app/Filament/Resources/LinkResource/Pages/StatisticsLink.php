<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use App\Models\Link;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class StatisticsLink extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LinkResource::class;

    protected static string $view = 'filament.resources.link-resource.pages.statistics-link';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function resolveRecord(int|string $record): Link
    {
        $link = Link::where('user_id', auth()->id())->findOrFail($record);

        return $link;
    }

    public function getClicks()
    {
        return $this->record->clicks()
            ->orderBy('clicked_at', 'desc')
            ->paginate(20);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Назад к списку')
                ->url(LinkResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
