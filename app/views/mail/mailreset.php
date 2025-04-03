<h1>{{ __('app.reset_password') }}</h1>

<p>
    {!! __('app.reset_password_hint', ['workspace' => $workspace, 'url' => workspace_url('/password/reset?token=' . $token)]) !!}
</p>