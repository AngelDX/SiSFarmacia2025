<?php

namespace App\Livewire;

use Livewire\Component;

class SupplierMain extends Component{
    public $isOpen=false;

    public function render(){
        return view('livewire.supplier-main');
    }

    public function create(){
        $this->isOpen=true;
    }
}
