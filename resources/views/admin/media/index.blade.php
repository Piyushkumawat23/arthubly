@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h2 class="mb-0">Media Manager</h2>
            <p class="text-muted text-sm">Manage all uploaded images from products, blogs, and CMS pages.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body bg-light">
            <div class="row g-3">
                @forelse($files as $index => $file)
                    <div class="col-6 col-md-3 col-lg-2 media-item-card" id="media-row-{{ $index }}">
                        <div class="card h-100 border shadow-sm rounded-3 overflow-hidden">
                            <div class="bg-white d-flex align-items-center justify-content-center p-2" style="height: 140px;">
                                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" 
                                     style="max-height: 100%; max-width: 100%; object-fit: contain; cursor: pointer;"
                                     onclick="window.open('{{ $file['url'] }}', '_blank')">
                            </div>
                            
                            <div class="card-footer bg-white border-top p-2 text-center" style="font-size: 12px;">
                                <div class="text-truncate fw-bold mb-1" title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </div>
                                <div class="text-muted mb-2">
                                    <span class="badge bg-secondary">{{ $file['folder'] }}</span> | {{ $file['size'] }}
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-sm btn-outline-primary copy-url-btn w-50 me-1" 
                                            data-url="{{ $file['url'] }}" title="Copy URL for reuse">
                                        Copy
                                    </button>
                                    @can('media.delete')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-media-btn w-50" 
                                            data-path="{{ $file['full_path'] }}" data-id="{{ $index }}">
                                        Delete
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No media files found in uploads folder.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Copy URL to Clipboard Logic
    $(document).on('click', '.copy-url-btn', function() {
        let url = $(this).data('url');
        let $btn = $(this);
        
        navigator.clipboard.writeText(url).then(function() {
            let originalText = $btn.text();
            $btn.text('Copied!').removeClass('btn-outline-primary').addClass('btn-success text-white');
            
            setTimeout(function() {
                $btn.text(originalText).removeClass('btn-success text-white').addClass('btn-outline-primary');
            }, 2000);
        });
    });

    // Delete Media Logic
    $(document).on('click', '.delete-media-btn', function() {
        let filePath = $(this).data('path');
        let cardId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this image? If this image is used in a product or blog, it will be broken.')) {
            $.ajax({
                url: "{{ route('admin.media.destroy') }}",
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    file_path: filePath,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if(res.success) {
                        $('#media-row-' + cardId).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert(res.message);
                    }
                },
                error: function(err) {
                    alert('Error deleting file.');
                }
            });
        }
    });
});
</script>
@endsection