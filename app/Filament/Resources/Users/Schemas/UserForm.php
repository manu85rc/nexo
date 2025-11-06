<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\View;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        // Componentes por defecto de usuario
        $defaultComponents = [


            FileUpload::make('profile_photo_path')
                            ->image()
                        ->label('Foto de Perfil')
                        ->directory('profile_photos')
                        ->avatar()
                        ->previewable(),



                // ->label('Foto de Perfil')
                // ->disk('public')
                // ->directory('profile_photos')
                // ->imageEditor()
                // ->visibility('public')
                // // ->square()
                // ->previewable(),




            // TextInput::make('worker.dni_front_path')
            //     ->required()
            //     ->label(__('dni_front_path')),

            // FileUpload::make('worker.dni_front_path')
            //                 ->image()
            //     ->label('DNI Frente')
            //             ->previewable(),


                // ->disk('public')
                // ->directory('dni_photos')
                // ->image()
                // ->imageEditor()
                // ->visibility('public')
                // ->panelLayout('grid'),
//-               >previewable(), 

            
            TextInput::make('name')
                ->required()
                ->label(__('Name')),
            
            TextInput::make('email')
                ->email()
                ->required()
                ->label(__('Email')),
            
            TextInput::make('password')
                ->hidden(fn ($record) => $record)
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required(fn ($record) => ! $record)
                ->rule(\Illuminate\Validation\Rules\Password::default())
                ->dehydrated(fn ($state) => filled($state))
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->label(__('Password')),
            
            TextInput::make('passwordConfirmation')
                ->hidden(fn ($record): mixed => $record)
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required(fn ($record) => ! $record)
                ->dehydrated(false)
                ->label(__('Confirm Password')),
            


// Using Select Component
// Select::make('roles')
//     ->relationship('roles', 'name')
//     ->multiple()
//     ->preload()
//     ->searchable(),



                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable(),


            TextInput::make('current_team_id')
                ->numeric()
                ->label(__('Current Team ID')),
            
            // TextInput::make('profile_photo_path')
            //     ->label(__('Profile Photo')),
        ];
        
        // Campos de Worker
        $workerComponents = [
            TextInput::make('worker.nombre')
                ->required()
                ->label(__('Nombre')),
            TextInput::make('worker.apellido')
                ->required()
                ->label(__('Apellido')),
            TextInput::make('worker.phone')
                ->label('Teléfono')
                ->required(),
            
            Checkbox::make('worker.has_whatsapp')
                ->label('Tiene WhatsApp'),
            
            TextInput::make('worker.dni')
                ->label('DNI')
                ->maxLength(15)
                ->required(),
            
            TextInput::make('worker.cuil_cuit')
                ->label('CUIL/CUIT')
                ->maxLength(20)
                ->required(),
            
            Select::make('worker.tax_status')
                ->label('Condición Fiscal')
                ->options([
                    'monotributo' => 'Monotributo',
                    'responsable inscripto' => 'Responsable Inscripto',
                    'ninguna' => 'Ninguna',
                ])->required(),
            



                

            FileUpload::make('worker.dni_front_path')
                ->label('DNI Frente')
                ->disk('public')
                ->directory('dni_photos')
                ->image()
                ->imageEditor()
                ->visibility('public')
                ->panelLayout('grid'),
//-               >previewable(), 

            FileUpload::make('worker.dni_back_path')
                ->label('DNI Dorso')
                ->disk('public')
                ->directory('dni_photos')
                ->image()
                ->imageEditor()
                ->visibility('public')
                ->previewable(),
                


            // FileUpload::make('profile_photo_path')
            //                 ->image()
            //             ->label('Foto de Perfil')
            //             ->directory('profile_photos')
            //             ->previewable(),

            Repeater::make('worker.skills')
                ->label('Habilidades')
                ->schema([
                    TextInput::make('skill')
                        ->label('Habilidad')
                        ->required(),
                ])
                ->collapsed()
                ->itemLabel(fn (array $state): ?string => $state['skill'] ?? null)
                ->addActionLabel('Agregar Habilidad')
                ->defaultItems(0)
                ->reorderable()
                ->deletable()
                ->cloneable(),







        ];

        // Combinar todos los componentes
        $allComponents = array_merge($defaultComponents, $workerComponents);
        
        return $schema->components($allComponents);
    }
}
