<?php

namespace App\Livewire;

use App\Livewire\Forms\ClientForm;
use App\Models\Client;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ClientMain extends Component
{
    use WithPagination;
    public $isOpen=false;
    public $search;
    public ?Client $supplier;
    public ClientForm $form;

    public function render(){
        $clientes=Client::where('fullname','LIKE','%'.$this->search.'%')->latest("id")->paginate();
        return view('livewire.client-main',compact('clientes'));
    }

    public function create(){
        $this->form->reset();
        $this->isOpen=true;
    }

    public function store(){
        $this->validate();
        if (!isset($this->supplier->id)) {
            Client::create($this->form->all());
        }else{
            $this->supplier->update($this->form->all());
        }
       $this->reset();
    }

    public function edit(Client $item){
        $this->isOpen=true;
        $this->supplier=$item;
        $this->form->fill($item);
    }

    #[On('delItem')]
    public function delete(Client $item){
        $item->delete();
    }

    public function updatingSearch(){
        $this->resetPage();
    }
}
