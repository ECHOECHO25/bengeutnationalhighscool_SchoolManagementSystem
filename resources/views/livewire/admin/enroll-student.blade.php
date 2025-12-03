<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gray-300 p-5 rounded-2xl">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-xl font-bold text-gray-700">ENLIST STUDENTS</h1>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">
                            💡 Select multiple students for batch enrollment
                        </span>
                    </div>
                </div>
                <div class="mt-3">
                    {{ $this->table }}
                </div>
            </div>
        </div>
    </div>

    <!-- Single Enrollment Modal -->
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
                        <x-filament::input.wrapper :valid="! $errors->has('track')">
                            <x-filament::input.select wire:model.live="track" :valid="! $errors->has('track')">
                                <option>Select An Option</option>
                                <option value="Academic">Academic</option>
                                <option value="Technical-Vocational-Livelihood(TVL)">Technical-Vocational-Livelihood(TVL)</option>
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

    <!-- Batch Enrollment Modal -->
    <x-filament::modal id="batch-enroll-modal" width="3xl" :close-by-escaping="false" :close-by-clicking-away="false" :autofocus="false">
        <x-slot name="heading">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="uppercase">Batch Enrollment</span>
            </div>
        </x-slot>

        <div>
            <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">Selected Students: {{ count($selectedStudents) }}</h3>
                <div class="max-h-60 overflow-y-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-100 sticky top-0">
                            <tr>
                                <th class="px-2 py-1 text-left text-xs font-semibold text-blue-900">Name</th>
                                <th class="px-2 py-1 text-left text-xs font-semibold text-blue-900">LRN</th>
                                <th class="px-2 py-1 text-left text-xs font-semibold text-blue-900">Previous Grade | Classroom</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedStudents as $student)
                                @php
                                    $prevYear = \App\Models\SchoolYear::where('is_active', 0)->orderBy('id', 'desc')->first();
                                    $lastInfo = 'New Student';

                                    if ($prevYear) {
                                        $lastStudent = \App\Models\Student::where('lrn', $student['lrn'])
                                            ->where('school_year_id', $prevYear->id)
                                            ->with('gradeLevel')
                                            ->first();

                                        if ($lastStudent) {
                                            $classroom = \App\Models\StudentClassroom::where('student_id', $lastStudent->id)
                                                ->with('classroom')
                                                ->first();

                                            $gradeLevel = $lastStudent->gradeLevel ? $lastStudent->gradeLevel->name : 'Unknown';
                                            $classroomInfo = $classroom ? $classroom->classroom->section . ' - ' . $classroom->classroom->building_number : 'N/A';
                                            $lastInfo = $gradeLevel . ' | ' . $classroomInfo;
                                        }
                                    }
                                @endphp
                                <tr class="border-t border-blue-200 hover:bg-blue-100">
                                    <td class="px-2 py-1 text-blue-800">{{ $student['lastname'] }}, {{ $student['firstname'] }}</td>
                                    <td class="px-2 py-1 text-blue-700">{{ $student['lrn'] }}</td>
                                    <td class="px-2 py-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $lastInfo === 'New Student' ? 'bg-green-100 text-green-800' : 'bg-blue-200 text-blue-900' }}">
                                            {{ $lastInfo }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Grade Level <span class="text-red-500">*</span></label>
                        <x-filament::input.wrapper :valid="! $errors->has('batchGradeLevelId')">
                            <x-filament::input.select wire:model.live="batchGradeLevelId">
                                <option value="">Select An Option</option>
                                @forelse ($grade_levels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @empty
                                @endforelse
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label for="" class="text-sm font-medium text-gray-700">Classroom <span class="text-red-500">*</span></label>
                        <x-filament::input.wrapper :valid="! $errors->has('batchClassroomId')">
                            <x-filament::input.select wire:model="batchClassroomId">
                                <option value="">Select An Option</option>
                                @forelse ($batch_classrooms as $item)
                                    <option value="{{ $item->id }}">{{ $item->building_number. ' - '. $item->section }}</option>
                                @empty
                                @endforelse
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </div>

                <x-filament::fieldset :hidden="$batchDepartment != 'Senior High School'">
                    <x-slot name="label">SENIOR HIGH SCHOOL INFORMATION</x-slot>
                    <div class="grid grid-cols-3 gap-5">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Semester <span class="text-red-500">*</span></label>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model="batchSemester">
                                    <option value="">Select</option>
                                    <option value="First Semester">First Semester</option>
                                    <option value="Second Semester">Second Semester</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Track <span class="text-red-500">*</span></label>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model="batchTrack">
                                    <option value="">Select</option>
                                    <option value="Academic">Academic</option>
                                    <option value="Technical-Vocational-Livelihood(TVL)">TVL</option>
                                    <option value="Sport">Sport</option>
                                    <option value="Arts and Design">Arts and Design</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Strand <span class="text-red-500">*</span></label>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model="batchStrand">
                                    <option value="">Select</option>
                                    <option value="STEM">STEM</option>
                                    <option value="ABM">ABM</option>
                                    <option value="HUMSS">HUMSS</option>
                                    <option value="GAS">GAS</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                    </div>
                </x-filament::fieldset>
            </div>
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="batchEnrollNow" wire:loading.attr="disabled" color="success">
                <span wire:loading.remove>Enroll All</span>
                <span wire:loading>Enrolling...</span>
            </x-filament::button>
            <x-filament::button color="danger" outlined x-on:click="$dispatch('close-modal', { id: 'batch-enroll-modal' })">Cancel</x-filament::button>
        </x-slot>
    </x-filament::modal>

    <!-- Grouped Enrollment Modal -->
    <x-filament::modal id="grouped-enroll-modal" width="7xl" :close-by-escaping="false" :close-by-clicking-away="false">
        <x-slot name="heading">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="uppercase">Batch Enroll by Last Year's Classroom</span>
            </div>
        </x-slot>

        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm text-blue-800">
                    <strong>📋 Instructions:</strong> Students are grouped by their previous classroom.
                    Assign grade level and new classroom for each group, then enroll all at once!
                </p>
            </div>

            @foreach($groupedStudents as $groupIndex => $group)
                @php
                    // Get grade level from first student in group
                    $firstStudent = $group['students'][0] ?? null;
                    $prevGradeInfo = '';

                    if ($firstStudent) {
                        $prevYear = \App\Models\SchoolYear::where('is_active', 0)->orderBy('id', 'desc')->first();
                        if ($prevYear) {
                            $lastStudent = \App\Models\Student::where('lrn', $firstStudent['lrn'])
                                ->where('school_year_id', $prevYear->id)
                                ->with('gradeLevel')
                                ->first();

                            if ($lastStudent && $lastStudent->gradeLevel) {
                                $prevGradeInfo = ' (From ' . $lastStudent->gradeLevel->name . ')';
                            }
                        }
                    }
                @endphp

                <x-filament::fieldset>
                    <x-slot name="label">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <span class="font-bold text-lg">{{ $group['classroom_name'] }}</span>
                                <span class="text-sm text-gray-600 font-normal">{{ $prevGradeInfo }}</span>
                            </div>
                            <span class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                {{ count($group['students']) }} students
                            </span>
                        </div>
                    </x-slot>

                    <div class="space-y-3">
                        <!-- Student List -->
                        <div class="bg-gray-50 p-3 rounded max-h-32 overflow-y-auto">
                            <div class="grid grid-cols-3 gap-2 text-sm">
                                @foreach($group['students'] as $student)
                                    <div class="flex items-center space-x-1">
                                        <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                        <span>{{ $student['lastname'] }}, {{ $student['firstname'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Assignment Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">New Grade Level <span class="text-red-500">*</span></label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select wire:model.live="groupedStudents.{{ $groupIndex }}.grade_level_id">
                                        <option value="">Select Grade Level</option>
                                        @foreach($grade_levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">New Classroom <span class="text-red-500">*</span></label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select wire:model="groupedStudents.{{ $groupIndex }}.classroom_id_new">
                                        <option value="">Select Classroom</option>
                                        @if(!empty($group['grade_level_id']))
                                            @foreach(\App\Models\Classroom::where('grade_level_id', $group['grade_level_id'])->get() as $classroom)
                                                <option value="{{ $classroom->id }}">{{ $classroom->building_number }} - {{ $classroom->section }}</option>
                                            @endforeach
                                        @endif
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </div>
                </x-filament::fieldset>
            @endforeach
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="enrollGroupedStudents" wire:loading.attr="disabled" color="success" icon="heroicon-o-user-group">
                <span wire:loading.remove>Enroll All Groups</span>
                <span wire:loading>Enrolling...</span>
            </x-filament::button>
            <x-filament::button color="danger" outlined x-on:click="$dispatch('close-modal', { id: 'grouped-enroll-modal' })">Cancel</x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
