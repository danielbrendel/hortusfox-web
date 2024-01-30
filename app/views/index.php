<h1>{{ __('app.dashboard') }}</h1>

<h2>{{ __('app.welcome_message', ['name' => $user->get('name')]) }}</h2>

<div class="stats">
	<div class="stats-item">
		<div class="stats-item-count">{{ $stats['plants'] }}</div>
		<div class="stats-item-label">{{ __('app.plants') }}</div>
	</div>

	<div class="stats-item">
		<div class="stats-item-count">{{ $stats['locations'] }}</div>
		<div class="stats-item-label">{{ __('app.locations') }}</div>
	</div>

	<div class="stats-item">
		<div class="stats-item-count">{{ $stats['tasks'] }}</div>
		<div class="stats-item-label">{{ __('app.tasks') }}</div>
	</div>

	<div class="stats-item">
		<div class="stats-item-count">{{ $stats['users'] }}</div>
		<div class="stats-item-label">{{ __('app.users') }}</div>
	</div>
</div>

@if (count($warning_plants) > 0)
	<div class="warning-plants has-warnings">
		<div class="warning-plants-title">{{ __('app.warning_plants_title') }}</div>

		<div class="warning-plants-content">
			@foreach ($warning_plants as $plant)
				<div class="warning-plants-item">{{ $plant->get('name') }} | <strong class="plant-state-{{ $plant->get('health_state') }}">{{ __('app.' . $plant->get('health_state')) }}</strong> | {{ (new Carbon($plant->get('last_edited_date')))->diffForHumans() }} | <a class="is-yellow-link" href="{{ url('/plants/details/' . $plant->get('id')) }}">{{ __('app.view_plant_details') }}</a></div>
			@endforeach
		</div>
	</div>
@else
	<div class="warning-plants is-all-ok">
		<div class="warning-plants-title warning-plants-title-margin-top-25 warning-plants-title-centered"><i class="far fa-check-circle is-color-yes"></i>&nbsp;{{ __('app.warning_plants_all_ok') }}</div>
	</div>
@endif

@if (count($overdue_tasks) > 0)
<div class="overdue-tasks">
	<div class="overdue-tasks-title">{{ __('app.overdue_tasks') }}</div>

	<div class="overdue-tasks-content">
		@foreach ($overdue_tasks as $overdue_task)
			<div class="overdue-tasks-item">{{ $overdue_task->get('title') }} | {{ date('Y-m-d', strtotime($overdue_task->get('due_date'))) }} | <a class="is-yellow-link" href="{{ url('/tasks#task-anchor-' . $overdue_task->get('id')) }}">{{ __('app.view_task_details') }}</a></div>
		@endforeach
	</div>
</div>
@endif

<div class="locations">
	@foreach ($locations as $location)
		<a href="{{ url('/plants/location/' . $location->get('id')) }}">
			<div class="location">
				<div class="location-title">
					{{ $location->get('name') }}
				</div>

				<div class="location-icon">
					<i class="{{ $location->get('icon') }}"></i>
				</div>
			</div>
		</a>
	@endforeach
</div>

@if (count($upcoming_tasks_overview) > 0)
<div class="upcoming-tasks-overview">
	<h3>{{ __('app.upcoming_tasks_overview') }}</h3>

	<div class="upcoming-tasks-overview-items">
		@foreach ($upcoming_tasks_overview as $task)
			<div class="task" id="task-item-{{ $task->get('id') }}">
				<a name="task-anchor-{{ $task->get('id') }}"></a>

				<div class="task-header">
					<div class="task-header-title" id="task-item-title-{{ $task->get('id') }}">{{ $task->get('title') }}</div>
					<div class="task-header-action"><a href="javascript:void(0);" onclick="window.vue.editTask({{ $task->get('id') }});"><i class="fas fa-edit"></i></a></div>
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
	</div>

	<div class="upcoming-tasks-overview-action">
		<a class="button is-link" href="{{ url('/tasks') }}">{{ __('app.view_more') }}</a>
	</div>
</div>
@endif

<div class="last-added-or-authored-plants">
	<h3>
		@if ($user->get('show_plants_aoru'))
			{{ __('app.last_added_plants') }}
		@else
			{{ __('app.last_authored_plants') }}
		@endif
	</h3>

	@if (count($last_plants_list) > 0)
	<div class="plants">
		@foreach ($last_plants_list as $plant)
			<a href="{{ url('/plants/details/' . $plant->get('id')) }}">
				<div class="plant-card" style="background-image: url('{{ asset('img/' . $plant->get('photo')) }}');">
					<div class="plant-card-overlay">
						<div class="plant-card-health-state">
							@if ($plant->get('health_state') === 'overwatered')
								<i class="fas fa-water plant-state-overwatered"></i>
							@elseif ($plant->get('health_state') === 'withering')
								<i class="fab fa-pagelines plant-state-withering"></i>
							@elseif ($plant->get('health_state') === 'infected')
								<i class="fas fa-biohazard plant-state-infected"></i>
							@endif
						</div>

						<div class="plant-card-title">{{ $plant->get('name') }}</div>
					</div>
				</div>
			</a>
		@endforeach
		</div>
	@else
	<div class="plants-empty">
		<div class="plants-empty-image">
			<img src="{{ asset('img/plantsempty.png') }}" alt="image"/>
		</div>

		<div class="plants-empty-text">{{ __('app.content_empty') }}</div>
	</div>
	@endif
</div>

@if ($user->get('show_log'))
	@if (count($log) > 0)
		<div class="log">
			<div class="log-title">{{ __('app.log_title') }}</div>

			<div class="log-content">
				@foreach ($log as $entry)
					<div class="log-item">[{{ $entry['date'] }}] ({{ $entry['user'] }}) {{ $entry['property'] }} =&gt; {{ $entry['value'] }} @ {{ $entry['target'] }}</div>
				@endforeach
			</div>
		</div>
	@endif
@endif
