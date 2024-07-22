<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;


    protected static ?string $label = "Clientes";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'I' => 'Individual',
                        'E' => 'Empresa',
                    ]),

                Forms\Components\TextInput::make('email')
                    ->label('Correo Eléctronico')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('ciudad')
                    ->label('Ciudad')
                    ->maxLength(255)
                    ->required(),


                Forms\Components\TextInput::make('codigo_postal')
                    ->label('Código Postal')
                    ->maxLength(5)
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'I' => 'warning',
                        'E' => 'success',
                    }),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección'),
                Tables\Columns\TextColumn::make('ciudad'),
                Tables\Columns\TextColumn::make('codigo_postal')->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'I' => 'Individual',
                        'E' => 'Empresa'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
