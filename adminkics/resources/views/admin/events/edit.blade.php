@extends('layouts.app')

@section('title', 'Edit Event')

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
                        <h4 class="page-title">Edit Event</h4>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Title -->
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               name="title" value="{{ old('title', $event->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Event Type -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Event Type *</label>
                                        <select class="form-select @error('event_type') is-invalid @enderror" 
                                                name="event_type" required>
                                            <option value="conference" {{ old('event_type', $event->event_type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                            <option value="workshop" {{ old('event_type', $event->event_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                            <option value="seminar" {{ old('event_type', $event->event_type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                            <option value="summit" {{ old('event_type', $event->event_type) == 'summit' ? 'selected' : '' }}>Summit</option>
                                            <option value="training" {{ old('event_type', $event->event_type) == 'training' ? 'selected' : '' }}>Training</option>
                                            <option value="social" {{ old('event_type', $event->event_type) == 'social' ? 'selected' : '' }}>Social</option>
                                            <option value="other" {{ old('event_type', $event->event_type) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('event_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  name="description" rows="4">{{ old('description', $event->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date *</label>
                                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                               name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                               name="end_date" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Location -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                               name="location" value="{{ old('location', $event->location) }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                               name="address" value="{{ old('address', $event->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Event Status -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Event Status *</label>
                                        <select class="form-select @error('event_status') is-invalid @enderror" 
                                                name="event_status" required>
                                            <option value="upcoming" {{ old('event_status', $event->event_status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                            <option value="ongoing" {{ old('event_status', $event->event_status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                            <option value="completed" {{ old('event_status', $event->event_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ old('event_status', $event->event_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('event_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Registration Deadline -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Registration Deadline</label>
                                        <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                               name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '') }}">
                                        @error('registration_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Registration Link -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Registration Link</label>
                                        <input type="url" class="form-control @error('registration_link') is-invalid @enderror" 
                                               name="registration_link" value="{{ old('registration_link', $event->registration_link) }}" placeholder="https://...">
                                        @error('registration_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Featured Image -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Featured Image</label>
                                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                               name="featured_image" accept="image/*">
                                        @if($event->featured_image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $event->featured_image) }}" 
                                                     width="100" class="img-thumbnail">
                                            </div>
                                        @endif
                                        @error('featured_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Gallery Images -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gallery Images</label>
                                        <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror" 
                                               name="gallery_images[]" accept="image/*" multiple>
                                        @if($event->gallery_images)
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @foreach($event->gallery_images as $image)
                                                    <img src="{{ asset('storage/' . $image) }}" 
                                                         width="60" class="img-thumbnail">
                                                @endforeach
                                            </div>
                                        @endif
                                        @error('gallery_images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Organizer -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Organizer</label>
                                        <input type="text" class="form-control @error('organizer') is-invalid @enderror" 
                                               name="organizer" value="{{ old('organizer', $event->organizer) }}">
                                        @error('organizer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Email -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                               name="contact_email" value="{{ old('contact_email', $event->contact_email) }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Phone -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Contact Phone</label>
                                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               name="contact_phone" value="{{ old('contact_phone', $event->contact_phone) }}">
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Speakers (JSON) -->
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Speakers (JSON format)</label>
                                        <textarea class="form-control @error('speakers') is-invalid @enderror" 
                                                  name="speakers" rows="2" placeholder='[{"name":"Dr. John Doe","title":"Professor","company":"MIT"}]'>{{ old('speakers', $event->speakers ? json_encode($event->speakers) : '') }}</textarea>
                                        @error('speakers')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- SEO Meta -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                               name="meta_title" value="{{ old('meta_title', $event->meta_title) }}">
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                  name="meta_description" rows="2">{{ old('meta_description', $event->meta_description) }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Toggles -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Featured Event</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $event->is_visible) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_visible">Visible on Website</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Event</button>
                                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection