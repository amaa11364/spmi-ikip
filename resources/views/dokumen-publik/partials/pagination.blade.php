@if($dokumens->hasPages())
<div class="row mt-4" data-page="{{ $dokumens->currentPage() }}" data-total="{{ $dokumens->total() }}">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0" id="paginationLinks">
                    {{-- Previous Page --}}
                    @if ($dokumens->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">‹</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->previousPageUrl() }}" 
                               onclick="return window.handlePaginationGlobal(event, '{{ $dokumens->previousPageUrl() }}')">‹</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $dokumens->currentPage();
                        $last = $dokumens->lastPage();
                        $start = max(1, $current - 1);
                        $end = min($last, $current + 1);
                    @endphp

                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->url(1) }}" 
                               onclick="return window.handlePaginationGlobal(event, '{{ $dokumens->url(1) }}')">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i == $current ? 'active' : '' }}">
                            <a class="page-link" href="{{ $dokumens->url($i) }}" 
                               onclick="return window.handlePaginationGlobal(event, '{{ $dokumens->url($i) }}')">{{ $i }}</a>
                        </li>
                    @endfor

                    @if($end < $last)
                        @if($end < $last - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->url($last) }}" 
                               onclick="return window.handlePaginationGlobal(event, '{{ $dokumens->url($last) }}')">{{ $last }}</a>
                        </li>
                    @endif

                    {{-- Next Page --}}
                    @if ($dokumens->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->nextPageUrl() }}" 
                               onclick="return window.handlePaginationGlobal(event, '{{ $dokumens->nextPageUrl() }}')">›</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">›</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        <div class="text-center mt-2">
            <small class="text-muted">
                Halaman {{ $dokumens->currentPage() }} dari {{ $dokumens->lastPage() }}
                @if($dokumens->total() > 0)
                    • Total {{ $dokumens->total() }} dokumen
                @endif
            </small>
        </div>
    </div>
</div>
@else
<div class="row mt-4" data-page="1" data-total="{{ $dokumens->total() }}">
    <div class="col-12 text-center">
        <small class="text-muted">
            @if($dokumens->total() > 0)
                Menampilkan semua {{ $dokumens->count() }} dokumen
            @else
                Tidak ada dokumen yang ditemukan
            @endif
        </small>
    </div>
</div>
@endif