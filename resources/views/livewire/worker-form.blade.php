<div class="max-w-3xl mx-auto p-6 rounded-2xl shadow">
    <form wire:submit.prevent="save" class="space-y-4">

        <div>
            {{-- <label class="block font-semibold">Teléfono:</label>
            <input type="text" wire:model="telefono" class="w-full border rounded p-2"> --}}
            <flux:input wire:model="telefono" :label="__('Teléfono')" type="text" required autofocus autocomplete="name" />


            <div class="flex items-center mb-4">
                <input id="default-checkbox" wire:model="tiene_whatsapp" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 ">
                <label for="default-checkbox"  class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">¿Tiene WhatsApp?</label>
            </div>

            
            {{-- <label class="flex items-center mt-1">
                <input type="checkbox" wire:model="tiene_whatsapp" class="mr-2">
                ¿Tiene WhatsApp?
            </label> --}}
        </div>

        <div>
            {{-- <label class="block font-semibold">DNI:</label>
            <input type="text" wire:model="dni" class="w-full border rounded p-2"> --}}
            
            <flux:input wire:model="dni" :label="__('DNI')" type="text" required autocomplete="name" />

            {{-- <label class="flex items-center mt-1">
                <input type="file" wire:model="foto" class="mr-2">
                ¿Adjunta foto?
            </label> --}}
        </div>

        {{-- <hr class="my-4"> --}}












        
        <h3 class="text-lg font-semibold">Seleccione sus rubros</h3>









        
        <div class="space-y-3">
            @foreach ([
                'Electricidad' => ['Instalaciones eléctricas domiciliarias', 'Mantenimiento eléctrico', 'Tableros eléctricos', 'Instalación de luminarias', 'Cableado estructurado'],
                'Plomería' => ['Reparación de cañerías', 'Instalación de sanitarios', 'Instalación de bombas de agua', 'Detección y reparación de filtraciones'],
                'Albañilería' => ['Construcción de muros', 'Revoques (fino y grueso)', 'Colocación de cerámicos/pisos', 'Reparaciones estructurales', 'Hormigón / Encofrados'],
                'Herrería' => ['Soldadura (MIG, eléctrica)', 'Rejas y portones', 'Estructuras metálicas', 'Reparación de elementos metálicos'],
                'Carpintería' => ['Muebles a medida', 'Colocación de puertas y ventanas', 'Revestimientos en madera', 'Reparación de muebles']
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
