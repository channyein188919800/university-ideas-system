@extends('layouts.qa-coordinator')

@section('title', 'Notifications - ' . auth()->user()->department->name)

@section('content')
<!-- Topbar -->
<div class="qa-topbar reveal" style="--delay: 0s;">
    <div>
        <h3>Notifications</h3>
        <p>Stay updated with department activity</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
            <i class="bi bi-check-all"></i> Mark All Read
        </button>
    </div>
</div>

<!-- Notifications List -->
<div class="row">
    <div class="col-12">
        <div class="qa-card reveal" style="--delay: 0.1s;">
            <div class="qa-card-header">
                <h5><i class="bi bi-bell"></i> Recent Notifications</h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active" id="showAll">All</button>
                    <button class="btn btn-outline-secondary" id="showUnread">Unread</button>
                </div>
            </div>
            <div class="qa-card-body p-0">
                @forelse($notifications as $notification)
                    <div class="feed-item with-border p-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                         data-read="{{ $notification->read_at ? 'read' : 'unread' }}">
                        <div class="d-flex gap-3">
                            <!-- Icon based on notification type -->
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; 
                                        background: {{ $notification->read_at ? '#e9ecef' : 'rgba(214, 158, 46, 0.2)' }};">
                                @if(str_contains($notification->type, 'IdeaSubmitted'))
                                    <i class="bi bi-lightbulb {{ $notification->read_at ? 'text-secondary' : 'text-warning' }}"></i>
                                @elseif(str_contains($notification->type, 'CommentAdded'))
                                    <i class="bi bi-chat {{ $notification->read_at ? 'text-secondary' : 'text-primary' }}"></i>
                                @else
                                    <i class="bi bi-bell {{ $notification->read_at ? 'text-secondary' : 'text-info' }}"></i>
                                @endif
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2">{{ $notification->data['message'] ?? '' }}</p>
                                <div class="d-flex gap-2">
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    @endif
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                onclick="markAsRead('{{ $notification->id }}')">
                                            <i class="bi bi-check"></i> Mark Read
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            @if(!$notification->read_at)
                                <span class="badge bg-primary" style="height: fit-content;">New</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted"></i>
                        <h5 class="mt-3">No Notifications</h5>
                        <p class="text-muted">You're all caught up!</p>
                    </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
                <div class="qa-card-footer border-top p-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                <h5>Processing...</h5>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showLoading() {
        const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
        modal.show();
        return modal;
    }

    function markAsRead(notificationId) {
        const modal = showLoading();
        
        fetch(`/qa-coordinator/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            if (data.success) {
                location.reload();
            }
        })
        .catch(() => {
            modal.hide();
            alert('Error marking notification as read');
        });
    }

    function markAllAsRead() {
        if (!confirm('Mark all notifications as read?')) return;
        
        const modal = showLoading();
        
        fetch('{{ route("qa-coordinator.notifications.read-all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            if (data.success) {
                location.reload();
            }
        })
        .catch(() => {
            modal.hide();
            alert('Error marking notifications as read');
        });
    }

    // Filter notifications
    document.getElementById('showAll')?.addEventListener('click', function() {
        document.querySelectorAll('.feed-item').forEach(item => {
            item.style.display = 'flex';
        });
        this.classList.add('active');
        document.getElementById('showUnread').classList.remove('active');
    });

    document.getElementById('showUnread')?.addEventListener('click', function() {
        document.querySelectorAll('.feed-item').forEach(item => {
            if (item.dataset.read === 'read') {
                item.style.display = 'none';
            } else {
                item.style.display = 'flex';
            }
        });
        this.classList.add('active');
        document.getElementById('showAll').classList.remove('active');
    });
</script>
@endpush