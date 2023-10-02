<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.tasks') }}</h1>

            <h2 class="smaller-headline">{{ __('app.tasks_hint') }}</h2>

            @include('flashmsg.php')

            <div class="margin-vertical">
                <a class="button is-success" href="javascript:void(0);" onclick="window.vue.bShowCreateTask = true;">{{ __('app.create_new') }}</a>
            </div>

            <div class="margin-vertical">
                <a class="is-default-link" href="{{ url('/tasks') }}">{{ __('app.tasks_todo') }}</a>&nbsp;|&nbsp;<a class="is-default-link" href="{{ url('/tasks?done=1') }}">{{ __('app.tasks_done') }}</a>
            </div>

            @if (isset($tasks))
                <div class="tasks">
                    @foreach ($tasks as $task)
                        <div class="task" id="task-item-{{ $task->get('id') }}">
                            <div class="task-header">
                                <div class="task-header-title" id="task-item-title-{{ $task->get('id') }}">{{ $task->get('title') }}</div>
                                <div class="task-header-action"><a href="javascript:void(0);" onclick="window.vue.editTask({{ $task->get('id') }});"><i class="fas fa-edit"></i></a></div>
                            </div>
                            <div class="task-description" id="task-item-description-{{ $task->get('id') }}">{{ ($task->get('description')) ?? 'N/A' }}</div>
                            <div class="task-footer">
                                <div class="task-footer-date">{{ (new Carbon($task->get('created_at')))->diffForHumans() }}</div>
                                <div class="task-footer-action">
                                    <input type="radio" onclick="window.vue.toggleTaskStatus({{ $task->get('id') }});" {{ ($task->get('done')) ? 'checked' : '' }} /><a href="javascript:void(0);" onclick="window.vue.toggleTaskStatus({{ $task->get('id') }});">&nbsp;{{ __('app.done') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
		</div>
	</div>

	<div class="column is-2"></div>
</div>