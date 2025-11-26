<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto bg-gray-300 p-5 rounded-2xl ">

            <div>
                @if (!$level_name)
                    <div class="grid place-content-center gap-5">
                        <h1 class="text-3xl font-bold text-gray-700 text-center">Select School Level</h1>
                        <div class="space-y-6 py-10">
                            @foreach ($school_levels as $item)
                                <button wire:click="$set('level_name', '{{ $item['name'] }}')"
                                    class="flex items-center justify-center px-20 text-xl h-20 bg-blue-700 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                    {{ $item['name'] . ' (' . $item['description'] . ')' }}
                                </button>
                            @endforeach
                                <a href="{{ auth()->user()->role == 'admin' ? route('admin.enroll-student') : route('encoder.enroll-student') }}" 
                                    class="flex w-full space-x-4 uppercase font-bold items-center justify-center px-20 text-xl py-5 bg-gray-700 text-white rounded-3xl hover:bg-blue-800 transition-colors duration-200">
                                   <span>Enrollment</span>
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-check-icon lucide-file-check"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/></svg>
                                </a>
                        </div>
                    </div>
                @else
                    <div class="flex justify-between items-center mb-5">
                        <h1 class="text-xl font-bold text-gray-700">Enlist Form - {{ $level_name }}</h1>
                        <button wire:click="$set('level_name', null)"
                            class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-800 transition-colors duration-200">
                            Back
                        </button>
                    </div>

                    <div>
                        {{ $this->form }}
                    </div>
                    <div class="mt-5">
                        <x-filament::button size="xl" wire:click="enroll">
                           Enlist Student
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
