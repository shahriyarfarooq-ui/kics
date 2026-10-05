@extends('layouts.app')

@section('title', 'Events Management')

@section('content')
<div class="main-menu">
    @include('components.logo')
    @include('components.sidebar')
</div>

<div class="page-content">
    @include('components.topbar')
    <div class="px-3">
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="page-title">Events Management</h4>
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create Event
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search events..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                        <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                        <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                        <option value="summit" {{ request('type') == 'summit' ? 'selected' : '' }}>Summit</option>
                                        <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Events Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Featured</th>
                                            <th>Visible</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($events as $event)
                                            <tr>
                                                <td>{{ $event->id }}</td>
                                                <td>
                                                    @if($event->featured_image)
                                                        <img src="{{ asset('storage/' . $event->featured_image) }}" 
                                                             alt="{{ $event->title }}" 
                                                             width="50" height="50" 
                                                             class="rounded object-cover" 
                                                             style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width:50px;height:50px;">
                                                            <i class="bi bi-calendar-event text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.events.show', $event) }}" class="text-decoration-none">
                                                        {{ Str::limit($event->title, 40) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $event->type_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $event->start_date->format('M d, Y') }}
                                                    @if($event->end_date)
                                                        <br><small class="text-muted">to {{ $event->end_date->format('M d, Y') }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($event->location, 20) ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $event->status_color }}">
                                                        {{ $event->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $event->is_featured ? 'warning' : 'secondary' }}">
                                                        {{ $event->is_featured ? 'Yes' : 'No' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $event->is_visible ? 'success' : 'danger' }}">
                                                        {{ $event->is_visible ? 'Visible' : 'Hidden' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <!-- Delete Form - Inline -->
                                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline-block;" 
                                                          onsubmit="return confirm('Delete this event?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                                    No events found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $events->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection