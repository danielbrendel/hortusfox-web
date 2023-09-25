<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.locations') }}</h1>

			<h2>{{ __('app.select_location_to_proceed') }}</h2>

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
		</div>
	</div>

	<div class="column is-2"></div>
</div>