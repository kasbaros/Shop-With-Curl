<div class="tf-breadcrumb">
    <div class="container">
        <div class="tf-breadcrumb-wrap d-flex justify-content-between align-items-center">
            <div class="tf-breadcrumb-list">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach($items as $index => $item)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    @if($item['url'])
                                        <a href="{{ $item['url'] }}" class="text-decoration-none">{{ $item['label'] }}</a>
                                    @else
                                        {{ $item['label'] }}
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
