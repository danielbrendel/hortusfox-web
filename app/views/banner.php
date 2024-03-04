<div class="banner" style="background-image: url('{{ asset('img/banner.jpg') }}');">
    @if (file_exists(public_path() . '/img/banner-icon.png'))
        <div class="banner-icon">
            <img src="{{ asset('img/banner-icon.png') }}" alt="banner-icon"/>
        </div>
    @endif
</div>