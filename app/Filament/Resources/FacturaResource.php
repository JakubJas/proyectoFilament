<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')
                    ->label('Precio')
                    ->required()
                    ->numeric(),

                // Estados facturado -> F, Pagado -> P, Cancelado -> C, Pendiente->A
                Forms\Components\Select::make('estado')
                    ->options([
                        'Facturado' => 'Facturado',
                        'Pagado' => 'Pagado',
                        'Cancelado' => 'Cancelado',
                        'Pendiente' => 'Pendiente'
                    ]),
                Forms\Components\DateTimePicker::make('fecha_creacion')
                    ->required(),
                Forms\Components\DateTimePicker::make('fecha_pago'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')->label('precio')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    // Estados facturado -> F, Pagado -> P, Cancelado -> C, Pendiente->A
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Facturado' => 'gray',
                        'Pendiente' => 'warning',
                        'Pagado' => 'success',
                        'Cancelado' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('fecha_creacion')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'Facturado' => 'Facturado',
                        'Pagado' => 'Pagado',
                        'Cancelado' => 'Cancelado',
                        'Pendiente' => 'Pendiente'
                    ]),
                Tables\Filters\SelectFilter::make('cliente_id')
                    ->relationship('cliente', 'nombre')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}
