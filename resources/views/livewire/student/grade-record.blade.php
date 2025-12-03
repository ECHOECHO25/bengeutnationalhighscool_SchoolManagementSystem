<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl p-5">
                <h1 class="uppercase text-2xl font-bold text-gray-700">My Grades</h1>
                <span class="text-gray-600 font-medium">This shows all the grades you have in
                    {{ auth()->user()->student->gradeLevel->name . ' - ' . auth()->user()->student->gradeLevel->department }}</span>
                <div class="mt-3">
                    <div x-data="{
                        activeAccordion: '',
                        setActiveAccordion(id) {
                            this.activeAccordion = (this.activeAccordion == id) ? '' : id
                        }
                    }"
                        class="relative w-full mx-auto overflow-hidden text-sm font-normal bg-white border border-gray-200 divide-y divide-gray-200 rounded-md">

                        @foreach ($exams as $exam)
                            <div x-data="{ id: $id('accordion') }" class="cursor-pointer group">
                                <button @click="setActiveAccordion(id)"
                                    class="flex items-center justify-between w-full p-4 text-left select-none group-hover:underline">
                                    <span
                                        class="text-lg uppercase text-gray-700 underline font-bold">{{ $exam->name ?? 'Untitled Exam' }}</span>
                                    <svg class="w-4 h-4 duration-200 ease-out"
                                        :class="{ 'rotate-180': activeAccordion == id }" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div x-show="activeAccordion == id" x-collapse x-cloak>
                                    <div class="p-4 pt-0 opacity-70">
                                        @php
                                            $studentGrades = \App\Models\StudentGrade::where(
                                                'student_id',
                                                auth()->user()->student->id,
                                            )
                                                ->whereHas('subjectGrade', function ($query) use ($exam) {
                                                    $query->where('exam_id', $exam->id)->whereHas('teacherSubject', function ($teacher) {
                                                    $teacher->where(
                                                        'school_year_id',
                                                        \App\Models\SchoolYear::where('is_active', true)->first()->id,
                                                    );
                                                });
                                                })

                                                ->get();
                                        @endphp
                                        <div>
                                            <div class="flex flex-col">
                                                <div class=" overflow-x-auto">
                                                    <div class="min-w-full inline-block align-middle">

                                                        <div class="overflow-hidden ">
                                                            <table class=" min-w-full rounded-xl ">
                                                                <thead>
                                                                    <tr class="bg-gray-200 ">
                                                                        <th scope="col"
                                                                            class="p-5 text-left text-sm  leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">
                                                                            SUBJECT </th>
                                                                        <th scope="col"
                                                                            class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">
                                                                            GRADE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-300 border ">
                                                                    @forelse ($studentGrades as $item)
                                                                        <tr
                                                                            class="bg-white transition-all duration-500 hover:bg-gray-50">
                                                                            <td
                                                                                class="px-5 py-3 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 ">
                                                                                {{ $item->subjectGrade->teacherSubject->name }}
                                                                            </td>
                                                                            <td
                                                                                class="px-5 py-3 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">
                                                                                {{ $item->grade }} </td>

                                                                        </tr>
                                                                        @empty
                                                                        <td
                                                                                class="px-5 py-3 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">
                                                                                No Grade Available... </td>
                                                                    @endforelse

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
