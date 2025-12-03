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

            <div class="mt-8 bg-white p-5 rounded-2xl shadow-lg">
    <h2 class="text-xl font-bold mb-4">Student Grade Sheet</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <!-- Student column -->
                    <th class="px-3 py-2 border">Student</th>

                    <!-- Exam columns -->
                    @foreach ($this->getExams() as $exam)
                        <th class="px-3 py-2 border text-center">
                            {{ $exam->name }}
                        </th>
                    @endforeach

                    <!-- Average -->
                    <th class="px-3 py-2 border text-center bg-gray-100">
                        Average
                    </th>
                </tr>
            </thead>

            <tbody>

            @foreach ($this->getStudents() as $student)
                <tr class="hover:bg-gray-50">

                    <!-- Student name -->
                    <td class="px-3 py-2 border font-medium">
                        {{ $student->lastname }}, {{ $student->firstname }}
                    </td>

                    <!-- Grades per exam -->
                    @foreach ($this->getExams() as $exam)
                        <td class="px-3 py-2 border text-center">
                            {{ $this->getStudentGrade($student->id, $exam->id) ?? '-' }}
                        </td>
                    @endforeach

                    <!-- Student Average -->
                    <td class="px-3 py-2 border text-center bg-gray-50 font-bold
                        text-blue-700">
                        {{ $this->getStudentAverage($student->id) ?? 'N/A' }}
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>


        </div>
    </div>
