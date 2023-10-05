<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ $location_name }}</h1>

			<div class="margin-vertical">
                <div class="is-inline-block is-action-button-margin"><a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = {{ $location }}; window.vue.bShowAddPlant = true;">{{ __('app.add_plant') }}</a></div>
				<div class="is-inline-block is-action-button-margin"><a class="button is-link" href="javascript:void(0);" onclick="window.vue.updateLastWatered({{ $location }});">{{ __('app.set_watered') }}</a></div>
				<div class="is-inline-block is-action-button-margin"><a class="is-default-link is-fixed-button-link" href="{{ url('/') }}">{{ __('app.back_to_dashboard') }}</a></div>
            </div>

			@include('flashmsg.php')

			<div class="plants">
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
		</div>
	</div>

	<div class="column is-2"></div>
</div>