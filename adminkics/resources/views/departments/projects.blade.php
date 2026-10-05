@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Projects for: {{ $department->name }}</h1>

    @if($projects->isEmpty())
        <p>No projects found for this department.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Group</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>{{ $p->projectlist_id }}</td>
                        <td>{{ $p->projectlist_Name }}</td>
                        <td>{{ optional($p->group)->group_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}
    @endif

    <a href="{{ url('/departments') }}" class="btn btn-secondary">Back to Departments</a>
</div>
@endsection
