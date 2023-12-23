<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/background.jpg') }}');">
		<div class="column-overlay">
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

			<div class="last-added-plants">
				<h3>{{ __('app.last_added_plants') }}</h3>

				@if (count($last_added_plants) > 0)
				<div class="plants">
					@foreach ($last_added_plants as $plant)
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
		</div>
	</div>

	<div class="column is-2"></div>
</div>