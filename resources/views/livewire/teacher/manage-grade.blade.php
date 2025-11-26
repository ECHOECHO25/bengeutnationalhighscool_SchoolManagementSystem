<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class=" bg-white p-5 rounded-2xl">
                <h1 class="font-semibold text-2xl text-gray-700">{{ $grade->name }}</h1>

                <x-filament::fieldset class="mt-5">
                    <x-slot name="label">
                        <span class="uppercase">
                            {{ $grade->teacherSubject->classroom->section . ' - ' . $grade->teacherSubject->classroom->building_number }}</span>
                    </x-slot>

                    <div class="w-full grid grid-cols-4 gap-5">
                        @forelse ($students as $item)
                            <div class="border-2 text-center p-5 rounded-2xl">
                                <h1 class="font-bold text-gray-700">{{ $item->full_name }}</h1>
                                <div class="mt-3">

                                    @php
                                        $existingGrade = \App\Models\StudentGrade::where('student_id', $item->id)
                                            ->where('subject_grade_id', $grade->id)
                                            ->first();
                                    @endphp
                                    @if ($existingGrade)
                                        <div class="flex ">
                                            <div class="text-xl border flex-1  font-bold text-green-600">
                                                {{ $existingGrade->grade }}
                                            </div>
                                            <button wire:click="deleteGrade({{ $existingGrade->id }})" class="text-red-600 hover:bg-red-100 rounded-r-lg border px-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-eraser-icon lucide-eraser">
                                                    <path
                                                        d="M21 21H8a2 2 0 0 1-1.42-.587l-3.994-3.999a2 2 0 0 1 0-2.828l10-10a2 2 0 0 1 2.829 0l5.999 6a2 2 0 0 1 0 2.828L12.834 21" />
                                                    <path d="m5.082 11.09 8.828 8.828" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <x-filament::input.wrapper>
                                            <x-filament::input type="number"
                                                wire:model.defer="grades.{{ $item->id }}.grade"
                                                wire:input="setStudentId({{ $item->id }})" class="text-center"
                                                placeholder="Enter Grade" />
                                        </x-filament::input.wrapper>
                                        <input type="hidden" wire:model.defer="grades.{{ $item->id }}.student_id"
                                            value="{{ $item->id }}">
                                    @endif



                                    {{-- Hidden field to store student_id --}}

                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </x-filament::fieldset>
                <div class="mt-5 flex space-x-3 items-center">
                    <x-filament::button wire:click="submitGrade">
                        Submit Grade
                    </x-filament::button>
                    <x-filament::button
                        href="{{ route('teacher.subject.grade', ['id' => encrypt($grade->teacher_subject_id)]) }}"
                        tag="a" color="danger" outlined>
                        Close
                    </x-filament::button>
                </div>
            </div>

        </div>
    </div>
</div>
