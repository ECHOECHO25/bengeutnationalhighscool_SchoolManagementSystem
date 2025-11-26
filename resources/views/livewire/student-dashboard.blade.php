<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-5 gap-5">
                <div class="bg-white p-5 col-span-3 flex space-x-4 items-center rounded-2xl">
                    <img src="{{ asset('images/student.png') }}" class="h-20 w-20" alt="">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-600 uppercase">
                            {{ auth()->user()->student->lastname . ', ' . auth()->user()->student->firstname . ' ' . (auth()->user()->student->middlename ? auth()->user()->student->middlename[0] . '.' : '') }}
                        </h1>
                        <div class="flex space-x-2 item-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 text-red-500" width="20"
                                height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-map-pin-house-icon lucide-map-pin-house">
                                <path
                                    d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z" />
                                <path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" />
                                <path d="M18 22v-3" />
                                <circle cx="10" cy="10" r="3" />
                            </svg>
                            <p class="text-sm font-medium text-gray-500">
                                {{ auth()->user()->student->building . ', ' . auth()->user()->student->street . ', ' . auth()->user()->student->barangay . ', ' . auth()->user()->student->municipality . ', ' . auth()->user()->student->province }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white col-span-2 p-5 rounded-2xl flex item-center ">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-600">Welcome Back,</h1>
                        <div class="mt-2">
                            <h1 class="text-lg font-medium text-gray-600">
                                {{ auth()->user()->student->gradeLevel->name . ' - ' . auth()->user()->student->gradeLevel->department }}
                            </h1>
                            <div class="text-lg font-medium text-gray-600">
                                {{ auth()->user()->student->studentClassrooms->first()->classroom->building_number . ' - ' . auth()->user()->student->studentClassrooms->first()->classroom->section }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-shared.student class="h-28 w-28 mx-auto" />
                    </div>
                </div>
            </div>
            <div class="mt-10">
                <h1 class="font-semibold text-xl text-white uppercase">ALL Subjects</h1>
                <div class="mt-3 bg-white p-5 rounded-2xl">
                    {{$this->table}}
                </div>
            </div>
            <div class="mt-10">
                <h1 class="font-semibold text-xl text-white uppercase">ATTENDANCE RECORD</h1>
                <div class="mt-3 bg-white p-5 rounded-2xl">
                    <livewire:student.my-attendance/>
                </div>
            </div>
        </div>
    </div>
</div>
