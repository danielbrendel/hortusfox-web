<!doctype html>
<html lang="{{ getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-with, initial-scale=1.0">
		
		<title>{{ __('app.workspace_title', ['name' => env('APP_WORKSPACE')]) }}</title>

		<link rel="icon" type="image/png" href="{{ asset('logo.png') }}"/>
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
			@include('navbar.php')

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

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.location') }}</label>
								<div class="control">
									<select name="location" class="input" id="inpLocationId">
										@foreach (LocationsModel::getAll() as $location)
											<option value="{{ $location->get('id') }}">{{ $location->get('name') }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.photo') }}</label>
								<div class="control">
									<input type="file" class="input" name="photo" required>
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="perennial" value="1">&nbsp;{{ __('app.perennial') }}
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
									<select name="light_level" class="input">
										<option value="">{{ __('app.select_light_level') }}</option>
										<option value="light_level_sunny">{{ __('app.light_level_sunny') }}</option>
										<option value="light_level_half_shade">{{ __('app.light_level_half_shade') }}</option>
										<option value="light_level_full_shade">{{ __('app.light_level_full_shade') }}</option>
									</select>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmAddPlant').submit();">{{ __('app.add') }}</button>
						<button class="button" onclick="window.vue.bShowAddPlant = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditText}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditText = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditText" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditTextPlantId"/>
							<input type="hidden" name="attribute" id="inpEditTextAttribute"/>
							<input type="hidden" name="anchor" id="inpEditTextAnchor"/>

							<div class="field">
								<div class="control">
									<input type="text" class="input" name="value" id="inpEditTextValue" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditText').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditText = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditBoolean}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditBoolean = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditBoolean" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditBooleanPlantId"/>
							<input type="hidden" name="attribute" id="inpEditBooleanAttribute"/>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="value" id="inpEditBooleanValue" value="1">&nbsp;<span id="property-hint"></span>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditBoolean').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditBoolean = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditInteger}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditInteger = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditInteger" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditIntegerPlantId"/>
							<input type="hidden" name="attribute" id="inpEditIntegerAttribute"/>

							<div class="field">
								<div class="control">
									<input type="number" class="input" name="value" id="inpEditIntegerValue" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditInteger').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditInteger = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditDate}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditDate = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditDate" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditDatePlantId"/>
							<input type="hidden" name="attribute" id="inpEditDateAttribute"/>

							<div class="field">
								<div class="control">
									<input type="date" class="input" name="value" id="inpEditDateValue" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditDate').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditDate = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditCombo}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditCombo = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditCombo" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditComboPlantId"/>
							<input type="hidden" name="attribute" id="inpEditComboAttribute"/>

							<div class="field">
								<div class="control">
									<select class="input" name="value" id="selEditCombo"></select>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditCombo').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditCombo = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditLinkText}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditLinkText = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditLinkText" method="POST" action="{{ url('/plants/details/edit/link') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditLinkTextPlantId"/>

							<div class="field">
								<label class="label">{{ __('app.text') }}</label>
								<div class="control">
									<input type="text" class="input" name="text" id="inpEditLinkTextValue" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.link') }}</label>
								<div class="control">
									<input type="text" class="input" name="link" id="inpEditLinkTextLink" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditLinkText').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditLinkText = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditPhoto}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditPhoto = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditPhoto" method="POST" action="{{ url('/plants/details/edit/photo') }}" enctype="multipart/form-data">
							@csrf

							<input type="hidden" name="plant" id="inpEditPhotoPlantId"/>
							<input type="hidden" name="attribute" id="inpEditPhotoAttribute"/>

							<div class="field">
								<div class="control">
									<input type="file" class="input" name="value" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditPhoto').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditPhoto = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowUploadPhoto}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.upload_photo') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowUploadPhoto = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmUploadPhoto" method="POST" action="{{ url('/plants/details/gallery/add') }}" enctype="multipart/form-data">
							@csrf

							<input type="hidden" name="plant" id="inpUploadPhotoPlantId"/>

							<div class="field">
								<div class="control">
									<input type="file" class="input" name="photo" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.label') }}</label>
								<div class="control">
									<input type="text" class="input" name="label" required>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmUploadPhoto').submit();">{{ __('app.upload') }}</button>
						<button class="button" onclick="window.vue.bShowUploadPhoto = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowCreateTask}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.create_task') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowCreateTask = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmCreateTask" method="POST" action="{{ url('/tasks/create') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.title') }}</label>
								<div class="control">
									<input type="text" class="input" name="title" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.description') }}</label>
								<div class="control">
									<textarea name="description" class="textarea"></textarea>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmCreateTask').submit();">{{ __('app.create_task') }}</button>
						<button class="button" onclick="window.vue.bShowCreateTask = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditTask}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_task') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditTask = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditTask" method="POST" action="{{ url('/tasks/edit') }}">
							@csrf

							<input type="hidden" name="task" id="inpEditTaskId"/>

							<div class="field">
								<label class="label">{{ __('app.title') }}</label>
								<div class="control">
									<input type="text" class="input" name="title" id="inpEditTaskTitle" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.description') }}</label>
								<div class="control">
									<textarea name="description" class="textarea" id="inpEditTaskDescription"></textarea>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditTask').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditTask = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditPreferences}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_preferences') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditPreferences = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditPreferences" method="POST" action="{{ url('/profile/preferences') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.language') }}</label>
								<div class="control">
									<select class="input" name="lang" id="selEditCombo">
										@foreach (UtilsModule::getLanguageList() as $lang)
											<option value="{{ $lang }}" {{ ($user->get('lang') === $lang) ? 'selected' : ''}}>{{ $lang }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="show_log" value="1" {{ ($user->get('show_log')) ? 'checked' : ''}}>&nbsp;{{ __('app.show_log') }}
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditPreferences').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditPreferences = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>
		</div>

		<script src="{{ asset('js/app.js') }}"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function(){
				@foreach (LocationsModel::getAll() as $location)
				window.vue.comboLocation.push({ ident: {{ $location->get('id') }}, label: '{{ $location->get('name') }}'});
				@endforeach

				window.vue.comboCuttingMonth.push({ ident: '#null', label: 'N/A'});
				@foreach (UtilsModule::GetMonthList() as $key => $value)
				window.vue.comboCuttingMonth.push({ ident: {{ $key }}, label: '{{ $value }}'});
				@endforeach
				
				window.vue.comboLightLevel.push({ ident: 'light_level_sunny', label: '{{ __('app.light_level_sunny') }}'});
				window.vue.comboLightLevel.push({ ident: 'light_level_half_shade', label: '{{ __('app.light_level_half_shade') }}'});
				window.vue.comboLightLevel.push({ ident: 'light_level_full_shade', label: '{{ __('app.light_level_full_shade') }}'});
				window.vue.comboHealthState.push({ ident: 'in_good_standing', label: '{{ __('app.in_good_standing') }}'});
				window.vue.comboHealthState.push({ ident: 'overwatered', label: '{{ __('app.overwatered') }}'});
				window.vue.comboHealthState.push({ ident: 'withering', label: '{{ __('app.withering') }}'});
				window.vue.comboHealthState.push({ ident: 'infected', label: '{{ __('app.infected') }}'});

				window.vue.confirmPhotoRemoval = '{{ __('app.confirmPhotoRemoval') }}';
				window.vue.confirmPlantRemoval = '{{ __('app.confirmPlantRemoval') }}';

				window.vue.initNavBar();

				window.currentOpenTaskCount = {{ TasksModel::getOpenTaskCount() }};
			});
		</script>
	</body>
</html>