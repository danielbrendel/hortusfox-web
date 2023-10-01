<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.profile') }}</h1>

            <div class="margin-vertical is-default-text-color">{{ __('app.profile_hint', ['name' => $user->get('name'), 'email' => $user->get('email')]) }}</div>

			<div class="plants">
                <h2 class="smaller-headline">{{ __('app.last_authored_plants') }}</h2>

				@foreach ($plants as $plant)
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