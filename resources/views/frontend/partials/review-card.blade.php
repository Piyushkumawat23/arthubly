{{-- Arthubly — a review card.
     File: resources/views/frontend/partials/review-card.blade.php
     Included in the reviews section of product_details. --}}
<article class="ps-rev">
    <header>
        <span class="ps-rev-av">{{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}</span>
        <span class="ps-rev-who">
            <b>{{ $review->user->name ?? 'Guest' }}</b>
            @if ($review->is_verified ?? false)
                <small class="ps-rev-vf">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    Verified buyer
                </small>
            @else
                <small>{{ $review->created_at?->diffForHumans() }}</small>
            @endif
        </span>
    </header>

    <span class="ratings"><span class="ratings-val" style="width: {{ ($review->rating / 5) * 100 }}%"></span></span>

    @if (!empty($review->comment))
        <p>{{ $review->comment }}</p>
    @endif

    @if ($review->is_verified ?? false)
        <time class="ps-rev-date">{{ $review->created_at?->diffForHumans() }}</time>
    @endif
</article>
