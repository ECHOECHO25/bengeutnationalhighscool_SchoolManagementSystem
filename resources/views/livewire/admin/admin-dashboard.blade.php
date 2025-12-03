<div>
    <div class="py-10">
        <main class="w-full max-w-7xl mx-auto p-6 lg:p-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 rounded-xl shadow-md overflow-hidden mb-10">
                <div class="p-8 text-white text-center">
                    <h1 class="text-3xl font-bold mb-3">Welcome to {{ config('app.name') }}</h1>
                    <p class="text-blue-100">School Management System</p>
                </div>
            </div>

            <div class="mb-10">
                <h1 class="font-semibold text-2xl text-white uppercase">STATISTICS</h1>

                <!-- Main Statistics Cards -->
                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Students Card -->
                    <div class="bg-white hover:cursor-pointer hover:bg-gray-700 hover:text-white relative overflow-hidden rounded-3xl shadow p-6 transition-colors duration-300">
                        <p class="text-4xl font-bold mt-8">{{ $studentCount }}</p>
                        <span class="font-medium">Students</span>
                        <div class="mt-3 flex gap-4 text-sm">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                <span>Male: <strong>{{ $maleStudents }}</strong></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                <span>Female: <strong>{{ $femaleStudents }}</strong></span>
                            </div>
                        </div>
                        <div class="absolute -right-5 bottom-0">
                            <x-shared.student class="h-40" />
                        </div>
                    </div>

                    <!-- Teachers Card -->
                    <div class="bg-white hover:cursor-pointer hover:bg-gray-700 hover:text-white relative overflow-hidden rounded-3xl shadow p-6 transition-colors duration-300">
                        <p class="text-4xl font-bold mt-8">{{ $teacherCount }}</p>
                        <span class="font-medium">Teachers</span>
                        <div class="mt-3 flex gap-4 text-sm">
                        </div>
                        <div class="absolute -right-0 bottom-0">
                            <x-shared.teacher class="h-32" />
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Grade Level Distribution Chart -->
                    <div class="bg-white p-6 rounded-3xl shadow">
                        <div id="grade-level-chart" style="height:400px;"></div>
                    </div>

                    <!-- Gender Distribution Chart -->
                    <div class="bg-white p-6 rounded-3xl shadow">
                        <div id="gender-chart" style="height:400px;"></div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Grade Level Chart
                        var gradeLevelChart = echarts.init(document.getElementById('grade-level-chart'));
                        var gradeLevelOption = {
                            title: {
                                text: 'STUDENTS PER GRADE LEVEL',
                                left: 'center'
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: '{b}: {c} ({d}%)'
                            },
                            legend: {
                                orient: 'horizontal',
                                bottom: '0'
                            },
                            series: [{
                                name: 'Grade Level',
                                type: 'pie',
                                radius: ['30%', '70%'],
                                center: ['50%', '50%'],
                                roseType: 'area',
                                itemStyle: {
                                    borderRadius: 8
                                },
                                data: @json($gradeLevelCounts)
                            }]
                        };
                        gradeLevelChart.setOption(gradeLevelOption);

                        // Gender Distribution Chart
                        var genderChart = echarts.init(document.getElementById('gender-chart'));
                        var genderOption = {
                            title: {
                                text: 'GENDER DISTRIBUTION',
                                left: 'center'
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: '{b}: {c} ({d}%)'
                            },
                            legend: {
                                orient: 'horizontal',
                                bottom: '0'
                            },
                            series: [{
                                name: 'Gender',
                                type: 'pie',
                                radius: ['40%', '70%'],
                                center: ['50%', '50%'],
                                avoidLabelOverlap: false,
                                itemStyle: {
                                    borderRadius: 10,
                                    borderColor: '#fff',
                                    borderWidth: 2
                                },
                                label: {
                                    show: true,
                                    formatter: '{b}\n{c} students'
                                },
                                emphasis: {
                                    label: {
                                        show: true,
                                        fontSize: 16,
                                        fontWeight: 'bold'
                                    }
                                },
                                data: [
                                    {
                                        value: {{ $maleStudents }},
                                        name: 'Male Students',
                                        itemStyle: { color: '#3b82f6' }
                                    },
                                    {
                                        value: {{ $femaleStudents }},
                                        name: 'Female Students',
                                        itemStyle: { color: '#ec4899' }
                                    }
                                ]
                            }]
                        };
                        genderChart.setOption(genderOption);

                        // Responsive resize
                        window.addEventListener('resize', () => {
                            gradeLevelChart.resize();
                            genderChart.resize();
                        });
                    });
                </script>
            </div>

            <!-- School Image -->
            <div class="relative h-64 w-full rounded-lg shadow-md overflow-hidden mb-10">
                <img src="{{ asset('images/BeNHS.jpg') }}" alt="Benguet National High School Campus"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center px-4">
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Benguet National High School</h2>
                        <p class="text-lg text-blue-100">Excellence in Education</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
