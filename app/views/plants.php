<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.plants') }}</h1>

			<div>
                <a class="button is-success" href="javascript:void(0);">{{ __('app.add_plant') }}</a>
            </div>

			<div class="plants">
				@foreach ($plants as $plant)
                    {{ $plant->get('name') }}
				@endforeach
			</div>
		</div>
	</div>

	<div class="column is-2"></div>
</div>