<h1>{{ $task->get('title') }}</h1>

<p>
    <pre>{{ $task->get('description') }}</pre>
</p>

@if (is_object($plant))
<p>
    <a href="{{ url('/plants/details/' . $plant->get('id')) }}">{{ $plant->get('name') }}</a>
</p>

<p>
    <img src="{{ abs_photo($plant->get('photo')) }}" alt="plant-photo"/>
</p>
@endif

<div>
    <div><strong>{{ __('app.due') }}:&nbsp;</strong><span class="is-critical-info">{{ date('Y-m-d', strtotime($task->get('due_date'))) }}</span></div>
    <div><a href="{{ workspace_url('/tasks') }}">{{ workspace_url('/tasks') }}</div>
</div>