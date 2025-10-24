<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Operarios')" :subheading=" __('Actualizá la configuración como operario')">
        <livewire:worker-form />

    </x-settings.layout>
</section>
