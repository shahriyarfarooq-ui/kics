@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Departments</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $d)
                <tr>
                    <td>{{ $d->kics_id }}</td>
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->code }}</td>
                    <td>
                        <a href="{{ route('departments.projects', $d->id) }}" class="btn btn-sm btn-primary">View Projects</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $departments->links() }}
</div>
@endsection
