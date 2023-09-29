<!doctype html>
<html lang="{{ getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-with, initial-scale=1.0">
		
		<title>Plant Manager App</title>

		<link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

		@if (env('APP_DEBUG'))
		<script src="{{ asset('js/vue.js') }}"></script>
		@else
		<script src="{{ asset('js/vue.min.js') }}"></script>
		@endif
		<script src="{{ asset('js/fontawesome.js') }}"></script>
	</head>
	
	<body>
		<div id="app">
			<div class="container">
				{%content%}
			</div>

			<div class="modal" :class="{'is-active': bShowAddPlant}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_plant') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowAddPlant = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmAddPlant" method="POST" action="{{ url('/plants/add') }}" enctype="multipart/form-data">
							@csrf

							<input type="hidden" name="location" id="inpLocationId"/>

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.photo') }}</label>
								<div class="control">
									<input type="file" name="photo" required>
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="perennial" value="1">&nbsp;{{ __('app.perennial') }}
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.cutting_month') }}</label>
								<div class="control">
									<select name="cutting_month">
										<option value="">{{ __('app.select_month') }}</option>
										@foreach (UtilsModule::getMonthList() as $key => $month)
											<option value="{{ $key + 1 }}">{{ $month }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.date_of_purchase') }}</label>
								<div class="control">
									<input type="date" name="date_of_purchase" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.humidity') }}</label>
								<div class="control">
									<input type="number" min="0" max="100" class="input" name="humidity" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.light_level') }}</label>
								<div class="control">
									<input type="text" class="input" name="light_level" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="document.getElementById('frmAddPlant').submit();">{{ __('app.add') }}</button>
						<button class="button" onclick="window.vue.bShowAddPlant = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>
		</div>

		<script src="{{ asset('js/app.js') }}"></script>
	</body>
</html>