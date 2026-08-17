@extends('admin.layout.app') {{-- Apne admin panel ke main layout ka naam yahan likhein, jaise 'admin.layouts.master' ya jo aap use karte hain --}}

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Success & Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Create Social Media Post</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.social.store') }}" method="POST">
                        @csrf
                        
                        {{-- Message/Caption --}}
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Post Caption / Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="Write your post caption here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image URL --}}
                        <div class="mb-3">
                            <label for="image_url" class="form-label fw-bold">Image URL</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://yoursite.com/images/post1.jpg" required>
                            <small class="text-muted">Note: Meta Graph API requires a publicly accessible Image URL (especially for Instagram).</small>
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Platform Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Select Platforms to Publish:</label>
                            
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="facebook" name="platforms[]" value="facebook" checked>
                                <label class="form-check-label" for="facebook">
                                    <i class="bi bi-facebook text-primary"></i> Facebook
                                </label>
                            </div>
                            
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="instagram" name="platforms[]" value="instagram" checked>
                                <label class="form-check-label" for="instagram">
                                    <i class="bi bi-instagram text-danger"></i> Instagram
                                </label>
                            </div>
                            
                            @error('platforms')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-send"></i> Publish Post Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection