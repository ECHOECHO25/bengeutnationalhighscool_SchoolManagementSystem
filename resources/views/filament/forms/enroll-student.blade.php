<div class="border rounded-2xl px-5 py-2 flex items-center space-x-3">
    <img src="{{ asset('images/student.png') }}" class="h-20" alt="">
    <div>
        <h1 class="text-lg font-medium">{{ $getRecord()->getFullNameAttribute() }}</h1>
        <h1 class="text-gray-600 font-medium"> {{ $getRecord()->gradeLevel->name }} -
            {{ $getRecord()->gradeLevel->department }}</h1>
    </div>
</div>
