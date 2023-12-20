<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>{{ __('app.reset_password') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

		@if (env('APP_DEBUG'))
		<script src="{{ asset('js/vue.js') }}"></script>
		@else
		<script src="{{ asset('js/vue.min.js') }}"></script>
		@endif
		<script src="{{ asset('js/fontawesome.js') }}"></script>
    </head>

    <body>
        <div id="app" class="reset-main" style="background-image: url('{{ asset('img/background.jpg') }}');">
            <div class="reset-overlay">
                <div class="reset-content">
                    <h1>{{ __('app.reset_password') }}</h1>

                    @if (FlashMessage::hasMsg('error'))
                    <div class="reset-info reset-info-error">
                        {{ FlashMessage::getMsg('error') }}
                    </div>
                    @elseif (FlashMessage::hasMsg('success'))
                    <div class="reset-info reset-info-success">
                        {{ FlashMessage::getMsg('success') }}
                    </div>
                    @endif

                    <div class="reset-form">
                        <form method="POST" action="{{ url('/password/reset') }}">
                            <input type="hidden" name="token" value="{{ $token }}"/>

                            <div class="field">
                                <div class="control">
                                    <input type="password" class="input" name="password" placeholder="{{ __('app.enter_password') }}" required/>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="password" class="input" name="password_confirmation" placeholder="{{ __('app.enter_password_confirmation') }}" required/>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" class="button is-info" value="{{ __('app.reset_password') }}"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/app.js', true) }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            });
        </script>
    </body>
</html>