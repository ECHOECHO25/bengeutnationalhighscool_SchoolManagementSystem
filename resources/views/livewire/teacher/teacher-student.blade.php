<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto bg-gray-300 p-5 rounded-2xl">

            <!-- Header with toggle & export buttons -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h1 class="text-2xl text-gray-700">
                        <strong>{{$classroom->gradeLevel->name}}</strong> |
                        <strong>{{$classroom->section}}</strong> |
                        <strong>Building: {{$classroom->building_number}}</strong>
                    </h1>
                    <h1 class="text-xl font-medium text-gray-600">
                        Adviser: {{$classroom->teacher->lastname}}, {{$classroom->teacher->firstname}}
                    </h1>
                </div>

                <div class="flex space-x-2">

<x-filament::button
    wire:click="toggleExcelView"
    icon="heroicon-o-arrows-right-left"
    size="md"
    color="primary">
    {{ $excelView ? 'Table View' : 'Excel Style View' }}
</x-filament::button>


                    <x-filament::button
                        wire:click="exportExcel"
                        icon="heroicon-o-document-arrow-down"
                        size="md"
                        color="success">
                        Export to Excel
                    </x-filament::button>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded-xl shadow mb-4 w-full">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-sm text-blue-600 font-medium">Male Students</div>
                <div class="text-2xl font-bold text-blue-900">{{ $maleCount }}</div>
            </div>
            <div class="bg-pink-50 p-4 rounded-lg text-center">
                <div class="text-sm text-pink-600 font-medium">Female Students</div>
                <div class="text-2xl font-bold text-pink-900">{{ $femaleCount }}</div>
            </div>
        </div>


            <!-- Table or Excel-style view -->
            <div class="mt-3">
                @if($excelView)
                    <!-- Excel-style view -->
                    <div class="overflow-x-auto border rounded-lg bg-white p-5">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LRN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($studentsData as $index => $student)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $student->lrn }}</td>
                                        <td class="px-6 py-4">{{ $student->lastname }}, {{ $student->firstname }}</td>
                                        <td class="px-6 py-4">{{ $student->gradeLevel->name }}</td>
                                        <td class="px-6 py-4">{{ $student->StudentClassrooms->first()->classroom->section ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Filament Table -->
                    <div>
                        {{ $this->table }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
