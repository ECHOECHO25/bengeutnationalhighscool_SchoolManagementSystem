<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gray-300 p-5 rounded-2xl">
                <div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold uppercase text-gray-800">Attendance Record</h1>
                            <h1 class="text-gray-600 font-medium">{{ $subject->name }} - {{ $subject->schedule }}</h1>
                        </div>
                        <div class="flex space-x-3 items-center">
                            <x-filament::button
                                outlined
                                color="danger"
                                icon="heroicon-m-arrow-uturn-left"
                                href="{{ route('teacher.subject') }}"
                                wire:navigate
                                tag="a">
                                <span>Back</span>
                            </x-filament::button>

                            <x-filament::button
                                wire:click="openManualModal"
                                color="warning"
                                icon="heroicon-o-pencil-square"
                                icon-position="after">
                                <span>Manual Entry</span>
                            </x-filament::button>

                            <x-filament::button
                                @click="$dispatch('open-modal', { id: 'select-student' })"
                                icon="heroicon-m-arrow-right-end-on-rectangle"
                                icon-position="after">
                                <span>Select Student</span>
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 bg-gray-300 p-5 rounded-2xl">
                {{ $this->table }}
            </div>
        </div>
    </div>

    <!-- Select Student Modal -->
    <x-filament::modal
        id="select-student"
        slide-over
        width="xl"
        sticky-footer
        :close-by-clicking-away="false"
        :close-button="false"
        sticky-header>
        <x-slot name="heading">
            <span>Select Students</span>

            <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass" class="w-full mt-5">
                <x-filament::input type="text" wire:model.live="search" />
            </x-filament::input.wrapper>
        </x-slot>

        <div>
            <div class="mt-5">
                <ul>
                    @forelse ($students as $item)
                        @php
                            $already_recorded = \App\Models\Attendance::where('teacher_subject_id', $this->subject->id)
                                ->where('student_id', $item->id)
                                ->whereDate('date', \Carbon\Carbon::now())
                                ->count();
                        @endphp
                        <li class="border-y flex space-x-3 items-center py-2">
                            <div>
                                <img src="{{ asset('images/student.png') }}" class="h-16 w-16" alt="">
                            </div>

                            <div>
                                <h1 class="text-lg font-bold">{{ $item->firstname }} {{ $item->lastname }}</h1>
                                <div>
                                    <x-filament::button
                                        wire:click="absentStudent({{ $item->id }})"
                                        size="xs"
                                        color="danger"
                                        icon="heroicon-s-x-circle"
                                        :disabled="$already_recorded > 0"
                                        icon-position="after">
                                        Absent
                                    </x-filament::button>
                                    <x-filament::button
                                        wire:click="lateStudent({{ $item->id }})"
                                        size="xs"
                                        color="warning"
                                        icon="heroicon-s-arrow-uturn-right"
                                        :disabled="$already_recorded > 0"
                                        icon-position="after">
                                        Late
                                    </x-filament::button>
                                    <x-filament::button
                                        wire:click="cuttingStudent({{ $item->id }})"
                                        size="xs"
                                        icon="heroicon-s-x-mark"
                                        icon-position="after"
                                        :disabled="$already_recorded > 0">
                                        Cutting
                                    </x-filament::button>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-8 text-gray-500">
                            No students found
                        </li>
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

    <!-- Manual Attendance Entry Modal -->
    <x-filament::modal
        id="manual-attendance"
        width="lg"
        sticky-footer
        :close-by-clicking-away="false">
        <x-slot name="heading">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Manual Attendance Entry</span>
            </div>
        </x-slot>

        <div class="space-y-4">
            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Use this form to add attendance records for past dates or to correct missed entries.
                </p>
            </div>

            <!-- Student Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Student <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="manualStudentId"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Select Student --</option>
                    @foreach($allStudents as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->lastname }}, {{ $student->firstname }} ({{ $student->lrn }})
                        </option>
                    @endforeach
                </select>
                @error('manualStudentId')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Date <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    wire:model="manualDate"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                @error('manualDate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Time Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Time In <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    wire:model="manualTimeIn"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                @error('manualTimeIn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none
                                  {{ $manualStatus === 'Absent' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                        <input
                            type="radio"
                            wire:model="manualStatus"
                            value="Absent"
                            class="sr-only"
                        />
                        <span class="flex flex-1 flex-col items-center">
                            <span class="block text-sm font-medium {{ $manualStatus === 'Absent' ? 'text-red-900' : 'text-gray-900' }}">
                                Absent
                            </span>
                        </span>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none
                                  {{ $manualStatus === 'Late' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                        <input
                            type="radio"
                            wire:model="manualStatus"
                            value="Late"
                            class="sr-only"
                        />
                        <span class="flex flex-1 flex-col items-center">
                            <span class="block text-sm font-medium {{ $manualStatus === 'Late' ? 'text-yellow-900' : 'text-gray-900' }}">
                                Late
                            </span>
                        </span>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none
                                  {{ $manualStatus === 'Cutting' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                        <input
                            type="radio"
                            wire:model="manualStatus"
                            value="Cutting"
                            class="sr-only"
                        />
                        <span class="flex flex-1 flex-col items-center">
                            <span class="block text-sm font-medium {{ $manualStatus === 'Cutting' ? 'text-blue-900' : 'text-gray-900' }}">
                                Cutting
                            </span>
                        </span>
                    </label>
                </div>
                @error('manualStatus')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <x-slot name="footerActions">
            <x-filament::button
                wire:click="saveManualAttendance"
                wire:loading.attr="disabled"
                color="success"
                icon="heroicon-o-check-circle">
                <span wire:loading.remove>Save Attendance</span>
                <span wire:loading>Saving...</span>
            </x-filament::button>

            <x-filament::button
                @click="$dispatch('close-modal', { id: 'manual-attendance' })"
                color="gray"
                outlined>
                Cancel
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
