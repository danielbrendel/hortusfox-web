<h1>{{ __('app.mail_share_photo') }}</h1>

<p>
    {!! __('app.mail_share_photo_hint', ['url_photo' => $url_photo, 'url_removal' => $url_removal]) !!}
</p>

<p>
    <img src="{{ $url_asset }}" alt="shared photo"/>
</p>