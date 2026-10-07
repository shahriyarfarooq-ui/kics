@extends('layouts.app')

@section('title', 'Page Hero Images')

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
                    <h4 class="page-title mb-2">Page Hero Images</h4>
                    <p class="text-muted mb-4">Manage the banner image shown at the top of each frontend page. If no image is set, that page keeps its current default background.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">Please check the image fields marked below.</div>
                    @endif

                    <form action="{{ route('admin.page-heroes.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            @foreach($pages as $page)
                                @php($banner = $banners->get($page['key']))
                                <div class="col-12 col-md-6 col-xl-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $page['label'] }}</h5>
                                            @if($banner?->image_path)
                                                <img
                                                    src="{{ rtrim(request()->getBaseUrl(), '/') }}/storage/{{ ltrim($banner->image_path, '/') }}"
                                                    alt="{{ $page['label'] }} hero preview"
                                                    class="img-fluid rounded mb-3"
                                                    style="width: 100%; height: 150px; object-fit: cover;"
                                                >
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded bg-light text-muted mb-3" style="height: 150px;">
                                                    No custom image set
                                                </div>
                                            @endif

                                            <label class="form-label" for="image-{{ $page['key'] }}">{{ $banner?->image_path ? 'Replace image' : 'Upload image' }}</label>
                                            <input
                                                class="form-control @error('images.' . $page['key']) is-invalid @enderror"
                                                id="image-{{ $page['key'] }}"
                                                type="file"
                                                name="images[{{ $page['key'] }}]"
                                                accept="image/jpeg,image/png,image/webp,image/avif"
                                            >
                                            @error('images.' . $page['key'])
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-1">JPG, PNG, WebP, or AVIF. Maximum 10 MB.</small>

                                            @if($banner?->image_path)
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox" id="remove-{{ $page['key'] }}" name="remove[{{ $page['key'] }}]" value="1">
                                                    <label class="form-check-label" for="remove-{{ $page['key'] }}">Remove custom image and use default</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="btn btn-primary mb-4" type="submit">Save page images</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
