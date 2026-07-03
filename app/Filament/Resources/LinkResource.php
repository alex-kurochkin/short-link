<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Мои ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_url')
                    ->label('Оригинальный URL')
                    ->required()
                    ->url()
                    ->maxLength(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Оригинальный URL')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Клики')
                    ->counts('clicks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('short_url')
                    ->label('Короткая ссылка')
                    ->copyable()
                    ->url(fn (Link $record) => $record->short_url, shouldOpenInNewTab: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('statistics')
                    ->label('Статистика')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn (Link $record) => static::getUrl('statistics', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'statistics' => Pages\StatisticsLink::route('/{record}/statistics'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}