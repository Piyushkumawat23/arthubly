@extends('admin.layout.app')

@section('content')
<style>
    .reviews-page {
        --rv-bg: #f6f7f9;
        --rv-card: #fff;
        --rv-border: #e6e8ed;
        --rv-text: #171a21;
        --rv-muted: #747b88;
        --rv-primary: #111827;
        background: var(--rv-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .rv-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .rv-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .rv-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--rv-primary);
        color: #fff;
        font-size: 17px;
    }

    .rv-title {
        margin: 0;
        color: var(--rv-text);
        font-size: 24px;
        line-height: 1.2;
        font-weight: 750;
    }

    .rv-subtitle {
        margin: 5px 0 0;
        color: var(--rv-muted);
        font-size: 13px;
    }

    .rv-add-btn {
        border-radius: 9px !important;
        padding: 9px 15px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }

    .rv-card {
        background: var(--rv-card);
        border: 1px solid var(--rv-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .rv-filter {
        padding: 18px;
    }

    .rv-filter-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 14px;
        color: var(--rv-text);
        font-size: 13px;
        font-weight: 700;
    }

    .rv-filter-title i {
        color: #6b7280;
    }

    .rv-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(130px, 1fr)) auto;
        gap: 10px;
        align-items: center;
    }

    .rv-filter-grid .form-control {
        height: 42px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: none;
        font-size: 12px;
    }

    .rv-filter-actions {
        display: flex;
        gap: 7px;
    }

    .rv-filter-actions .btn {
        height: 42px;
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 11px;
        font-weight: 600;
    }

    .rv-alert {
        border-radius: 9px;
        font-size: 12px;
        margin-bottom: 18px;
    }

    .rv-list-header {
        padding: 15px 18px;
        border-bottom: 1px solid var(--rv-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .rv-list-title {
        margin: 0;
        color: var(--rv-text);
        font-size: 14px;
        font-weight: 700;
    }

    .rv-list-note {
        color: var(--rv-muted);
        font-size: 11px;
    }

    .rv-table-wrap {
        overflow-x: auto;
    }

    .rv-table {
        width: 100%;
        min-width: 1050px;
        margin: 0;
        font-size: 12px;
    }

    .rv-table thead th {
        padding: 12px 11px;
        background: #f8fafc;
        color: #68707d;
        border: 0;
        border-bottom: 1px solid var(--rv-border);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
        font-weight: 700;
    }

    .rv-table tbody td {
        padding: 13px 11px;
        border: 0;
        border-bottom: 1px solid #eef0f3;
        vertical-align: middle;
        color: #374151;
    }

    .rv-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .rv-table tbody tr:hover {
        background: #fbfcfd;
    }

    .rv-table tbody tr.rv-spam-row {
        background: #fff8f8;
    }

    .rv-item {
        min-width: 175px;
    }

    .rv-item-type {
        display: inline-flex;
        padding: 4px 7px;
        margin-bottom: 5px;
        border-radius: 5px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .rv-item-name {
        color: #171a21;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.35;
    }

    .rv-customer {
        min-width: 120px;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
    }

    .rv-guest {
        color: #9ca3af;
        font-weight: 500;
    }

    .rv-rating {
        white-space: nowrap;
    }

    .rv-rating i {
        font-size: 11px;
        margin-right: 1px;
    }

    .rv-rating-number {
        margin-left: 5px;
        color: #6b7280;
        font-size: 10px;
        font-weight: 600;
    }

    .rv-comment {
        max-width: 250px;
        color: #596170;
        line-height: 1.5;
    }

    .rv-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        min-width: 105px;
    }

    .rv-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 7px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 700;
    }

    .rv-tag-verified {
        background: #ecfdf3;
        color: #15803d;
    }

    .rv-tag-spam {
        background: #fef2f2;
        color: #dc2626;
    }

    .rv-date {
        color: #7b8290;
        font-size: 10px;
        white-space: nowrap;
    }

    .rv-actions {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .rv-actions form {
        margin: 0;
    }

    .rv-action-btn {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        padding: 0 !important;
        border-radius: 7px !important;
        font-size: 10px !important;
    }

    .rv-empty {
        padding: 45px 20px !important;
        text-align: center;
    }

    .rv-empty-icon {
        color: #c2c7d0;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .rv-empty-title {
        color: #4b5563;
        font-size: 13px;
        font-weight: 700;
    }

    .rv-empty-text {
        margin-top: 4px;
        color: #9ca3af;
        font-size: 11px;
    }

    .rv-footer {
        padding: 14px 18px;
        border-top: 1px solid var(--rv-border);
        display: flex;
        justify-content: flex-end;
    }

    .rv-footer .pagination {
        margin: 0;
    }

    .rv-footer .page-link {
        border-radius: 7px !important;
        margin-left: 4px;
        border: 1px solid var(--rv-border);
        color: #4b5563;
        font-size: 11px;
    }

    @media (max-width: 1000px) {
        .rv-filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .rv-filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 650px) {
        .reviews-page {
            padding: 14px;
        }

        .rv-header {
            flex-direction: column;
        }

        .rv-title {
            font-size: 20px;
        }

        .rv-add-btn {
            width: 100%;
        }

        .rv-filter-grid {
            grid-template-columns: 1fr;
        }

        .rv-filter-actions {
            grid-column: auto;
        }

        .rv-filter-actions .btn {
            flex: 1;
        }

        .rv-list-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="reviews-page">

    {{-- HEADER --}}
    <div class="rv-header">
        <div class="rv-heading">
            <div class="rv-heading-icon">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h1 class="rv-title">Manage Reviews</h1>
                <p class="rv-subtitle">Review, moderate and manage customer feedback.</p>
            </div>
        </div>

        @can('reviews.add')
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-dark rv-add-btn">
                <i class="fas fa-plus mr-1"></i> Add New Review
            </a>
        @endcan
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success rv-alert">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
        </div>
    @endif --}}

    {{-- FILTERS --}}
    <div class="rv-card">
        <div class="rv-filter">
            <div class="rv-filter-title">
                <i class="fas fa-filter"></i>
                Filter Reviews
            </div>

            <form action="{{ route('admin.reviews.index') }}" method="GET">
                <div class="rv-filter-grid">
                    <select name="rating" class="form-control">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>

                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                    </select>

                    <select name="is_verified" class="form-control">
                        <option value="">All Buyers</option>
                        <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                    </select>

                    <select name="is_spam" class="form-control">
                        <option value="">Spam Filter</option>
                        <option value="1" {{ request('is_spam') == '1' ? 'selected' : '' }}>Only Spam</option>
                        <option value="0" {{ request('is_spam') == '0' ? 'selected' : '' }}>Not Spam</option>
                    </select>

                    <div class="rv-filter-actions">
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>

                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-light border">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- REVIEWS TABLE --}}
    <div class="rv-card">
        <div class="rv-list-header">
            <div>
                <h2 class="rv-list-title">All Reviews</h2>
                <div class="rv-list-note">Moderate customer reviews, spam and verification status.</div>
            </div>
        </div>

        <div class="rv-table-wrap">
            <table class="table rv-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Tags</th>
                        <th>Date</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reviews as $review)
                        <tr class="{{ $review->is_spam ? 'rv-spam-row' : '' }}">
                            <td>
                                <div class="rv-item">
                                    <span class="rv-item-type">
                                        {{ class_basename($review->reviewable_type) }}
                                    </span>

                                    <div class="rv-item-name">
                                        {{ $review->reviewable->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="{{ !$review->user ? 'rv-customer rv-guest' : 'rv-customer' }}">
                                    {{ $review->user->name ?? 'Guest' }}
                                </div>
                            </td>

                            <td>
                                <div class="rv-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor

                                    <span class="rv-rating-number">
                                        {{ $review->rating }}/5
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="rv-comment">
                                    {{ Str::limit($review->comment, 55) }}
                                </div>
                            </td>

                            <td>
                                <div class="rv-tags">
                                    @if($review->is_verified)
                                        <span class="rv-tag rv-tag-verified" title="Verified Buyer">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </span>
                                    @endif

                                    @if($review->is_spam)
                                        <span class="rv-tag rv-tag-spam" title="Spam Review">
                                            <i class="fas fa-ban"></i> Spam
                                        </span>
                                    @endif

                                    @if(!$review->is_verified && !$review->is_spam)
                                        <span class="text-muted" style="font-size:10px;">—</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="rv-date">
                                    {{ $review->created_at->format('d M y, H:i') }}
                                </div>
                            </td>

                            <td>
                                <div class="rv-actions">
                                    {{-- Approve / Pending --}}
                                    <form action="{{ route('admin.reviews.toggle_status', $review->id) }}" method="POST">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm rv-action-btn {{ $review->status ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                            title="{{ $review->status ? 'Set Pending' : 'Approve Review' }}">
                                            <i class="fas {{ $review->status ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Spam --}}
                                    <form action="{{ route('admin.reviews.toggle_spam', $review->id) }}" method="POST">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm rv-action-btn {{ $review->is_spam ? 'btn-danger' : 'btn-outline-warning' }}"
                                            title="{{ $review->is_spam ? 'Remove Spam' : 'Mark as Spam' }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.reviews.edit', $review->id) }}"
                                        class="btn btn-sm btn-outline-info rv-action-btn"
                                        title="Edit Review">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.reviews.destroy', $review->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this review?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger rv-action-btn"
                                            title="Delete Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="rv-empty">
                                <div class="rv-empty-icon">
                                    <i class="far fa-comment-dots"></i>
                                </div>
                                <div class="rv-empty-title">No reviews found</div>
                                <div class="rv-empty-text">
                                    Try changing your filters or add a new review.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rv-footer">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection