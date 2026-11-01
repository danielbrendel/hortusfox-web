@if ((isset($title)) && (is_string($title)))
    <title>{{ app('workspace') . ' - ' . $title }}</title>
@else
    <title>{{ app('workspace') }}</title>
@endif
