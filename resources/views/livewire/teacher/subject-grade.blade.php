<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class=" bg-gray-300 p-5 rounded-2xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold uppercase text-gray-800">Grading Record</h1>
                        <h1 class="text-gray-600 font-medium">{{ $subject->name }} - {{ $subject->schedule }}</h1>
                    </div>
                    <div class="flex space-x-3 items-center">
                        <x-filament::button outlined color="danger" icon="heroicon-m-arrow-uturn-left"
                            href="{{ route('teacher.subject') }}" wire:navigate tag="a">
                            <span>Back</span>
                        </x-filament::button>
                        <x-filament::button @click="$dispatch('open-modal', { id: 'upload-grade' })"
                            icon="heroicon-m-plus-circle" icon-position="after">
                            <span>Create Grade</span>
                        </x-filament::button>
                    </div>
                </div>
            </div>
            <div class="mt-5  bg-gray-300 p-5 rounded-2xl">
                {{ $this->table }}
            </div>

            <x-filament::modal id="upload-grade" width="xl" wire:ignore.self>
                <x-slot name="heading">
                    Create Grade
                </x-slot>

                <div class="space-y-4">
                    {{-- Name Input --}}
                    <div>
                        <label class="text-sm">Name</label>
                        <x-filament::input.wrapper class="mt-1">
                            <x-filament::input type="text" wire:model.live="name" />
                        </x-filament::input.wrapper>
                    </div>
                    <div>
                        <label class="text-sm">Exam</label>
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="exam_id">
                                <option >Select an option</option>
                                @foreach ($exams as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                               
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                </div>

                <x-slot name="footer">
                    <x-filament::button icon="heroicon-m-arrow-up-tray" icon-position="after" wire:click="uploadGrade">
                        Create Now
                    </x-filament::button>

                    <x-filament::button outlined color="danger"
                        @click="$dispatch('close-modal', { id: 'upload-grade' })">
                        Close
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>

        </div>
    </div>
