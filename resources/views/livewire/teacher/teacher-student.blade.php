<div>
    <div class="py-10">
        <div class="max-w-7xl mx-auto bg-gray-300 p-5 rounded-2xl">
             <div>
                <h1 class="text-2xl  text-gray-700"><strong>{{$classroom->gradeLevel->name}} </strong>| <strong>{{$classroom->section}}</strong> | <strong>Building: {{$classroom->building_number}}</strong></h1>
                <h1 class="text-xl font-medium text-gray-600">Adviser: {{$classroom->teacher->lastname}}, {{$classroom->teacher->firstname}}</h1>
            </div>
            <div class="mt-3">
                {{$this->table}}
            </div>
        </div>
    </div>
</div>
