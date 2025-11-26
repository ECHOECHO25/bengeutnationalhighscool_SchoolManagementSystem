<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto bg-gray-300 p-5 rounded-2xl">
            <div>
                {{$this->table}}
            </div>
        </div>
    </div>
       <x-filament::modal id="import-classroom" width="xl">
        <x-slot name="heading">
            <h1 class="font-semibold text-gray-700 text-xl">Import Classroom Records</h1>
        </x-slot>

        <div>
            <div class="border-l-4 border p-2 border-main rounded-xl">
                <h1 class="font-semibold uppercase text-sm text-gray-700">File Format Requirements:</h1>
                <p class="text-gray-500 text-sm">Required columns: building_number, grade_level, section</p>
                <p class="text-gray-500 text-sm">Optional columns: homeroom_teacher, capacity, status</p>
                <p class="text-gray-500 text-sm">Email must be unique</p>
                <p class="text-gray-500 text-sm">Max file size: 2MB</p>
            </div>
            <div class="mt-5">
                <label for="" class="text-sm ">Excel File</label>
                <x-filament::input.wrapper class="mt-2">
                    <x-filament::input type="file" wire:model.live="file" />
                </x-filament::input.wrapper>
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-700 mt-1">(.xlsx, .xls, .csv)</p>
                    <p wire:loading wire:target="file" class="text-sm animate-pulse text-gray-600">Uploading
                        Attachment...</p>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <x-filament::button wire:click="importClassroom" color="primary">
                Import Now
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
