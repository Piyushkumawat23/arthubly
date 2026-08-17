@extends('admin.layout.app') {{-- Apne layout ka naam yahan likhein --}}

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Edit Short Note</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.notes.update', $note->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Note Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $note->title) }}" required>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Note Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="note-editor" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $note->content) }}</textarea>
                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Update Note</button>
                <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#note-editor'))
        .catch(error => {
            console.error(error);
        });
</script>

<style>
    /* Editor ki height set karne ke liye custom CSS */
    .ck-editor__editable_inline {
        min-height: 250px;
    }
</style>
@endsection