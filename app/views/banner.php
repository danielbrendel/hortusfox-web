<div class="banner" style="background-image: url('{{ asset('img/banner.jpg') }}');">
    @if (file_exists(public_path() . '/img/banner-icon.png'))
        <div class="banner-icon">
            <img src="{{ asset('img/banner-icon.png') }}" alt="banner-icon"/>
        </div>
    @endif

    @if (file_exists(public_path() . '/img/banner-accessory.png'))
        <div class="banner-accessory">
            <img src="{{ asset('img/banner-accessory.png') }}" alt="banner-accessory"/>
        </div>
    @endif
</div>