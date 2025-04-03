<h1>{{ __('app.account_created') }}</h1>

<p>
    {!! __('app.account_created_hint', ['workspace' => $workspace, 'url' => workspace_url('/auth'), 'password' => $password]) !!}
</p>