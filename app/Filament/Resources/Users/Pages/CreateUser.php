<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
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
            $workerData['dni_front_path'] = $dniFrontPath[0];
        }

        if ($dniBackPath && is_array($dniBackPath) && isset($dniBackPath[0])) {
            $workerData['dni_back_path'] = $dniBackPath[0];
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

        // Crear el registro de Worker
        // El modelo Worker tiene 'skills' cast como 'array', así que Eloquent lo manejará automáticamente
        if (!empty(array_filter($workerData, fn($value) => !is_null($value) && $value !== '' && $value !== []))) {
            $user->worker()->create($workerData);
        }
    }
}
