<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.dashboard') }}</h1>

			<h2>{{ __('app.welcome_message', ['name' => $user->get('name')]) }}</h2>

			
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
					<div class="warning-plants-title warning-plants-title-no-margin-bottom"><i class="far fa-check-circle is-color-yes"></i>&nbsp;{{ __('app.warning_plants_all_ok') }}</div>
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
								<i class="{{ $location->get('icon') }} fa-8x"></i>
							</div>
						</div>
					</a>
				@endforeach
			</div>

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
		</div>
	</div>

	<div class="column is-2"></div>
</div>