<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class=" bg-gray-300 p-5 rounded-2xl">
                <div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold uppercase text-gray-800">Attendance Record</h1>
                            <h1 class="text-gray-600 font-medium">{{ $subject->name }} - {{ $subject->schedule }}</h1>
                        </div>
                        <div class="flex space-x-3 items-center">
                            <x-filament::button outlined color="danger" icon="heroicon-m-arrow-uturn-left"
                                href="{{route('teacher.subject')}}" wire:navigate tag="a">
                                <span>Back</span>
                            </x-filament::button>
                            <x-filament::button @click="$dispatch('open-modal', { id: 'select-student' })"
                                icon="heroicon-m-arrow-right-end-on-rectangle" icon-position="after">
                                <span>Select Student</span>
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5  bg-gray-300 p-5 rounded-2xl">
                {{ $this->table }}
            </div>
        </div>
    </div>
    <x-filament::modal id="select-student" slide-over width="xl" sticky-footer :close-by-clicking-away="false" :close-button="false"
        sticky-header>
        <x-slot name="heading">
            <span>
                Select Students
            </span>

            <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass" class="w-full mt-5">
                <x-filament::input type="text" wire:model.live="search" />
            </x-filament::input.wrapper>
        </x-slot>

        <div>

            <div class="mt-5">
                <ul>

                    @forelse ($students as $item)
                        @php
                            $already_recorded = \App\Models\Attendance::where('teacher_subject_id', $this->subject->id)->where('student_id', $item->id)
                                ->whereDate('date', \Carbon\Carbon::now())
                                ->count();
                        @endphp
                        <li class="sdsdsd border-y flex space-x-3 items-center py-2">
                            <div>
                                <img src="{{ asset('images/student.png') }}" class="h-16 w-16 " alt="">
                            </div>

                            <div>
                                <h1 class="text-lg font-bold">{{ $item->firstname }} {{ $item->lastname }} </h1>
                                <div>
                                    <x-filament::button wire:click="absentStudent({{ $item->id }})" size="xs"
                                        color="danger" icon="heroicon-s-x-circle" :disabled="$already_recorded > 0"
                                        icon-position="after">
                                        Absent
                                    </x-filament::button>
                                    <x-filament::button wire:click="lateStudent({{ $item->id }})" size="xs"
                                        color="warning" icon="heroicon-s-arrow-uturn-right"  :disabled="$already_recorded > 0"
                                        icon-position="after">
                                        Late
                                    </x-filament::button>
                                    <x-filament::button wire:click="cuttingStudent({{ $item->id }})" size="xs"
                                        icon="heroicon-s-x-mark" icon-position="after" :disabled="$already_recorded > 0">
                                        Cutting
                                    </x-filament::button>
                                </div>
                            </div>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
        <x-slot name="footer">
            <x-filament::button @click="$dispatch('close-modal', { id: 'select-student' })">
                <span>Close</span>
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
