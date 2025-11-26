<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto ">
            <div class="bg-gray-300 p-5 rounded-2xl">
                <h1 class="text-xl font-bold text-gray-700">ENLIST STUDENTS</h1>
                <div class="mt-3">
                    {{ $this->table }}
                </div>
            </div>
        </div>
    </div>

    <x-filament::modal id="enroll-student" width="2xl" :close-by-escaping="false" :close-by-clicking-away="false" :autofocus="false">
        <x-slot name="heading">
            <span class="uppercase">Enroll Now</span>
        </x-slot>
        <div>
            <div class="border rounded-2xl px-5 py-2 flex items-center space-x-3">
                <img src="{{ asset('images/student.png') }}" class="h-20" alt="">
                <div>
                    <h1 class="text-lg font-medium">{{ $student->full_name ?? '' }}</h1>
                    <h1 class="text-gray-600 font-medium"> {{ $student->gradeLevel->name ?? '' }} -
                        {{ $student->gradeLevel->department ?? '' }}</h1>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-5">
                <div>
                    <label for="" class="text-sm font-medium text-gray-700">Grade Level</label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="grade_level_id">
                            <option>Select An Option</option>
                            @forelse ($grade_levels as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @empty
                            @endforelse

                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </div>
            <x-filament::fieldset :hidden="$department != 'Senior High School'" class="mt-5">
                <x-slot name="label">
                    SENIOR HIGH SCHOOL INFORMATION
                </x-slot>

                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Semester</label>
                        <x-filament::input.wrapper :valid="! $errors->has('semester')">
                            <x-filament::input.select wire:model.live="semester" :valid="! $errors->has('semester')">
                                <option>Select An Option</option>
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Track</label>
                        <x-filament::input.wrapper  :valid="! $errors->has('track')">
                            <x-filament::input.select wire:model.live="track" :valid="! $errors->has('track')">
                                <option>Select An Option</option>
                                <option value="Academic">Academic</option>
                                <option value="Technical-Vocational-Livelihood(TVL)">
                                    Technical-Vocational-Livelihood(TVL)</option>
                                <option value="Sport">Sport</option>
                                <option value="Arts and Design">Arts and Design</option>

                            </x-filament::input.select>
                            
                        </x-filament::input.wrapper>
                    </div>
                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Strand</label>
                        <x-filament::input.wrapper :valid="! $errors->has('strand')">
                            <x-filament::input.select wire:model.live="strand" :valid="! $errors->has('strand')">
                                <option>Select An Option</option>
                                <option value="STEM">STEM</option>
                                <option value="ABM">ABM</option>
                                <option value="HUMSS">HUMSS</option>
                                <option value="GAS">GAS</option>

                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </div>
            </x-filament::fieldset>
            <x-filament::fieldset class="mt-5">
                <x-slot name="label">
                    CLASSROOM
                </x-slot>

                <div>
                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Classroom</label>
                        <x-filament::input.wrapper :valid="! $errors->has('classroom_id')">
                            <x-filament::input.select wire:model="classroom_id" :valid="! $errors->has('classroom_id')">
                                <option>Select An Option</option>
                                @forelse ($classrooms as $item)
                                    <option value="{{ $item->id }}">{{ $item->building_number. ' - '. $item->section }}</option>
                                @empty
                                @endforelse

                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                </div>
            </x-filament::fieldset>

        </div>
        <x-slot name="footerActions">
            <x-filament::button wire:click="enrollNow">
                Enroll Now
            </x-filament::button>
            <x-filament::button color="danger" outlined x-on:click="$dispatch('close-modal', { id: 'enroll-student' })">
               Close
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
