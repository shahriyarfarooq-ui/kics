@extends('layouts.app')

@section('title', $event->title)

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
                        <h4 class="page-title">{{ $event->title }}</h4>
                        <div>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Featured Image -->
                                    @if($event->featured_image)
                                        <div class="mb-4">
                                            <img src="{{ asset('storage/' . $event->featured_image) }}" 
                                                 alt="{{ $event->title }}" 
                                                 class="img-fluid rounded">
                                        </div>
                                    @endif

                                    <!-- Description -->
                                    <h5>Description</h5>
                                    <p class="text-muted">{{ $event->description ?? 'No description provided.' }}</p>

                                    <!-- Speakers -->
                                    @if($event->speakers)
                                        <h5 class="mt-4">Speakers</h5>
                                        <div class="row">
                                            @foreach($event->speakers as $speaker)
                                                <div class="col-md-4 mb-2">
                                                    <div class="border rounded p-2">
                                                        <strong>{{ $speaker['name'] ?? 'Unknown' }}</strong>
                                                        <br><small class="text-muted">{{ $speaker['title'] ?? '' }} @if(isset($speaker['company'])) - {{ $speaker['company'] }} @endif</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Gallery -->
                                    @if($event->gallery_images)
                                        <h5 class="mt-4">Gallery</h5>
                                        <div class="row">
                                            @foreach($event->gallery_images as $image)
                                                <div class="col-md-3 mb-2">
                                                    <img src="{{ asset('storage/' . $image) }}" 
                                                         class="img-thumbnail" style="height:150px;width:100%;object-fit:cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Event Details Card -->
                            <div class="card">
                                <div class="card-body">
                                    <h5>Event Details</h5>
                                    <hr>
                                    <dl class="row">
                                        <dt class="col-6">Type</dt>
                                        <dd class="col-6">
                                            <span class="badge bg-info">{{ $event->type_label }}</span>
                                        </dd>

                                        <dt class="col-6">Status</dt>
                                        <dd class="col-6">
                                            <span class="badge bg-{{ $event->status_color }}">
                                                {{ $event->status_label }}
                                            </span>
                                        </dd>

                                        <dt class="col-6">Start Date</dt>
                                        <dd class="col-6">{{ $event->start_date->format('M d, Y H:i') }}</dd>

                                        @if($event->end_date)
                                            <dt class="col-6">End Date</dt>
                                            <dd class="col-6">{{ $event->end_date->format('M d, Y H:i') }}</dd>
                                        @endif

                                        <dt class="col-6">Location</dt>
                                        <dd class="col-6">{{ $event->location ?? 'N/A' }}</dd>

                                        @if($event->address)
                                            <dt class="col-6">Address</dt>
                                            <dd class="col-6">{{ $event->address }}</dd>
                                        @endif

                                        @if($event->organizer)
                                            <dt class="col-6">Organizer</dt>
                                            <dd class="col-6">{{ $event->organizer }}</dd>
                                        @endif

                                        @if($event->contact_email)
                                            <dt class="col-6">Email</dt>
                                            <dd class="col-6"><a href="mailto:{{ $event->contact_email }}">{{ $event->contact_email }}</a></dd>
                                        @endif

                                        @if($event->contact_phone)
                                            <dt class="col-6">Phone</dt>
                                            <dd class="col-6">{{ $event->contact_phone }}</dd>
                                        @endif

                                        @if($event->registration_link)
                                            <dt class="col-12 mt-2">
                                                <a href="{{ $event->registration_link }}" target="_blank" class="btn btn-success w-100">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Register Now
                                                </a>
                                            </dt>
                                        @endif

                                        <dt class="col-6 mt-2">Featured</dt>
                                        <dd class="col-6 mt-2">
                                            <span class="badge bg-{{ $event->is_featured ? 'warning' : 'secondary' }}">
                                                {{ $event->is_featured ? 'Yes' : 'No' }}
                                            </span>
                                        </dd>

                                        <dt class="col-6">Visible</dt>
                                        <dd class="col-6">
                                            <span class="badge bg-{{ $event->is_visible ? 'success' : 'danger' }}">
                                                {{ $event->is_visible ? 'Yes' : 'No' }}
                                            </span>
                                        </dd>

                                        <dt class="col-6">Created</dt>
                                        <dd class="col-6">{{ $event->created_at->format('M d, Y') }}</dd>

                                        <dt class="col-6">Updated</dt>
                                        <dd class="col-6">{{ $event->updated_at->format('M d, Y') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection