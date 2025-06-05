<div>
    <div class="text-2xl mb-6">Proveedores</div>
    <div class="flex gap-3">
        <flux:input icon="magnifying-glass" placeholder="Search orders" />
        <flux:button wire:click="create()" variant="primary" icon="plus">Primary</flux:button>
    </div>
    @include('livewire.supplier-create')
    <div class="flex items-center justify-center">
        <table class="border-separate w-full border-spacing-y-2 text-sm">
            <thead class="bg-gray-500 text-gray-100">
                <tr>
                    <th class="th-class">Name</th>
                    <th class="th-class">Email</th>
                    <th class="th-class">Company</th>
                    <th class="th-class">Status</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td class="td-class">Frodo Baggins</td>
                <td class="td-class">fbaggins@mail.com</td>
                <td class="td-class">Fellowship of the Ring</td>
                <td class="td-class">
                <span class="float-right rounded-md bg-green-600/50 px-4 py-px text-xs font-semibold uppercase text-green-900 antialiased">Active</span>
                </td>
            </tr>

            <tr>
                <td class="td-class">Peregrin Took</td>
                <td class="td-class">pippin@mail.com</td>
                <td class="td-class">Fellowship of the Ring</td>
                <td class="td-class">
                <span class="float-right rounded-md bg-green-600/50 px-4 py-px text-xs font-semibold uppercase text-green-900 antialiased">Active</span>
                </td>
            </tr>

            <tr>
                <td class="td-class">Bilbo Baggins</td>
                <td class="td-class">bbaggins@mail.com</td>
                <td class="td-class">Thorin’s Company</td>
                <td class="td-class">
                <span class="float-right rounded-md bg-yellow-600/50 px-4 py-px text-xs font-semibold uppercase text-yellow-900 antialiased">Pending</span>
                </td>
            </tr>
            <tr>
                <td class="td-class suspended-text">Boromir of Gondor</td>
                <td class="td-class suspended-text">boromir@mail.com</td>
                <td class="td-class suspended-text">Fellowship of the Ring</td>
                <td class="td-class">
                <span class="float-right rounded-md bg-red-600/50 px-4 py-px text-xs font-semibold uppercase text-red-100 antialiased">Suspended</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
