@if ($paginator->hasPages())
<style>
    /* Modern Pagination Styles */
    .modern-pagination-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        margin: 40px 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .pagination-info {
        text-align: center;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        background: #f8fafc;
        padding: 12px 24px;
        border-radius: 25px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px #f0f0f0;
    }

    .pagination-info .highlight {
        color: #2d4a70;
        font-weight: 700;
    }

    .modern-pagination {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        padding: 12px 20px;
        border-radius: 20px;
        box-shadow: 0 8px 32px #e0e7f0;
        border: 1px solid #f0f4f8;
        position: relative;
        overflow: hidden;
    }

    .modern-pagination::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #2d4a70;
    }

    .page-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 8px 12px;
        border: 2px solid transparent;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        background: #fff;
        color: #64748b;
        cursor: pointer;
    }

    .page-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, #f5f5f5, transparent);
        transition: left 0.5s;
    }

    .page-btn:hover::before {
        left: 100%;
    }

    .page-btn:hover {
        transform: translateY(-2px) scale(1.05);
        border-color: #2d4a70;
        color: #2d4a70;
        box-shadow: 0 8px 25px #d0d7e0;
        background: #f8fafc;
    }

    .page-btn.active {
        background: #2d4a70;
        color: #fff;
        border-color: #2d4a70;
        transform: translateY(-1px);
        box-shadow: 0 12px 32px #c5d2e0;
        position: relative;
        z-index: 2;
    }

    .page-btn.active::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        background: #e5e7eb;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        to {
            transform: translate(-50%, -50%) scale(2);
            opacity: 0;
        }
    }

    .page-btn.active:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 16px 40px #b8c5d5;
        background: #1e3a52;
    }

    .page-btn.disabled {
        color: #cbd5e1;
        background: #f8fafc;
        border-color: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .page-btn.disabled:hover {
        transform: none;
        box-shadow: none;
        background: #f8fafc;
        border-color: #f1f5f9;
        color: #cbd5e1;
    }

    .page-btn.nav-btn {
        font-weight: 700;
        padding: 8px 16px;
        gap: 8px;
        min-width: auto;
    }

    .page-btn.prev-btn {
        border-radius: 16px 10px 10px 16px;
    }

    .page-btn.next-btn {
        border-radius: 10px 16px 16px 10px;
    }

    .page-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        color: #94a3b8;
        font-weight: 700;
        font-size: 16px;
        cursor: default;
        position: relative;
    }

    .page-dots::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 2px;
        background: #2d4a70;
        border-radius: 1px;
        opacity: 0.3;
    }

    /* Mobile Styles */
    .mobile-pagination {
        display: none;
        justify-content: space-between;
        width: 100%;
        max-width: 400px;
        gap: 12px;
    }

    .mobile-page-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 20px;
        background: #fff;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px #f0f0f0;
    }

    .mobile-page-btn:hover {
        transform: translateY(-2px);
        border-color: #2d4a70;
        color: #2d4a70;
        box-shadow: 0 8px 25px #d0d7e0;
    }

    .mobile-page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .mobile-page-btn.disabled:hover {
        transform: none;
        border-color: #e2e8f0;
        color: #64748b;
        box-shadow: 0 4px 16px #f0f0f0;
    }

    .page-indicator {
        background: #f8fafc;
        color: #2d4a70;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    /* Icons */
    .nav-icon {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        stroke-width: 2.5;
        fill: none;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .modern-pagination {
            display: none;
        }
        
        .mobile-pagination {
            display: flex;
        }
        
        .pagination-info {
            font-size: 13px;
            padding: 10px 20px;
        }
        
        .modern-pagination-wrapper {
            margin: 30px 0;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .mobile-page-btn {
            padding: 12px 16px;
            font-size: 14px;
        }
        
        .page-indicator {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>

<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="modern-pagination-wrapper">
    <!-- Pagination Info -->
    <div class="pagination-info">
        {!! __('Showing') !!}
        @if ($paginator->firstItem())
            <span class="highlight">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="highlight">{{ $paginator->lastItem() }}</span>
        @else
            <span class="highlight">{{ $paginator->count() }}</span>
        @endif
        {!! __('of') !!}
        <span class="highlight">{{ $paginator->total() }}</span>
        {!! __('results') !!}
    </div>

    <!-- Desktop Pagination -->
    <div class="modern-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn nav-btn prev-btn disabled" aria-disabled="true">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
               class="page-btn nav-btn prev-btn" 
               aria-label="{{ __('pagination.previous') }}">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-dots" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" 
                           class="page-btn" 
                           aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
               class="page-btn nav-btn next-btn" 
               aria-label="{{ __('pagination.next') }}">
                Next
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </a>
        @else
            <span class="page-btn nav-btn next-btn disabled" aria-disabled="true">
                Next
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </span>
        @endif
    </div>

    <!-- Mobile Pagination -->
    <div class="mobile-pagination">
        @if ($paginator->onFirstPage())
            <span class="mobile-page-btn disabled">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="mobile-page-btn">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                Previous
            </a>
        @endif

        <div class="page-indicator">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="mobile-page-btn">
                Next
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </a>
        @else
            <span class="mobile-page-btn disabled">
                Next
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </span>
        @endif
    </div>
</nav>
@endif