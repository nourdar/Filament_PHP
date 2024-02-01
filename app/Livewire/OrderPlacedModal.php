<?php

namespace App\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class OrderPlacedModal extends ModalComponent
{

    public $success = false;

    public function render()
    {
        return view('livewire.order-placed-modal');
    }
}
