<div>
    <div class="py-10">
        <main class="w-full max-w-7xl  mx-auto p-6 lg:p-8">
            <!-- Welcome Banner -->
            <div
                class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 rounded-xl shadow-md overflow-hidden mb-10">
                <div class="p-8 text-white text-center">
                    <h1 class="text-3xl font-bold mb-3">Welcome to {{ config('app.name') }}</h1>
                    <p class="text-blue-100">School Management System</p>
                </div>
            </div>

            <div class="mb-10">
                <h1 class="font-semibold text-2xl text-white uppercase">STATISTICS</h1>
                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="bg-white hover:cursor-pointer hover:bg-gray-700 hover:text-white relative overflow-hidden rounded-3xl shadow p-6 ">
                        <p class="text-4xl font-bold  mt-8">{{$studentCount}}</p>
                        <span class="font-medium ">Students</span>
                        <div class="absolute -right-5 bottom-0">
                            <x-shared.student class="h-40 " />
                        </div>
                    </div>
                    <div
                        class="bg-white hover:cursor-pointer hover:bg-gray-700 hover:text-white relative overflow-hidden rounded-3xl shadow p-6 ">
                        <p class="text-4xl font-bold  mt-8">{{ $teacherCount }}</p>
                        <span class="font-medium ">Teachers</span>
                        <div class="absolute -right-0 bottom-0">
                            <x-shared.teacher class="h-32 " />
                        </div>
                    </div>
                </div>
                <div class="mt-5 bg-white p-6 rounded-3xl shadow">
                    <div id="chart-container" style="height:400px;"></div>

                    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var dom = document.getElementById('chart-container');
                            var myChart = echarts.init(dom, null, {
                                renderer: 'canvas',
                                useDirtyRect: false
                            });

                            var option = {
                                title: {
                                    text: 'STUDENTS PER GRADE LEVEL',
                                    left: 'center'
                                },
                                tooltip: {
                                    trigger: 'item'
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

                            myChart.setOption(option);
                            window.addEventListener('resize', () => myChart.resize());
                        });
                    </script>
                </div>


            </div>

            <!-- School Image -->
            <div class="relative h-64 w-full rounded-lg shadow-md overflow-hidden mb-10">
                <img src="{{ asset('images/BeNHS.jpg') }}" alt="Benguet National High School Campus"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center px-4">
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Benguet National High School</h2>
                        <p class="text-lg text-blue-100">Excellence in Education </p>
                    </div>
                </div>
            </div>

            <!-- School Description -->

        </main>
    </div>
</div>
