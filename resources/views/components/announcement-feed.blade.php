@php use Illuminate\Support\Str; @endphp

<div class="list-group list-group-flush">
    @forelse ($announcements as $announcement)
        @php
            $badge = $announcement->badgeClass();
            $statusLabel = $announcement->statusLabel();
            $excerpt = Str::limit(strip_tags($announcement->body), 160);
            $postedBy = $announcement->creator
                ? $announcement->creator->first_name . ' ' . $announcement->creator->last_name
                : 'System';
            $publishDate = optional($announcement->publish_at)->format('M d, Y');
            $audience = $announcement->audience ? implode(', ', array_map('ucfirst', $announcement->audience)) : 'All Users';
        @endphp

        <div class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center me-3" style="flex-grow: 1; min-width: 0;">
                <div class="me-3">
                    <h6 class="mb-0 text-truncate fw-bold" style="max-width: 260px;">
                        {{ $announcement->title }}
                    </h6>
                    <span class="text-muted small text-truncate d-none d-lg-inline-block">
                        Posted by {{ $postedBy }} • {{ $publishDate }} • {{ $audience }}
                    </span>
                    <span class="text-muted small d-lg-none">
                        {{ $publishDate }}
                    </span>
                    <p class="mt-2 mb-0 text-muted small">
                        {{ $excerpt }}
                    </p>
                </div>
                <span class="badge bg-{{ $badge }} flex-shrink-0 d-none d-md-inline">{{ $statusLabel }}</span>
            </div>

            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 ms-2" type="button"
                    data-bs-toggle="collapse" data-bs-target="#announcement-body-{{ $announcement->id }}">
                Details →
            </button>
        </div>
        <div class="collapse border-top px-4 py-3" id="announcement-body-{{ $announcement->id }}">
            <p class="mb-0">{!! nl2br(e($announcement->body)) !!}</p>
        </div>
    @empty
        <div class="list-group-item text-center py-5 text-muted">
            No announcements available right now.
        </div>
    @endforelse
</div>





