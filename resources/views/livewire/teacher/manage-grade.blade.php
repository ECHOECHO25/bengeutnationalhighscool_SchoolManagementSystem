<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white p-5 rounded-2xl shadow-lg">
                <!-- Header -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h1 class="font-semibold text-2xl text-gray-700">{{ $grade->name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $grade->teacherSubject->classroom->section }} - {{ $grade->teacherSubject->classroom->building_number }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($hasChanges)
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                                Unsaved Changes
                            </span>
                        @endif
                        <span class="text-sm text-gray-600">
                            {{ count($students) }} Students
                        </span>
                    </div>
                </div>




                <!-- Excel-Style Grade Table -->
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    LRN
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    Grade
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($students as $index => $student)
                                <tr class="hover:bg-gray-50 transition-colors"
                                    wire:key="student-{{ $student->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student->full_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->lrn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <input
                                                type="number"
                                                wire:model.defer="grades.{{ $student->id }}.grade"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg font-semibold
                                                       @if(!empty($grades[$student->id]['existing_id'])) bg-green-50 border-green-300 @endif"
                                                placeholder="0.00"
                                                onkeydown="if(event.key === 'Enter') { event.target.blur(); }"
                                            />
                                            @if(!empty($grades[$student->id]['existing_id']))
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    Saved
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if(!empty($grades[$student->id]['existing_id']))
                                            <button
                                                wire:click="deleteGrade({{ $student->id }})"
                                                wire:confirm="Are you sure you want to delete this grade?"
                                                class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded transition-colors"
                                                title="Delete Grade">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No students found in this classroom
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 flex items-center space-x-3">
    <input type="file" wire:model="importFile" accept=".xlsx,.csv" class="border p-2 rounded">
    <x-filament::button
        wire:click="importGrades"
        icon="heroicon-o-document-arrow-up"
        color="info">
        Import Grades
    </x-filament::button>

    @if ($importFile)
        <span class="text-sm text-gray-600">File selected: {{ $importFile->getClientOriginalName() }}</span>
    @endif
</div>

@error('importFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <!-- Action Buttons -->
                <div class="mt-5 flex items-center justify-between">
                    <div class="flex space-x-3">
                        <x-filament::button
                            wire:click="submitGrade"
                            wire:loading.attr="disabled"
                            icon="heroicon-o-check-circle"
                            size="lg">
                            <span wire:loading.remove>Save All Grades</span>
                            <span wire:loading>Saving...</span>
                        </x-filament::button>

                        <x-filament::button
                            wire:click="clearAll"
                            wire:confirm="Are you sure you want to clear all grades? This cannot be undone."
                            color="danger"
                            outlined
                            icon="heroicon-o-trash">
                            Clear All
                        </x-filament::button>

                        <x-filament::button
    wire:click="downloadTemplate"
    icon="heroicon-o-arrow-down-tray"
    color="success"
    size="lg"
>
    Download Excel Template
</x-filament::button>

                    </div>

                    <x-filament::button
                        href="{{ route('teacher.subject.grade', ['id' => encrypt($grade->teacher_subject_id)]) }}"
                        tag="a"
                        color="gray"
                        outlined
                        icon="heroicon-o-x-mark">
                        Close
                    </x-filament::button>
                </div>

                <!-- Quick Stats -->
                <div class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-4">
                    @php
                        $totalStudents = count($students);
                        $gradedStudents = collect($grades)->filter(fn($g) => !empty($g['existing_id']))->count();
                        $pendingStudents = $totalStudents - $gradedStudents;
                        $averageGrade = collect($grades)->filter(fn($g) => !empty($g['grade']))->avg('grade');
                    @endphp

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-blue-600 font-medium">Total Students</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $totalStudents }}</div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-green-600 font-medium">Graded</div>
                        <div class="text-2xl font-bold text-green-900">{{ $gradedStudents }}</div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-yellow-600 font-medium">Pending</div>
                        <div class="text-2xl font-bold text-yellow-900">{{ $pendingStudents }}</div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-purple-600 font-medium">Class Average</div>
                        <div class="text-2xl font-bold text-purple-900">
                            {{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Info -->
    <div class="fixed bottom-4 right-4 bg-white p-3 rounded-lg shadow-lg text-xs text-gray-600 border">
        <div class="font-semibold mb-1">💡 Tips:</div>
        <div>• Press <kbd class="bg-gray-100 px-2 py-1 rounded">Enter</kbd> to move to next field</div>
        <div>• Click "Save All Grades" to save all changes at once</div>
    </div>
    <style>
    kbd {
        font-family: monospace;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 1;
    }
</style>
</div>


