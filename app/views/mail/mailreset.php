<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

		<title>{{ __('app.reset_password') }}</title>
    </head>

    <body>
        <h1>{{ __('app.reset_password') }}</h1>

        <p>
            {!! __('app.reset_password_hint', ['workspace' => $workspace, 'url' => url('/password/reset?token=' . $token)]) !!}
        </p>

        <p>
            <small>Powered by {{ env('APP_NAME') }}</small>
        </p>
    </body>
</html>