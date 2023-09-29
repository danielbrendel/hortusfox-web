<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.plants') }}</h1>

			<div>
                <a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = {{ $location }}; window.vue.bShowAddPlant = true;">{{ __('app.add_plant') }}</a>
            </div>

			<div class="plants">
				@foreach ($plants as $plant)
                    <div class="plant-card" style="background-image: url('{{ $plant->get('photo') }}');">
						<div class="plant-card-title">{{ $plant->get('name') }}</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="column is-2"></div>
</div>