@extends('layouts.app')

@section('title', 'Homepage Popup')

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
                <div class="col-12 col-xl-8">
                    <h4 class="page-title mb-4">Homepage Announcement Popup</h4>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.announcement-popup.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label" for="title">Popup label</label>
                                    <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $popup->title ?? '') }}" maxlength="255">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                @if($popup?->image_path)
                                    <div class="mb-3">
                                        <label class="form-label d-block">Current image</label>
                                        <img src="{{ rtrim(request()->getBaseUrl(), '/') }}/storage/{{ ltrim($popup->image_path, '/') }}" alt="Current popup" style="max-width: 100%; max-height: 320px; object-fit: contain;">
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ $popup ? 'Replace image' : 'Popup image' }}{{ $popup ? '' : ' *' }}</label>
                                    <input class="form-control @error('image') is-invalid @enderror" id="image" type="file" name="image" accept="image/*" {{ $popup ? '' : 'required' }}>
                                    <small class="text-muted">Maximum file size: 10 MB.</small>
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="link_url">Link when popup is clicked</label>
                                    <input class="form-control @error('link_url') is-invalid @enderror" id="link_url" type="url" name="link_url" value="{{ old('link_url', $popup->link_url ?? '') }}" placeholder="https://example.com">
                                    @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $popup->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Show popup on the homepage</label>
                                </div>

                                <button class="btn btn-primary" type="submit">Save popup</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
