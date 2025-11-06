<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WorkerForm extends Component
{
    use WithFileUploads;

    public $phone;
    public $nombre;
    public $apellido;
    public $has_whatsapp = false;
    public $dni;
    public $cuil_cuit;
    public $tax_status;
    public $skills = [];
    public $dni_front;
    public $dni_back;

    protected $rules = [
        'phone' => 'required|string',
        'dni' => 'nullable|string',
        'cuil_cuit' => 'nullable|string',
        'skills' => 'array',
        'dni_front' => 'nullable|image|max:2048',
        'dni_back' => 'nullable|image|max:2048',
    ];


    public function mount()
    {
        $profile = Worker::where('user_id', Auth::id())->first();

        if ($profile) {
            $this->phone = $profile->phone;
            $this->nombre = $profile->nombre;
            $this->apellido = $profile->apellido;
            $this->has_whatsapp = $profile->has_whatsapp;
            $this->dni = $profile->dni;
            $this->cuil_cuit = $profile->cuil_cuit;
            $this->tax_status = $profile->tax_status;
            $this->skills = $profile->skills ?? [];
            $this->dni_front = $profile->dni_front_path;
            $this->dni_back = $profile->dni_back_path;
        }
    }


    public function save()
    {
        $existingWorker = Worker::where('user_id', Auth::id())->first();

        $this->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dni' => 'required|string|max:15',
            'cuil_cuit' => 'required|string|max:20',
            'tax_status' => 'required|in:monotributo,responsable inscripto,ninguna',
            // 'dni_front' => [
            //     'nullable',
            //     'image',
            //     'max:2048',
            //     Rule::requiredIf(fn () => !$existingWorker || empty($existingWorker->dni_front_path)),
            // ],
            // 'dni_back' => [
            //     'nullable',
            //     'image',
            //     'max:2048',
            //     Rule::requiredIf(fn () => !$existingWorker || empty($existingWorker->dni_back_path)),
            // ],
        ]);

        // Manejar reemplazo o borrado de DNI frente
        $newDniFrontPath = $existingWorker?->dni_front_path;
        if ($this->dni_front === null) {
            // Si el usuario borra la imagen
            if ($existingWorker && $existingWorker->dni_front_path) {
                Storage::disk('public')->delete($existingWorker->dni_front_path);
            }
            $newDniFrontPath = null;
        } elseif ($this->dni_front && !is_string($this->dni_front)) {
            // Si sube una nueva imagen
            if ($existingWorker && $existingWorker->dni_front_path) {
                Storage::disk('public')->delete($existingWorker->dni_front_path);
            }
            $newDniFrontPath = $this->dni_front->store('dni_photos', 'public');
        }

        // Manejar reemplazo o borrado de DNI dorso
        $newDniBackPath = $existingWorker?->dni_back_path;
        if ($this->dni_back === null) {
            if ($existingWorker && $existingWorker->dni_back_path) {
                Storage::disk('public')->delete($existingWorker->dni_back_path);
            }
            $newDniBackPath = null;
        } elseif ($this->dni_back && !is_string($this->dni_back)) {
            if ($existingWorker && $existingWorker->dni_back_path) {
                Storage::disk('public')->delete($existingWorker->dni_back_path);
            }
            $newDniBackPath = $this->dni_back->store('dni_photos', 'public');
        }

        Worker::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'phone' => $this->phone,
                'has_whatsapp' => $this->has_whatsapp,
                'dni' => $this->dni,
                'cuil_cuit' => $this->cuil_cuit,
                'tax_status' => $this->tax_status,
                'skills' => json_encode($this->skills),
                'dni_front_path' => $newDniFrontPath, // Siempre guarda la ruta anterior si no hay nueva
                'dni_back_path' => $newDniBackPath,   // Siempre guarda la ruta anterior si no hay nueva
            ]
        );
        
        if (!$existingWorker) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
  

        return view('livewire.worker-form');
    }
}
