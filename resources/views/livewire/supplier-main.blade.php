<div>
    <div class="text-2xl mb-6">Proveedores</div>
    <div class="flex gap-3">
        <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search orders" />
        <flux:modal.trigger name="edit-profile">
            <flux:button wire:click="create()" variant="primary" icon="plus" class="cursor-pointer">Agregar</flux:button>
        </flux:modal.trigger>
    </div>
    @include('livewire.supplier-create')
    <div class="flex items-center justify-center">
        <table class="border-separate w-full border-spacing-y-2 text-sm">
            <thead class="bg-gray-500 text-gray-100">
                <tr>
                    <th class="th-class">ID</th>
                    <th class="th-class">Nombres y apellidos</th>
                    <th class="th-class">Documento</th>
                    <th class="th-class">Celular</th>
                    <th class="th-class">Correo</th>
                    <th class="th-class">Opciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($proveedores as $item)
            <tr>
                <td class="td-class">{{$item->id}}</td>
                <td class="td-class">{{$item->fullname}}</td>
                <td class="td-class">{{$item->document}}</td>
                <td class="td-class">{{$item->cellphone}}</td>
                <td class="td-class">{{$item->email}}</td>
                <td class="td-class">
                    <flux:button wire:click="edit({{$item}})" icon="pencil-square" size="xs" class="cursor-pointer"></flux:button>
                    <flux:button wire:click="$dispatch('deleteItem',{{$item->id}})"  variant="danger" icon="trash" size="xs" class="cursor-pointer"></flux:button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{$proveedores->links()}}
    </div>
<!--Scripts - Sweetalert   -->
      @push('js')
        <script>
          Livewire.on('deleteItem',id=>{
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                    //console.log(id);
                    //alert(id);
                    Livewire.dispatch('delItem',{item:id});
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )

                }
              })
          });
        </script>
      @endpush

</div>
