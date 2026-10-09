{{-- Unified Semantic Alert & Notification System for Hospital Management System --}}
<style>
    .hms-alerts-wrapper {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        max-width: 440px;
        width: calc(100% - 48px);
        pointer-events: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .hms-alert-card {
        pointer-events: auto;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.16), 0 4px 12px -2px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.9);
        overflow: hidden;
        transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), 
                    transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), 
                    max-height 0.35s ease, 
                    margin 0.35s ease;
        position: relative;
    }

    .hms-alert-card.hms-dismissing {
        opacity: 0 !important;
        transform: translateY(-16px) scale(0.96) !important;
        max-height: 0 !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* CRUD Themes */
    .hms-alert-card.hms-alert-success {
        border-left: 5px solid #10b981 !important;
    }
    .hms-alert-card.hms-alert-update {
        border-left: 5px solid #2563eb !important;
    }
    .hms-alert-card.hms-alert-delete {
        border-left: 5px solid #dc2626 !important;
    }
    .hms-alert-card.hms-alert-error {
        border-left: 5px solid #ef4444 !important;
    }
    .hms-alert-card.hms-alert-warning {
        border-left: 5px solid #f59e0b !important;
    }
    .hms-alert-card.hms-alert-info {
        border-left: 5px solid #0ea5e9 !important;
    }

    .hms-alert-inner {
        padding: 14px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .hms-alert-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .hms-alert-content {
        flex-grow: 1;
        min-width: 0;
    }

    .hms-alert-title {
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hms-alert-message {
        font-size: 13px;
        line-height: 1.4;
        color: #475569;
        word-break: break-word;
    }

    .hms-alert-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 18px;
        line-height: 1;
        padding: 4px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s;
        flex-shrink: 0;
    }

    .hms-alert-close:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .hms-alert-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background: rgba(0, 0, 0, 0.05);
    }

    .hms-alert-progress-bar {
        height: 100%;
        width: 100%;
        transform-origin: left;
        animation: hmsAlertProgress 4s linear forwards;
    }

    @keyframes hmsAlertProgress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }

    .hms-alert-card:hover .hms-alert-progress-bar {
        animation-play-state: paused;
    }

    /* Print hiding */
    @media print {
        .hms-alerts-wrapper {
            display: none !important;
        }
    }
</style>

<div class="hms-alerts-wrapper" id="hmsAlertsContainer">
    {{-- Validation Errors Notification --}}
    @if ($errors->any())
        <div class="hms-alert-card hms-alert-error hms-auto-dismiss-alert" data-timeout="6500" role="alert">
            <div class="hms-alert-inner">
                <div class="hms-alert-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <div class="hms-alert-content">
                    <div class="hms-alert-title text-danger">
                        <span>Validation Error</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 font-10">Attention</span>
                    </div>
                    <ul class="mb-0 ps-3 text-danger-emphasis font-12" style="line-height: 1.45;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="hms-alert-close" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="hms-alert-progress">
                <div class="hms-alert-progress-bar" style="background: #ef4444; animation-duration: 6.5s;"></div>
            </div>
        </div>
    @endif

    {{-- CRUD Flash Notification Cards --}}
    @php
        $flashAlerts = [];

        // Check explicit keys
        if (session('success')) {
            $msg = (string) session('success');
            $lower = strtolower($msg);
            
            // Auto-detect operation from message content
            if (str_contains($lower, 'delete') || str_contains($lower, 'cancel') || str_contains($lower, 'remove')) {
                $flashAlerts[] = [
                    'type' => 'delete',
                    'badge' => 'Deleted',
                    'title' => 'Record Removed',
                    'message' => $msg,
                    'icon' => 'bi-trash3-fill',
                    'accent' => '#dc2626',
                    'bg' => '#fef2f2',
                ];
            } elseif (str_contains($lower, 'update') || str_contains($lower, 'edit') || str_contains($lower, 'modified')) {
                $flashAlerts[] = [
                    'type' => 'update',
                    'badge' => 'Updated',
                    'title' => 'Record Updated',
                    'message' => $msg,
                    'icon' => 'bi-pencil-square',
                    'accent' => '#2563eb',
                    'bg' => '#eff6ff',
                ];
            } else {
                $flashAlerts[] = [
                    'type' => 'success',
                    'badge' => 'Success',
                    'title' => 'Record Saved',
                    'message' => $msg,
                    'icon' => 'bi-check-circle-fill',
                    'accent' => '#10b981',
                    'bg' => '#ecfdf5',
                ];
            }
        }

        if (session('update')) {
            $flashAlerts[] = [
                'type' => 'update',
                'badge' => 'Updated',
                'title' => 'Record Updated',
                'message' => session('update'),
                'icon' => 'bi-pencil-square',
                'accent' => '#2563eb',
                'bg' => '#eff6ff',
            ];
        }

        if (session('delete')) {
            $flashAlerts[] = [
                'type' => 'delete',
                'badge' => 'Deleted',
                'title' => 'Record Removed',
                'message' => session('delete'),
                'icon' => 'bi-trash3-fill',
                'accent' => '#dc2626',
                'bg' => '#fef2f2',
            ];
        }

        if (session('error')) {
            $flashAlerts[] = [
                'type' => 'error',
                'badge' => 'Failed',
                'title' => 'Action Failed',
                'message' => session('error'),
                'icon' => 'bi-x-octagon-fill',
                'accent' => '#ef4444',
                'bg' => '#fef2f2',
            ];
        }

        if (session('warning')) {
            $flashAlerts[] = [
                'type' => 'warning',
                'badge' => 'Warning',
                'title' => 'Notice',
                'message' => session('warning'),
                'icon' => 'bi-exclamation-triangle-fill',
                'accent' => '#f59e0b',
                'bg' => '#fffbeb',
            ];
        }

        if (session('info')) {
            $flashAlerts[] = [
                'type' => 'info',
                'badge' => 'Info',
                'title' => 'Information',
                'message' => session('info'),
                'icon' => 'bi-info-circle-fill',
                'accent' => '#0ea5e9',
                'bg' => '#f0f9ff',
            ];
        }

        if (session('status') && !session('success') && !session('update') && !session('delete')) {
            $flashAlerts[] = [
                'type' => 'info',
                'badge' => 'Status',
                'title' => 'System Notice',
                'message' => session('status'),
                'icon' => 'bi-info-circle-fill',
                'accent' => '#0ea5e9',
                'bg' => '#f0f9ff',
            ];
        }
    @endphp

    @foreach ($flashAlerts as $item)
        <div class="hms-alert-card hms-alert-{{ $item['type'] }} hms-auto-dismiss-alert" data-timeout="4000" role="alert">
            <div class="hms-alert-inner">
                <div class="hms-alert-icon-wrap" style="background: {{ $item['bg'] }}; color: {{ $item['accent'] }};">
                    <i class="bi {{ $item['icon'] }}"></i>
                </div>
                <div class="hms-alert-content">
                    <div class="hms-alert-title" style="color: {{ $item['accent'] }};">
                        <span>{{ $item['title'] }}</span>
                        <span class="badge border font-10" style="background: {{ $item['bg'] }}; color: {{ $item['accent'] }} !important; border-color: {{ $item['accent'] }}33 !important;">
                            {{ $item['badge'] }}
                        </span>
                    </div>
                    <div class="hms-alert-message">
                        {{ $item['message'] }}
                    </div>
                </div>
                <button type="button" class="hms-alert-close" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="hms-alert-progress">
                <div class="hms-alert-progress-bar" style="background: {{ $item['accent'] }}; animation-duration: 4s;"></div>
            </div>
        </div>
    @endforeach
</div>

<script>
    (function () {
        function setupHmsAlertAutoDismiss() {
            var alertCards = document.querySelectorAll('.hms-auto-dismiss-alert');
            if (!alertCards.length) return;

            alertCards.forEach(function (card) {
                var timeoutMs = parseInt(card.getAttribute('data-timeout') || '4000', 10);
                var timerId = null;

                function closeCard() {
                    if (card.classList.contains('hms-dismissing')) return;
                    card.classList.add('hms-dismissing');
                    setTimeout(function () {
                        if (card.parentNode) {
                            card.parentNode.removeChild(card);
                        }
                    }, 380);
                }

                function startDismissTimer(duration) {
                    clearTimeout(timerId);
                    timerId = setTimeout(closeCard, duration);
                }

                // Initial timer
                startDismissTimer(timeoutMs);

                // Pause auto-dismiss on hover, resume on mouse leave
                card.addEventListener('mouseenter', function () {
                    clearTimeout(timerId);
                });

                card.addEventListener('mouseleave', function () {
                    startDismissTimer(2000);
                });

                // Manual close button click
                var closeBtn = card.querySelector('.hms-alert-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        clearTimeout(timerId);
                        closeCard();
                    });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupHmsAlertAutoDismiss);
        } else {
            setupHmsAlertAutoDismiss();
        }
    })();
</script>
