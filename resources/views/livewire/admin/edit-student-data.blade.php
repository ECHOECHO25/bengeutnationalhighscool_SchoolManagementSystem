<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto bg-gray-300 p-5 rounded-2xl ">
            <div>
                {{ $this->form }}
            </div>
            <div class="mt-5">
                <x-filament::button wire:click="updateForm">
                   Update
                </x-filament::button>
            </div>
        </div>
    </div>
</div>
