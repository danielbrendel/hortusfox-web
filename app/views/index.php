<div class="dashboard-header">
	<div class="dashboard-welcome">
		<h1>{{ __('app.dashboard') }}</h1>

		<h2>{{ __('app.welcome_message', ['name' => $user->get('name')]) }}</h2>
	</div>

	@if (($weather) && (is_object($weather)))
	<div class="dashboard-weather">
		<div class="dashboard-weather-content">
			<div class="dashboard-weather-left">
				<img src="{{ WeatherModule::WEATHER_ICON_ENDPOINT }}/wn/{{ $weather->weather[0]->icon }}@2x.png" alt="icon"/>
			</div>

			<div class="dashboard-weather-right">
				<div>{{ $weather->name }}</div>
				<div><i class="fas fa-thermometer-half"></i> {{ round($weather->main->temp) . '°' . WeatherModule::getUnitChar(app('owm_unittype')) }} &bull; <i class="fas fa-tint"></i> {{ $weather->main->humidity . '%' }} &bull; <i class="fas fa-wind"></i> {{ round($weather->wind->speed) . 'm/s' }}</div>
			</div>
		</div>
	</div>
	@endif
</div>

<div class="stats">
	<div class="stats-item is-pointer" onclick="location.href = '{{ url('/#last-added-or-authored-plants') }}';">
		<div class="stats-item-count">{{ $stats['plants'] }}</div>
		<div class="stats-item-label">{{ __('app.plants') }}</div>
	</div>

	<div class="stats-item is-pointer" onclick="location.href = '{{ url('/#locations') }}';">
		<div class="stats-item-count">{{ $stats['locations'] }}</div>
		<div class="stats-item-label">{{ __('app.locations') }}</div>
	</div>

	<div class="stats-item is-pointer" onclick="location.href = '{{ url('/tasks') }}';">
		<div class="stats-item-count">{{ $stats['tasks'] }}</div>
		<div class="stats-item-label">{{ __('app.tasks') }}</div>
	</div>

	<div class="stats-item is-pointer" onclick="location.href = '{{ (($user->get('admin')) ? url('/admin?tab=users') : url('/profile')) }}';">
		<div class="stats-item-count">{{ $stats['users'] }}</div>
		<div class="stats-item-label">{{ __('app.users') }}</div>
	</div>
</div>

<div class="line-up-frames">
	@if (count($warning_plants) > 0)
		<div class="warning-plants has-warnings">
			<div class="warning-plants-title has-warnings">{{ __('app.warning_plants_title') }}</div>

			<div class="warning-plants-content">
				<table>
					<thead>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php $table_counter = 0; ?>
						@foreach ($warning_plants as $plant)
							<tr class="{{ ($table_counter % 2 === 0) ? 'table-bright-color' : '' }}">
								<td><a class="is-yellow-link" href="{{ url('/plants/details/' . $plant->get('id')) }}">{{ (strlen($plant->get('name')) > 20) ? substr($plant->get('name'), 0, 20) . '...' : $plant->get('name') }}</a></td>
								<td><strong class="plant-state-{{ $plant->get('health_state') }}">{{ __('app.' . $plant->get('health_state')) }}</strong></td>
								<td>{{ (new Carbon($plant->get('last_edited_date')))->diffForHumans() }}</td>
							</tr>

							<?php $table_counter++; ?>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@else
		<div class="warning-plants">
			<div class="warning-plants-title warning-plants-title-centered"><i class="far fa-check-circle is-color-yes"></i>&nbsp;{{ __('app.warning_plants_all_ok') }}</div>
		</div>
	@endif

	@if (count($overdue_tasks) > 0)
		<div class="overdue-tasks">
			<div class="overdue-tasks-title">{{ __('app.overdue_tasks') }}</div>

			<div class="overdue-tasks-content">
				<table>
					<thead>
						<tr>
							<td></td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php $table_counter = 0; ?>
						@foreach ($overdue_tasks as $overdue_task)
							<tr class="{{ ($table_counter % 2 === 0) ? 'table-bright-color' : '' }}">
								<td><a class="is-yellow-link" href="{{ url('/tasks#task-anchor-' . $overdue_task->get('id')) }}">{{ $overdue_task->get('title') }}</a></td>
								<td>{{ date('Y-m-d', strtotime($overdue_task->get('due_date'))) }}</td>
							</tr>

							<?php $table_counter++; ?>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
</div>

@if ($user->get('show_calendar_view'))
<div class="calendar-view">
	<h3>{{ __('app.calendar_overview') }}</h3>

	<canvas id="calendar-small-view"></canvas>
</div>
@endif

<div class="locations">
	<a name="locations"></a>

	@foreach ($locations as $location)
		<a href="{{ url('/plants/location/' . $location->get('id')) }}">
			<div class="location">
				<div class="location-title">
					{{ $location->get('name') }}
				</div>

				<div class="location-icon">
					<i class="{{ $location->get('icon') }}"></i>
				</div>

				<div class="location-footer">
					<div class="is-inline-block">
						<?php $plant_count = PlantsModel::getPlantCount($location->get('id')); ?>
						<span class="location-footer-count-desktop"><i class="fas fa-seedling is-color-ok"></i>&nbsp;{{ __('app.plant_count', ['count' => $plant_count]) }} &nbsp;</span>
						<span class="location-footer-count-mobile"><i class="fas fa-seedling is-color-ok"></i>&nbsp;{{ $plant_count }} &nbsp;</span>
					</div>

					<div class="is-inline-block">
						<?php $danger_count = PlantsModel::getDangerCount($location->get('id')); ?>

						<span class="location-footer-count-desktop">
							@if ($danger_count > 0)
								<i class="fas fa-biohazard is-color-danger"></i>&nbsp;{{ __('app.danger_count', ['count' => $danger_count]) }}
							@else
								<i class="far fa-check-circle is-color-ok"></i>&nbsp;{{ __('app.all_in_good_standing') }}
							@endif
						</span>

						<span class="location-footer-count-mobile">
							@if ($danger_count > 0)
								<i class="fas fa-biohazard is-color-danger"></i>&nbsp;{{ $danger_count }}
							@else
								<i class="far fa-check-circle is-color-ok"></i>&nbsp;{{ __('app.all_in_good_standing') }}
							@endif
						</span>
					</div>
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
	<a name="last-added-or-authored-plants"></a>

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
							@if ($plant->get('health_state') !== 'in_good_standing')
								<i class="{{ PlantsModel::$plant_health_states[$plant->get('health_state')]['icon'] }} plant-state-{{ $plant->get('health_state') }}"></i>
							@endif
						</div>

						<div class="plant-card-title {{ ((strlen($plant->get('name')) > PlantsModel::PLANT_LONG_TEXT_THRESHOLD) ? 'plant-card-title-longtext' : '') }}">{{ $plant->get('name') . ((!is_null($plant->get('clone_num'))) ? ' (' . strval($plant->get('clone_num') + 1) . ')' : '') }}</div>
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
					<div class="log-item">
						@if ($entry['link'])
							<a href="{{ $entry['link'] }}">[{{ $entry['date'] }}] ({{ $entry['user'] }}) {{ $entry['property'] }} =&gt; {{ $entry['value'] }} @ {{ $entry['target'] }}</a>
						@else
							[{{ $entry['date'] }}] ({{ $entry['user'] }}) {{ $entry['property'] }} =&gt; {{ $entry['value'] }} @ {{ $entry['target'] }}
						@endif
					</div>
				@endforeach
			</div>
		</div>
	@endif
@endif
