@php
    $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
    $primaryPath = $galleryPaths[0] ?? $product->featured_image;
    $img = $product->getStorageImageUrl($primaryPath);
    $hoverPath = $product->featured_image ?? $primaryPath;
    $hover = $product->getStorageImageUrl($hoverPath);
    $price = (float) ($product->price ?? 0);
    $sale = (float) ($product->sale_price ?? 0);
    $isOnSale = $sale > 0 && $sale < $price;
@endphp
<div class="swiper-slide" lazy="true">
    <div class="card-product">
        <div class="card-product-wrapper">
            <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                <img class="img-product" src="{{ $img }}" alt="{{ $product->name }}">
                <img class="img-hover" src="{{ $hover }}" alt="{{ $product->name }}">
            </a>
            <div class="list-product-btn">
                <a href="javascript:void(0)" class="box-icon bg_white quick-add" data-product-id="{{ $product->id }}">
                    <span class="icon icon-bag"></span>
                    <span class="tooltip">Quick Add</span>
                </a>
                <a href="javascript:void(0)" class="box-icon bg_white wishlist btn-icon-action" data-product-id="{{ $product->id }}">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Add to Wishlist</span>
                    <span class="icon icon-delete"></span>
                </a>
                <a href="javascript:void(0)" class="box-icon bg_white quickview" data-product-id="{{ $product->id }}">
                    <span class="icon icon-view"></span>
                    <span class="tooltip">Quick View</span>
                </a>
            </div>
        </div>
        <div class="card-product-info">
            <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
            @if($isOnSale)
                <span class="price">
                    <span class="text-danger fw-6">{{ money_format_ugx($sale) }}</span>
                    <span class="text-muted text-decoration-line-through ms-1">{{ money_format_ugx($price) }}</span>
                </span>
            @else
                <span class="price">{{ money_format_ugx($price) }}</span>
            @endif
        </div>
    </div>
</div>
