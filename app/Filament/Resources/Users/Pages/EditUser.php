<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Worker;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargar los datos del worker si existen
        if ($this->record->worker) {
            $worker = $this->record->worker;
            
            // Asegurarse de que skills sea un array y convertir a formato de Filament Repeater
            $skills = $worker->skills;
            if (is_string($skills)) {
                $skills = json_decode($skills, true) ?? [];
            }
            if (!is_array($skills)) {
                $skills = [];
            }
            
            // Convertir a formato de Filament Repeater: [['skill' => '...'], ['skill' => '...']]
            $formattedSkills = [];
            foreach ($skills as $key => $value) {
                if (is_array($value)) {
                    // Array anidado del worker-form livewire
                    foreach ($value as $skillName => $isSelected) {
                        if ($isSelected) {
                            $formattedSkills[] = ['skill' => $skillName];
                        }
                    }
                } elseif (isset($value['skill'])) {
                    // Ya está en formato correcto
                    $formattedSkills[] = $value;
                } elseif (is_string($value)) {
                    // Array simple de strings
                    $formattedSkills[] = ['skill' => $value];
                }
            }
            
            $data['worker'] = [
                'nombre' => $worker->nombre,
                'apellido' => $worker->apellido,
                'phone' => $worker->phone,
                'has_whatsapp' => $worker->has_whatsapp,
                'dni' => $worker->dni,
                'cuil_cuit' => $worker->cuil_cuit,
                'tax_status' => $worker->tax_status,
                'skills' => $formattedSkills,
                'dni_front_path' => $worker->dni_front_path,
                'dni_back_path' => $worker->dni_back_path,
            ];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $user = $this->record;
        $formData = $this->form->getState();

        if (!isset($formData['worker'])) {
            return;
        }

        $workerData = $formData['worker'];

        // Manejar los archivos de DNI
        $dniFrontPath = $workerData['dni_front'] ?? null;
        $dniBackPath = $workerData['dni_back'] ?? null;

        if ($dniFrontPath && is_array($dniFrontPath) && isset($dniFrontPath[0])) {
            // Si es un archivo nuevo, guardarlo
            $workerData['dni_front_path'] = $dniFrontPath[0];
        } elseif (is_string($dniFrontPath)) {
            // Si es una ruta existente, mantenerla
            $workerData['dni_front_path'] = $dniFrontPath;
        }

        if ($dniBackPath && is_array($dniBackPath) && isset($dniBackPath[0])) {
            // Si es un archivo nuevo, guardarlo
            $workerData['dni_back_path'] = $dniBackPath[0];
        } elseif (is_string($dniBackPath)) {
            // Si es una ruta existente, mantenerla
            $workerData['dni_back_path'] = $dniBackPath;
        }

        // Eliminar las claves de archivo temporal
        unset($workerData['dni_front'], $workerData['dni_back']);

        // Convertir skills del formato Repeater [['skill' => '...']] a array simple ['...']
        if (isset($workerData['skills']) && is_array($workerData['skills'])) {
            $simplifiedSkills = [];
            foreach ($workerData['skills'] as $skill) {
                if (is_array($skill) && isset($skill['skill'])) {
                    $simplifiedSkills[] = $skill['skill'];
                } elseif (is_string($skill)) {
                    $simplifiedSkills[] = $skill;
                }
            }
            $workerData['skills'] = $simplifiedSkills;
        }

        // Actualizar o crear el registro de Worker
        // El modelo Worker tiene 'skills' cast como 'array', así que Eloquent lo manejará automáticamente
        $user->worker()->updateOrCreate(
            ['user_id' => $user->id],
            $workerData
        );
    }
}
