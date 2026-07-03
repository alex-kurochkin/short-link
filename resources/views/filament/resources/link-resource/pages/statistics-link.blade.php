<x-filament-panels::page>
    {{-- Карточка со статистикой --}}
    <x-filament::section>
        <x-slot name="heading">
            Статистика ссылки
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Код:</p>
                <p class="text-lg font-mono text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded inline-block">
                    {{ $this->record->code }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Оригинальный URL:</p>
                <p class="text-lg text-gray-900 dark:text-white break-words overflow-wrap-anywhere max-w-full">
                    {{ $this->record->original_url }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Короткая ссылка:</p>
                <p class="text-lg">
                    <a href="{{ $this->record->short_url }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">
                        {{ $this->record->short_url }}
                    </a>
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Всего кликов:</p>
                <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                    {{ $this->record->clicks_count }}
                </p>
            </div>
        </div>
    </x-filament::section>

    {{-- Карточка с историей переходов --}}
    <x-filament::section>
        <x-slot name="heading">
            История переходов
        </x-slot>

        @if($this->getClicks()->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white">#</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white">IP адрес</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white">Дата и время</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->getClicks() as $index => $click)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-mono text-gray-900 dark:text-white">{{ $click->ip_address }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $click->clicked_at->format('d.m.Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->getClicks()->links() }}
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 italic">Переходов пока нет</p>
        @endif
    </x-filament::section>
</x-filament-panels::page>
