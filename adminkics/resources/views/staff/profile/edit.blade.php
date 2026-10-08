<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile | KICS</title>
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Staff Profile</h2>
                    <p class="text-muted mb-0">Your name, designation, and department are maintained by KICS.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">Log out</button>
                </form>
            </div>

            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-warning">{{ session('error') }}</div>@endif
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="card mb-4"><div class="card-body">
                <h4 class="mb-1">{{ $person->full_name }}</h4>
                <p class="text-muted mb-0">{{ $person->designation->designation_name ?? 'Staff Member' }}@if($person->group?->name) · {{ $person->group->name }}@endif</p>
            </div></div>

            @if($person->profile_edit_locked)<div class="alert alert-warning">Profile editing has been locked by the administrator. You can view your information but cannot update it.</div>@endif

            <form method="POST" action="{{ route('staff.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <fieldset {{ $person->profile_edit_locked ? 'disabled' : '' }}>
                    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">About Me</h5></div><div class="card-body">
                        <div class="mb-3"><label class="form-label" for="about_me">About Me</label><textarea class="form-control" id="about_me" name="about_me" rows="4">{{ old('about_me', $person->about_me) }}</textarea></div>
                        <div><label class="form-label" for="profile_photo">Profile photo</label><input class="form-control" type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp"></div>
                    </div></div>
                    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Education and Certifications</h5></div><div class="card-body">
                        <div class="mb-3"><label class="form-label" for="education">Education</label><textarea class="form-control" id="education" name="education" rows="4" placeholder="One item per line, e.g.&#10;Matric&#10;Intermediate&#10;BS Computer Science&#10;MS Data Science">{{ old('education', $person->education) }}</textarea><div class="form-text">Add one qualification per line.</div></div>
                        <div><label class="form-label" for="certifications">Certifications</label><textarea class="form-control" id="certifications" name="certifications" rows="4" placeholder="One certification per line, e.g.&#10;AWS Certified&#10;Google Cloud Associate&#10;Microsoft Certified">{{ old('certifications', $person->certifications) }}</textarea><div class="form-text">Add one certification per line.</div></div>
                    </div></div>
                    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Achievements and Work Experience</h5></div><div class="card-body">
                        <div class="mb-3"><label class="form-label" for="achievements">Achievements</label><textarea class="form-control" id="achievements" name="achievements" rows="4" placeholder="One achievement per line, e.g.&#10;Best Researcher 2024&#10;Distinguished Faculty Award">{{ old('achievements', $person->achievements) }}</textarea><div class="form-text">Add one achievement per line.</div></div>
                        <div><label class="form-label" for="work_experience">Work Experience</label><textarea class="form-control" id="work_experience" name="work_experience" rows="4" placeholder="One role per line, e.g.&#10;Senior Research Officer, KICS&#10;Lab Engineer, UET">{{ old('work_experience', $person->work_experience) }}</textarea><div class="form-text">Add one role per line.</div></div>
                    </div></div>
                    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Publications and Projects</h5></div><div class="card-body">
                        <div class="mb-3"><label class="form-label" for="publications">Publications</label><textarea class="form-control" id="publications" name="publications" rows="4" placeholder="One publication per line">{{ old('publications', $person->publications) }}</textarea><div class="form-text">Add one publication per line.</div></div>
                        <div><label class="form-label" for="projects">Projects</label><textarea class="form-control" id="projects" name="projects" rows="4" placeholder="One project per line">{{ old('projects', $person->projects) }}</textarea><div class="form-text">Add one project per line.</div></div>
                    </div></div>
                    <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Social Links</h5></div><div class="card-body">
                        <div class="mb-3"><label class="form-label" for="linkedin_url">LinkedIn</label><input class="form-control" type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $person->linkedin_url) }}" placeholder="https://"></div>
                        <div class="mb-3"><label class="form-label" for="github_url">GitHub</label><input class="form-control" type="url" id="github_url" name="github_url" value="{{ old('github_url', $person->github_url) }}" placeholder="https://"></div>
                        <div><label class="form-label" for="website_url">Website</label><input class="form-control" type="url" id="website_url" name="website_url" value="{{ old('website_url', $person->website_url) }}" placeholder="https://"></div>
                    </div></div>
                    @unless($person->profile_edit_locked)<button class="btn btn-primary btn-lg" type="submit">Save Profile</button>@endunless
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>
