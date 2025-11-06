@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

<div class="max-w-3xl mx-auto p-6 rounded-2xl shadow">
    <form wire:submit.prevent="save" class="space-y-4">

<div>
    <flux:input wire:model="nombre" :label="__('Nombre')" type="text" required autocomplete="given-name" />
</div>
<div>
    <flux:input wire:model="apellido" :label="__('Apellido')" type="text" required autocomplete="family-name" />
</div>

        <div>
            <flux:input wire:model="phone" :label="__('Teléfono')" type="text" required autofocus autocomplete="name" />
            <div class="flex items-center mb-4">
                <input id="default-checkbox" wire:model="has_whatsapp" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 ">
                <label for="default-checkbox"  class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">¿Tiene WhatsApp?</label>
            </div>
        </div>

        <div>
            <flux:input wire:model="dni" :label="__('DNI')" type="text" required autocomplete="name" />
        </div>
        <br class="my-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                {{-- DNI Frente --}}
                <div>
                    <label>DNI Frente</label>
                    <input type="file" wire:model="dni_front" accept="image/*">
                    @if ($dni_front)
                        @if (is_string($dni_front))
                            <img src="{{ asset('storage/' . $dni_front) }}" alt="DNI Frente" class="h-32 mt-2 rounded shadow">
                        @else
                            <img src="{{ $dni_front->temporaryUrl() }}" alt="DNI Frente" class="h-32 mt-2 rounded shadow">
                        @endif
                    @endif
                </div>
            </div>
            <div class="flex-1">
                {{-- DNI Dorso --}}
                <div>
                    <label>DNI Dorso</label>
                    <input type="file" wire:model="dni_back" accept="image/*">
                    @if ($dni_back)
                        @if (is_string($dni_back))
                            <img src="{{ asset('storage/' . $dni_back) }}" alt="DNI Dorso" class="h-32 mt-2 rounded shadow">
                        @else
                            <img src="{{ $dni_back->temporaryUrl() }}" alt="DNI Dorso" class="h-32 mt-2 rounded shadow">
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <br class="my-4">
        <div>
            <flux:input wire:model="cuil_cuit" :label="__('CUIL / CUIT')" type="text" required autocomplete="name" />
        </div>

        <div>
            <label class="block text-sm font-medium">Situación Impositiva</label>
            <select
                wire:model="tax_status" required
                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white text-gray-900 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            >
            <option value=""  {{ empty($tax_status) ? 'selected' : 'disabled' }}>Seleccione una opción</option>
                <option value="monotributo">Monotributo</option>
                <option value="responsable inscripto">Responsable Inscripto</option>
                <option value="ninguna">Ninguna</option>
            </select>
        </div>

        
        <h3 class="text-lg font-semibold">Seleccione sus rubros</h3>



        
        <div class="space-y-3">
            @foreach ([
                'Electricidad' => ['Instalaciones eléctricas domiciliarias', 'Mantenimiento eléctrico', 'Tableros eléctricos', 'Instalación de luminarias', 'Cableado estructurado'],
                'Plomería' => ['Reparación de cañerías', 'Instalación de sanitarios', 'Instalación de bombas de agua', 'Detección y reparación de filtraciones'],
                'Albañilería' => ['Construcción de muros', 'Revoques (fino y grueso)', 'Colocación de cerámicos/pisos', 'Reparaciones estructurales', 'Hormigón / Encofrados'],
                'Herrería' => ['Soldadura (MIG, eléctrica)', 'Rejas y portones', 'Estructuras metálicas', 'Reparación de elementos metálicos'],
                'Carpintería' => ['Muebles a medida', 'Colocación de puertas y ventanas', 'Revestimientos en madera', 'Reparación de muebles'],
                'Otros' => ['Proximamente', 'Proximamente', 'Proximamente'],
            ] as $rubro => $subrubros)
                <div class="border p-3 rounded">
                    <h4 class="font-bold">{{ $rubro }}</h4>
                    @foreach ($subrubros as $subrubro)
                        <label class="block ml-4">
                            <input type="checkbox" wire:model="skills.{{ $rubro }}.{{ $subrubro }}" class="mr-2">
                            {{ $subrubro }}
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>
<br>

        {{-- <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Guardar
            </button>
        </div> --}}


        <div class="flex items-center gap-4">
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Guardar') }}
                </flux:button>
            </div>

            <x-action-message class="me-3" on="savedok">
                {{ __('Saved.') }}
            </x-action-message>
        </div>  
    </form>
</div>
