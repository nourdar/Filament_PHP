<?php

namespace App\Livewire;

use App\Models\Settings;
use LivewireUI\Modal\ModalComponent;

class CallModal extends ModalComponent
{
    public $phones;

    public function mount()
    {
        $settings = Settings::first();

        $this->phones = collect($settings?->telephone);
    }
    public function render()
    {
        return view('livewire.call-modal');
    }
}
