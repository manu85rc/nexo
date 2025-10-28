<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WorkerProfile;
use Illuminate\Support\Facades\Auth;

class WorkerForm extends Component
{
    use WithFileUploads;

    public $telefono;
    public $tiene_whatsapp = false;
    public $dni;
    public $foto;
    public $skills = [];
    public $request;

    protected $rules = [
        'telefono' => 'required|string',
        'dni' => 'nullable|string',
        'foto' => 'nullable|image|max:2048',
        'skills' => 'array',
    ];


    public function mount()
    {
        $profile = WorkerProfile::where('user_id', Auth::id())->first();

        if ($profile) {
            $this->telefono = $profile->telefono;
            $this->tiene_whatsapp = $profile->tiene_whatsapp;
            $this->dni = $profile->dni;
            $this->skills = $profile->skills ?? [];
        }
    }


    public function save()
    {
        $this->validate();

        $path = $this->foto
            ? $this->foto->store('fotos_trabajadores', 'public')
            : null;

        WorkerProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'telefono' => $this->telefono,
                'tiene_whatsapp' => $this->tiene_whatsapp,
                'dni' => $this->dni,
                'foto' => $path,
                'skills' => $this->skills,
            ]
        );


        $previousUrl = session('_previous')['url'] ?? '';
        $previousPath = ltrim(parse_url($previousUrl, PHP_URL_PATH), '/'); // quita la barra inicial
        

        $this->dispatch('savedok');
        if ($previousPath === 'worker') {
            return redirect('dashboard');
        }

        // return redirect('settings/worker');












        
    }

    public function render()
    {
  

        return view('livewire.worker-form');
    }
}
