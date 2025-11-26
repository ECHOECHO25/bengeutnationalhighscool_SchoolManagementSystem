<div>
    <table>
        <thead>
            <tr>
                <th>Building Number</th>
                <th>Grade Level</th>
                <th>Section</th>
                <th>Homeroom Teacher</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>School Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classrooms as $classroom)
            <tr>
               <td>{{ $classroom->building_number }}</td>
               <td>{{ $classroom->gradeLevel->name }}</td>
               <td>{{ $classroom->section }}</td>
               <td>{{ $classroom->teacher->lastname }}, {{ $classroom->teacher->firstname }}</td>
                <td>{{ $classroom->capacity }}</td>
                <td>{{ $classroom->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ \Carbon\Carbon::parse($classroom->schoolYear->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($classroom->schoolYear->end_date)->format('Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>