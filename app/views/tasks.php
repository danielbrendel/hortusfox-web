<h1>{{ __('app.tasks') }}</h1>

<h2 class="smaller-headline">{{ __('app.tasks_hint') }}</h2>

@include('flashmsg.php')

<div class="margin-vertical">
    <a class="button is-success" href="javascript:void(0);" onclick="window.vue.bShowCreateTask = true;">{{ __('app.create_new') }}</a>
</div>

<div class="margin-vertical">
    <a class="is-default-link {{ ((!isset($_GET['done'])) || ($_GET['done'] == false)) ? 'is-underlined' : '' }}" href="{{ url('/tasks') }}">{{ __('app.tasks_todo') }}</a>&nbsp;|&nbsp;<a class="is-default-link {{ ((isset($_GET['done'])) && ($_GET['done'] == true)) ? 'is-underlined' : '' }}" href="{{ url('/tasks?done=1') }}">{{ __('app.tasks_done') }}</a>
</div>

<div class="sorting-control is-rounded is-small is-margin-bottom-20">
    <input type="text" id="tasks-filter" placeholder="{{ __('app.filter_by_text') }}">
</div>

@if (isset($tasks))
    <div class="tasks">
        @if (count($tasks) > 0)
            @foreach ($tasks as $task)
                <div class="task" id="task-item-{{ $task->get('id') }}">
                    <a name="task-anchor-{{ $task->get('id') }}"></a>

                    <div class="task-header">
                        <div class="task-header-title" id="task-item-title-{{ $task->get('id') }}">{{ $task->get('title') }}</div>
                        <div class="task-header-action">
                            <span><a href="javascript:void(0);" onclick="window.vue.editTask({{ $task->get('id') }});"><i class="fas fa-edit"></i></a></span>

                            @if ($task->get('done'))
                                <span><a href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_task') }}')) { window.vue.removeTask({{ $task->get('id') }}); }"><i class="fas fa-trash-alt"></i></a></span>
                            @endif
                        </div>
                    </div>

                    <div class="task-description" id="task-item-description-{{ $task->get('id') }}"><pre>{{ ($task->get('description')) ?? 'N/A' }}</pre></div>
                    
                    <div class="task-footer">
                        <div class="task-footer-date">{{ (new Carbon($task->get('created_at')))->diffForHumans() }}</div>

                        <div class="task-footer-due" id="task-item-due-{{ $task->get('id') }}">
                            @if ($task->get('due_date') !== null)
                                <span class="{{ ((new DateTime($task->get('due_date'))) < (new DateTime())) ? 'is-task-overdue' : '' }}">{{ date('Y-m-d', strtotime($task->get('due_date'))) }}</span>
                            @endif
                        </div>
                        
                        <div class="task-footer-action">
                            <input type="radio" onclick="window.vue.toggleTaskStatus({{ $task->get('id') }});" {{ ($task->get('done')) ? 'checked' : '' }} /><a href="javascript:void(0);" onclick="window.vue.toggleTaskStatus({{ $task->get('id') }});">&nbsp;{{ __('app.done') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @if ((!isset($_GET['done'])) || ($_GET['done'] == 0))
            <div class="tasks-all-done">
                <div class="tasks-all-done-image">
                    <img src="{{ asset('img/partypopper.png') }}" alt="image"/>
                </div>

                <div class="tasks-all-done-text">{{ __('app.all_tasks_done') }}</div>
            </div>
            @endif
        @endif
    </div>
@endif
