<div>
    <table>
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Lastname</th>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Email</th>
                <th>Birthdate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->teacher_identification_number }}</td>
                <td>{{ $teacher->lastname }}</td>
                <td>{{ $teacher->firstname }}</td>
                <td>{{ $teacher->middlename }}</td>
                <td>{{ $teacher->user->email }}</td>
                <td>{{ $teacher->birthdate }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>