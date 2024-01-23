<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>{{ app('workspace') }}</title>

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
        <div id="app" class="auth-main" style="background-image: url('{{ asset('img/background.jpg') }}');">
            <div class="auth-overlay">
                <div class="auth-content">
                    <div class="auth-header">
                        <img src="{{ asset('logo.png') }}" alt="Logo"/>

                        <h1>{{ app('workspace') }}</h1>
                    </div>

                    @if (FlashMessage::hasMsg('error'))
                    <div class="auth-info auth-info-error">
                        {{ FlashMessage::getMsg('error') }}
                    </div>
                    @elseif (FlashMessage::hasMsg('success'))
                    <div class="auth-info auth-info-success">
                        {{ FlashMessage::getMsg('success') }}
                    </div>
                    @endif

                    <div class="auth-form">
                        <form method="POST" action="{{ url('/login') }}">
                            @csrf

                            <div class="field">
                                <div class="control">
                                    <input type="email" class="input" name="email" placeholder="{{ __('app.enter_email') }}" required/>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="password" class="input" name="password" placeholder="{{ __('app.enter_password') }}" required/>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" class="button is-info" value="{{ __('app.login') }}"/>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="auth-help">
                        <a href="javascript:void(0);" onclick="window.vue.bShowRestorePassword = true;">{{ __('app.restore_password') }}</a>
                    </div>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowRestorePassword}">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head is-stretched">
                        <p class="modal-card-title">{{ __('app.restore_password') }}</p>
                        <button class="delete" aria-label="close" onclick="window.vue.bShowRestorePassword = false;"></button>
                    </header>
                    <section class="modal-card-body is-stretched">
                        <form id="frmRestorePassword" method="POST" action="{{ url('/password/restore') }}">
                            @csrf

                            <div class="field">
                                <div class="control">
                                    <input type="email" class="input" name="email" placeholder="{{ __('app.restore_password_email_placeholder') }}" required>
                                </div>
                            </div>
                        </form>
                    </section>
                    <footer class="modal-card-foot is-stretched">
                        <button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmRestorePassword').submit();">{{ __('app.restore_password') }}</button>
                        <button class="button" onclick="window.vue.bShowRestorePassword = false;">{{ __('app.cancel') }}</button>
                    </footer>
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