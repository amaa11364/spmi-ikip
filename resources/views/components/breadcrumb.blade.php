{{-- resources/views/components/breadcrumb.blade.php --}}
@php
    $segments = request()->segments();
@endphp

@if(count($segments) > 0)
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-light p-3 rounded">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        
        @foreach($segments as $segment)
            @php
                $url = implode('/', array_slice($segments, 0, $loop->iteration));
                $isLast = $loop->last;
                $segmentName = ucfirst(str_replace('-', ' ', $segment));
                
                // Custom segment names
                if ($segment === 'admin') {
                    $segmentName = 'Admin';
                } elseif ($segment === 'berita') {
                    $segmentName = 'Berita';
                } elseif ($segment === 'jadwal') {
                    $segmentName = 'Jadwal';
                }
            @endphp
            
            @if(!$isLast)
                <li class="breadcrumb-item">
                    <a href="/{{ $url }}" class="text-decoration-none">
                        {{ $segmentName }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $segmentName }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif