<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="icon" type="image/png" href="{{ asset('logo.png') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

@if (env('APP_DEBUG'))
<script src="{{ asset('js/vue.js') }}"></script>
@else
<script src="{{ asset('js/vue.min.js') }}"></script>
@endif

<script src="{{ asset('js/app.js', true) }}"></script>

<!-- Marker: Webpack-injected styles go BEFORE this, theme styles load AFTER -->
<meta name="webpack-styles-end" content="marker"/>

@if ((ThemeModule::ready()) && (ThemeModule::data()->include))
<link rel="stylesheet" type="text/css" href="{{ ThemeModule::data()->include }}"/>
@endif

@if ((ThemeModule::ready()) && (ThemeModule::data()->script))
<script src="{{ ThemeModule::data()->script }}"></script>
@endif

@if (is_string(app('custom_head_code')))
{!! app('custom_head_code') !!}
@endif
