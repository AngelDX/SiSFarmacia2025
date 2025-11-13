<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ClientForm extends Form
{
    #[Validate('required|min:5')]
    public $fullname,$documento,$type;
    public $address,$email;
}
