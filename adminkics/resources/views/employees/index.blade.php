{{-- resources/views/employees/index.blade.php --}}
<table class="table">
    <thead>
        <tr><th>Name</th><th>Job Title</th><th>Department</th><th>State</th></tr>
    </thead>
    <tbody>
    @foreach($employees as $emp)
        <tr>
            <td>{{ $emp->complete_name }}</td>
            <td>{{ $emp->job_title }}</td>
            <td>{{ $emp->department }}</td>
            <td>{{ $emp->state }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $employees->links() }}