<?php

namespace App\Filament\Resources\CagriKayitlari;

use App\Enums\CozumDurumu;
use App\Filament\Resources\CagriKayitlari\Pages\CreateCagriKaydi;
use App\Filament\Resources\CagriKayitlari\Pages\EditCagriKaydi;
use App\Filament\Resources\CagriKayitlari\Pages\ListCagriKayitlari;
use App\Filament\Resources\CagriKayitlari\Pages\ViewCagriKaydi;
use App\Filament\Resources\Concerns\AuthorizesAdminOnlyDeletes;
use App\Filament\Resources\Concerns\DeniesBaskanYardimcisiWrites;
use App\Models\CagriKaydi;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CagriKaydiResource extends Resource
{
    use AuthorizesAdminOnlyDeletes;
    use DeniesBaskanYardimcisiWrites;

    protected static ?string $model = CagriKaydi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $modelLabel = 'Çağrı kaydı';

    protected static ?string $pluralModelLabel = 'Çağrı kayıtları';

    protected static ?string $navigationLabel = 'Çağrı kayıtları';

    protected static ?string $slug = 'cagri-kayitlari';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('arayan_kisi_gorunum')
                    ->label('Arayan kişi')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn (): ?string => auth()->user()?->name)
                    ->afterStateHydrated(function (TextInput $component, ?CagriKaydi $record): void {
                        $component->state($record?->arayanKisi?->name ?? auth()->user()?->name);
                    }),
                DateTimePicker::make('aranan_saat')
                    ->label('Aranan saat')
                    ->required()
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d.m.Y H:i')
                    ->default(now()),
                TextInput::make('gorusulen_kisi')
                    ->label('Karşıda görüşülen kişi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jira_talep_kodu')
                    ->label('Jira talep kodu')
                    ->maxLength(50),
                Select::make('cozum_durumu')
                    ->label('Çözüm durumu')
                    ->options(CozumDurumu::class)
                    ->required()
                    ->default(CozumDurumu::Beklemede),
                Textarea::make('konu')
                    ->label('Konu')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('arayanKisi.name')
                    ->label('Arayan kişi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('aranan_saat')
                    ->label('Aranan saat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('gorusulen_kisi')
                    ->label('Karşıda görüşülen kişi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('konu')
                    ->label('Konu')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('jira_talep_kodu')
                    ->label('Jira talep kodu')
                    ->placeholder('-')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('cozum_durumu')
                    ->label('Çözüm durumu')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('aranan_saat', 'desc')
            ->filters([
                SelectFilter::make('cozum_durumu')
                    ->label('Çözüm durumu')
                    ->options(CozumDurumu::class),
                SelectFilter::make('arayan_kisi_id')
                    ->label('Arayan kişi')
                    ->relationship('arayanKisi', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Görüntüle')
                    ->visible(fn (CagriKaydi $record): bool => ! static::canEdit($record)),
                EditAction::make()
                    ->visible(fn (CagriKaydi $record): bool => static::canEdit($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['arayanKisi']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCagriKayitlari::route('/'),
            'create' => CreateCagriKaydi::route('/create'),
            'view' => ViewCagriKaydi::route('/{record}'),
            'edit' => EditCagriKaydi::route('/{record}/edit'),
        ];
    }
}
