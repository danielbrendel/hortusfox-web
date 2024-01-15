<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

		<title>{{ __('app.mail_share_photo') }}</title>
    </head>

    <body>
        <h1>{{ __('app.mail_share_photo') }}</h1>

        <p>
            {!! __('app.mail_share_photo_hint', ['url_photo' => $url_photo, 'url_removal' => $url_removal]) !!}
        </p>

        <p>
            <img src="{{ $url_photo }}" alt="shared photo"/>
        </p>

        <p>
            <small>Powered by {{ env('APP_NAME') }}</small>
        </p>
    </body>
</html>