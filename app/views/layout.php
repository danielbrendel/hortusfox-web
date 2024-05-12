<!doctype html>
<html lang="{{ getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>{{ app('workspace') }}</title>

		<link rel="icon" type="image/png" href="{{ asset('logo.png') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

		@if ((ThemeModule::ready()) && (ThemeModule::data()->include))
		<link rel="stylesheet" type="text/css" href="{{ ThemeModule::data()->include }}"/>
		@endif

		<link rel="manifest" href="{{ asset('manifest.json') }}"/>

		@if (env('APP_DEBUG'))
		<script src="{{ asset('js/vue.js') }}"></script>
		@else
		<script src="{{ asset('js/vue.min.js') }}"></script>
		@endif
		<script src="{{ asset('js/fontawesome.js') }}"></script>
	</head>
	
	<body>
		<div id="app" class="{{ ((app('pwa_enable')) ? 'app-padding-pwa' : '') }}">
			<div id="scroller-top"></div>

			@include('navbar.php')

			@if (ThemeModule::ready())
				@include('theme.php')
			@else
				@include('banner.php')
			@endif

			<div id="small-system-messages"></div>

			<div class="container">
				<div class="columns">
					<div class="column is-1"></div>

					<div class="column is-10">
						<div class="content-inner">
							{%content%}
						</div>
					</div>

					<div class="column is-1"></div>
				</div>
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
									<select name="location" class="input" id="inpLocationId" required>
										@foreach (LocationsModel::getAll() as $loc)
											<option value="{{ $loc->get('id') }}">{{ $loc->get('name') }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.photo') }}</label>
								<div class="control">
									<input type="file" class="input" name="photo" accept="image/*">
								</div>
							</div>

							<input type="submit" class="is-hidden" id="submit-add-plant">
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-add-plant" onclick="document.getElementById('frmAddPlant').addEventListener('submit', function() { document.getElementById('button-add-plant').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; }); document.getElementById('submit-add-plant').click();">{{ __('app.add') }}</button>
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

			<div class="modal" :class="{'is-active': bShowEditMultilineText}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_property') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditMultilineText = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditMultilineText" method="POST" action="{{ url('/plants/details/edit') }}">
							@csrf

							<input type="hidden" name="plant" id="inpEditMultilineTextPlantId"/>
							<input type="hidden" name="attribute" id="inpEditMultilineTextAttribute"/>
							<input type="hidden" name="anchor" id="inpEditMultilineTextAnchor"/>

							<div class="field">
								<div class="control">
									<textarea class="input" name="value" id="inpEditMultilineTextValue" required></textarea>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditMultilineText').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditMultilineText = false;">{{ __('app.cancel') }}</button>
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

							<fieldset>
								<legend id="property-hint" class="legend-title"></legend>

								<div class="field">
									<div class="control">
										<input type="radio" name="value" id="inpEditBooleanValue_yes" value="1">
										<label for="inpEditBooleanValue_yes">{{ __('app.yes') }}</label>
									</div>
								</div>

								<div class="field">
									<div class="control">
										<input type="radio" name="value" id="inpEditBooleanValue_no" value="0">
										<label for="inpEditBooleanValue_no">{{ __('app.no') }}</label>
									</div>
								</div>
							</fieldset>
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
									<input type="file" class="input" name="value" accept="image/*" required>
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
									<input type="file" class="input" name="photo" accept="image/*" required>
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

							<div class="field">
								<label class="label">{{ __('app.due') }}</label>
								<div class="control">
									<input type="date" class="input" name="due_date">
								</div>
							</div>

							<input type="submit" id="submit-create-task" class="is-hidden"/>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-create-task-item" onclick="document.getElementById('frmCreateTask').addEventListener('submit', function() { document.getElementById('button-create-task-item').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; });  document.getElementById('submit-create-task').click();">{{ __('app.create_task') }}</button>
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

							<div class="field">
								<label class="label">{{ __('app.due') }}</label>
								<div class="control">
									<input type="date" class="input" name="due_date" id="inpEditTaskDueDate">
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

			<div class="modal" :class="{'is-active': bShowAddInventoryItem}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_inventory_item') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowAddInventoryItem = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmAddInventoryItem" method="POST" action="{{ url('/inventory/add') }}" enctype="multipart/form-data">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.group') }}</label>
								<div class="control">
									<select name="group" class="input" required>
										@foreach (InvGroupModel::getAll() as $group_item)
											<option value="{{ $group_item->get('token') }}">{{ $group_item->get('label') }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.photo') }}</label>
								<div class="control">
									<input type="file" class="input" name="photo" accept="image/*">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.location') }}</label>
								<div class="control">
									<input type="text" class="input" name="location">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.description') }}</label>
								<div class="control">
									<textarea class="textarea" name="description"></textarea>
								</div>
							</div>

							<input type="submit" class="is-hidden" id="submit-add-inventory"/>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-add-inventory-item" onclick="document.getElementById('frmAddInventoryItem').addEventListener('submit', function() { document.getElementById('button-add-inventory-item').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; }); document.getElementById('submit-add-inventory').click();">{{ __('app.add') }}</button>
						<button class="button" onclick="window.vue.bShowAddInventoryItem = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditInventoryItem}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_inventory_item') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditInventoryItem = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditInventoryItem" method="POST" action="{{ url('/inventory/edit') }}" enctype="multipart/form-data">
							@csrf

							<input type="hidden" name="id" id="inpInventoryItemId"/>

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" id="inpInventoryItemName" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.group') }}</label>
								<div class="control">
									<select name="group" class="input" id="inpInventoryItemGroup">
										@foreach (InvGroupModel::getAll() as $group_item)
											<option value="{{ $group_item->get('token') }}">{{ $group_item->get('label') }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.photo') }}</label>
								<div class="control">
									<input type="file" class="input" name="photo" accept="image/*" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.location') }}</label>
								<div class="control">
									<input type="text" class="input" name="location" id="inpInventoryItemLocation" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.description') }}</label>
								<div class="control">
									<textarea class="textarea" name="description" id="inpInventoryItemDescription"></textarea>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditInventoryItem').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditInventoryItem = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowManageGroups}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.manage_groups') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowManageGroups = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<table class="table inventory-groups">
							<thead>
								<tr>
									<td>{{ __('app.token') }}</td>
									<td>{{ __('app.label') }}</td>
									<td></td>
								</tr>
							</thead>

							<tbody>
								@foreach (InvGroupModel::getAll() as $group_item)
									<tr id="inventory-group-item-{{ $group_item->get('id') }}">
										<td><a href="javascript:void(0);" id="inventory-group-elem-token-{{ $group_item->get('id') }}" onclick="window.vue.editInventoryGroupItem({{ $group_item->get('id') }}, 'token', '{{ $group_item->get('token') }}');">{{ $group_item->get('token') }}</a></td>
										<td><a href="javascript:void(0);" id="inventory-group-elem-label-{{ $group_item->get('id') }}" onclick="window.vue.editInventoryGroupItem({{ $group_item->get('id') }}, 'label', '{{ $group_item->get('label') }}');">{{ $group_item->get('label') }}</a></td>
										<td><a href="javascript:void(0);" onclick="window.vue.removeInventoryGroupItem({{ $group_item->get('id') }}, 'inventory-group-item-{{ $group_item->get('id') }}');"><i class="fas fa-times"></i></a></td>
									</tr>
								@endforeach
							</tbody>
						</table>

						<div><hr/></div>

						<form id="frmAddInventoryGroup" method="POST" action="{{ url('/inventory/group/add') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.token') }}</label>
								<div class="control">
									<input type="text" class="input" name="token" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.label') }}</label>
								<div class="control">
									<input type="text" class="input" name="label" required>
								</div>
							</div>

							<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmAddInventoryGroup').submit();">{{ __('app.add') }}</button>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button" onclick="window.vue.bShowManageGroups = false;">{{ __('app.close') }}</button>
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
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" value="{{ $user->get('name') }}">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.email') }}</label>
								<div class="control">
									<input type="email" class="input" name="email" value="{{ $user->get('email') }}">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.password') }}</label>
								<div class="control">
									<input type="password" class="input" name="password">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.password_confirmation') }}</label>
								<div class="control">
									<input type="password" class="input" name="password_confirmation">
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.language') }}</label>
								<div class="control">
									<select class="input" name="lang" id="selEditCombo">
										@foreach (UtilsModule::getLanguageList() as $lang)
											<option value="{{ $lang['ident'] }}" {{ (UtilsModule::getLanguage() === $lang['ident']) ? 'selected' : ''}}>{{ $lang['name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.theme') }}</label>
								<div class="control">
									<select class="input" name="theme" id="selEditCombo">
										@foreach (ThemeModule::list() as $theme)
											<option value="{{ $theme }}" {{ ($user->get('theme') === $theme) ? 'selected' : ''}}>{{ $theme }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field {{ ((!app('chat_enable')) ? 'is-hidden': '') }}">
								<label class="label">{{ __('app.chatcolor') }}</label>
								<div class="control">
									<input type="color" class="input" name="chatcolor" value="{{ UserModel::getChatColorForUser($user->get('id')) }}">
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="show_log" value="1" {{ ($user->get('show_log')) ? 'checked' : ''}}>&nbsp;{{ __('app.show_log') }}
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="show_calendar_view" value="1" {{ ($user->get('show_calendar_view')) ? 'checked' : ''}}>&nbsp;{{ __('app.show_calendar_view') }}
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="show_plant_id" value="1" {{ ($user->get('show_plant_id')) ? 'checked' : ''}}>&nbsp;{{ __('app.show_plant_id') }}
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="notify_tasks_overdue" value="1" {{ ($user->get('notify_tasks_overdue')) ? 'checked' : ''}}>&nbsp;{{ __('app.notify_tasks_overdue') }}
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="notify_tasks_tomorrow" value="1" {{ ($user->get('notify_tasks_tomorrow')) ? 'checked' : ''}}>&nbsp;{{ __('app.notify_tasks_tomorrow') }}
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="notify_calendar_reminder" value="1" {{ ($user->get('notify_calendar_reminder')) ? 'checked' : ''}}>&nbsp;{{ __('app.notify_calendar_reminder') }}
								</div>
							</div>

							<fieldset>
								<legend>{{ __('app.last_added_or_updated_plants_hint') }}</legend>

								<div class="field">
									<div class="control">
										<input type="radio" name="show_plants_aoru" id="show_plants_aoru_added" value="1" {{ ($user->get('show_plants_aoru')) ? 'checked' : ''}}>
										<label for="show_plants_aoru_added">{{ __('app.show_plants_aoru_added') }}</label>
									</div>
								</div>

								<div class="field">
									<div class="control">
										<input type="radio" name="show_plants_aoru" id="show_plants_aoru_updated" value="0" {{ (!$user->get('show_plants_aoru')) ? 'checked' : ''}}>
										<label for="show_plants_aoru_updated">{{ __('app.show_plants_aoru_updated') }}</label>
									</div>
								</div>
							</fieldset>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditPreferences').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditPreferences = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowCreateNewUser}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.create_user') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowCreateNewUser = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmCreateNewUser" method="POST" action="{{ url('/admin/user/create') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.email') }}</label>
								<div class="control">
									<input type="email" class="input" name="email" required>
								</div>
							</div>

							<div class="field">
								<div class="control">
									<input type="checkbox" name="sendmail" value="1" checked>&nbsp;{{ __('app.send_confirmation_email') }}
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmCreateNewUser').submit();">{{ __('app.create') }}</button>
						<button class="button" onclick="window.vue.bShowCreateNewUser = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowCreateNewLocation}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_location') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowCreateNewLocation = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmCreateNewLocation" method="POST" action="{{ url('/admin/location/add') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.icon') }}</label>
								<div class="control has-icons-right">
									<input type="text" class="input" name="icon" value="fas fa-leaf" oninput="document.getElementById('create-new-location-icon-preview').className = this.value;" required>

									<span class="icon is-small is-right">
										<i class="fas fa-leaf" id="create-new-location-icon-preview"></i>
									</span>
								</div>
							</div>

							<p class="form-paragraph-modal">
								{!! __('app.search_for_location_icons', ['url' => 'https://fontawesome.com/v5/search?m=free']) !!}
							</p>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmCreateNewLocation').submit();">{{ __('app.add_location') }}</button>
						<button class="button" onclick="window.vue.bShowCreateNewLocation = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowRemoveLocation}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.remove_location') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowRemoveLocation = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmRemoveLocation" method="POST" action="{{ url('/admin/location/remove') }}">
							@csrf

							<input type="hidden" name="id" id="remove-location-id"/>

							<div class="field">
								<label class="label">{{ __('app.location_migration') }}</label>
								<div class="control">
									<select class="input" name="target" id="selRemoveLocation">
										<option value="">-</option>
										@foreach ($locations as $location)
											<option class="remove-location-item-option" id="remove-location-item-{{ $location->get('id') }}" value="{{ $location->get('id') }}">{{ $location->get('name') }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmRemoveLocation').submit();">{{ __('app.remove') }}</button>
						<button class="button" onclick="window.vue.bShowRemoveLocation = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowPreviewImageModal}">
				<div class="modal-background"></div>

				<div class="modal-content">
					<p class="image">
						<img id="preview-image-modal-img" alt="image">
					</p>
				</div>

				<button class="modal-close is-large" aria-label="close" onclick="window.vue.bShowPreviewImageModal = false;"></button>
			</div>

			<div class="modal" :class="{'is-active': bShowSharePhoto}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.share_photo') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowSharePhoto = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<div class="field">
							<p>{{ __('app.share_photo_hint', ['url' => env('APP_SERVICE_URL')]) }}</p>
						</div>

						<div class="field">
							<label class="label">{{ __('app.share_photo_title') }}</label>
							<div class="control">
								<input type="text" class="input" id="share-photo-title">
							</div>
						</div>

						<div class="field">
							<p class="is-color-error is-hidden" id="share-photo-error"></p>
						</div>

						<input type="hidden" class="input" id="share-photo-id">
						<input type="hidden" class="input" id="share-photo-type">

						<div class="field has-addons is-stretched is-hidden" id="share-photo-result">
							<div class="control is-stretched">
								<input class="input is-background-success-light" type="text" id="share-photo-link">
							</div>
							<div class="control">
								<a class="button is-info" href="javascript:void(0);" onclick="window.vue.copyToClipboard(document.getElementById('share-photo-link').value, document.getElementById('share-photo-type').value);">{{ __('app.copy_to_clipboard') }}</a>
							</div>
						</div>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button id="share-photo-submit-action" class="button is-success" onclick="window.vue.performPhotoShare(document.getElementById('share-photo-id').value, document.getElementById('share-photo-title').value, document.getElementById('share-photo-type').value, document.getElementById('share-photo-link'), this, document.getElementById('share-photo-error'));">{{ __('app.share') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowAddFirstLocation}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_location') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowAddFirstLocation = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<p class="form-paragraph-modal">
							{!! __('app.create_your_first_location', ['url' => url('/admin?tab=locations')]) !!}
						</p>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button" onclick="window.vue.bShowAddFirstLocation = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowAddCalendarItem}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_calendar_item') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowAddCalendarItem = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmAddCalendarItem" method="POST" action="{{ url('/calendar/add') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.date_from') }}</label>
								<div class="control">
									<input type="date" class="input" name="date_from" onchange="document.getElementById('date-till').value = this.value;" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.date_till') }}</label>
								<div class="control">
									<input type="date" class="input" name="date_till" id="date-till" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.calendar_class') }}</label>
								<div class="control">
									<select name="class" class="input">
										@foreach (CalendarClassModel::getAll() as $class_item)
											<option value="{{ $class_item->get('ident') }}">{{ __($class_item->get('name')) }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<input type="submit" class="is-hidden" id="submit-add-calendar-item">
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-add-calendar-item" onclick="document.getElementById('frmAddCalendarItem').addEventListener('submit', function() { document.getElementById('button-add-calendar-item').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; }); document.getElementById('submit-add-calendar-item').click();">{{ __('app.add') }}</button>
						<button class="button" onclick="window.vue.bShowAddCalendarItem = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditCalendarItem}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_calendar_item') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditCalendarItem = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmEditCalendarItem" method="POST" action="{{ url('/calendar/edit') }}">
							@csrf

							<input type="hidden" name="ident" id="inpEditCalendarItemId"/>

							<div class="field">
								<label class="label">{{ __('app.name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" id="inpEditCalendarItemName" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.date_from') }}</label>
								<div class="control">
									<input type="date" class="input" name="date_from" id="inpEditCalendarItemDateFrom" onchange="document.getElementById('date-till').value = this.value;" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.date_till') }}</label>
								<div class="control">
									<input type="date" class="input" name="date_till" id="inpEditCalendarItemDateTill" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.calendar_class') }}</label>
								<div class="control">
									<select name="class" id="inpEditCalendarItemClass" class="input">
										@foreach (CalendarClassModel::getAll() as $class_item)
											<option value="{{ $class_item->get('ident') }}">{{ __($class_item->get('name')) }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<input type="submit" class="is-hidden" id="submit-edit-calendar-item">

							<div class="field">
								<div class="control">
									<a class="calendar-link-removal" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_calendar_item') }}')) { window.vue.removeCalendarItem(document.getElementById('inpEditCalendarItemId').value); }">{{ __('app.remove_calendar_item') }}</a>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-edit-calendar-item" onclick="document.getElementById('frmEditCalendarItem').addEventListener('submit', function() { document.getElementById('button-edit-calendar-item').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; }); document.getElementById('submit-edit-calendar-item').click();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditCalendarItem = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowCreateNewCalendarClass}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_calendar_class') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowCreateNewCalendarClass = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<form id="frmAddCalendarClass" method="POST" action="{{ url('/admin/calendar/class/add') }}">
							@csrf

							<div class="field">
								<label class="label">{{ __('app.calendar_class_ident') }}</label>
								<div class="control">
									<input type="text" class="input" name="ident" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.calendar_class_name') }}</label>
								<div class="control">
									<input type="text" class="input" name="name" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.calendar_class_color_background') }}</label>
								<div class="control">
									<input type="color" class="input" name="color_background" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.calendar_class_color_border') }}</label>
								<div class="control">
									<input type="color" class="input" name="color_border" required>
								</div>
							</div>

							<input type="submit" class="is-hidden" id="submit-add-calendar-class">
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" id="button-add-calendar-class" onclick="document.getElementById('frmAddCalendarClass').addEventListener('submit', function() { document.getElementById('button-add-calendar-class').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; return true; }); document.getElementById('submit-add-calendar-class').click();">{{ __('app.add') }}</button>
						<button class="button" onclick="window.vue.bShowCreateNewCalendarClass = false;">{{ __('app.cancel') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowPlantQRCode}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.qr_code') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowPlantQRCode = false;"></button>
					</header>
					<section class="modal-card-body is-stretched">
						<input type="hidden" id="title-plant-qr-code"/>

						<div class="field">
							<div class="control is-centered">
								<img src="" id="image-plant-qr-code" alt="QR Code"/>
							</div>
						</div>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="window.vue.printQRCode(document.getElementById('image-plant-qr-code').src);">{{ __('app.print') }}</button>
						<button class="button" onclick="window.vue.bShowPlantQRCode = false;">{{ __('app.close') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowPlantBulkPrint}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.bulk_qrcodes') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowPlantBulkPrint = false;"></button>
					</header>
					<section class="modal-card-body modal-anchors is-stretched">
						<div class="field">
							<div class="control">
								<a href="javascript:void(0);" onclick="window.vue.bulkChecked('plant-bulk-print-qrcode', true);">{{ __('app.select_all') }}</a>&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="window.vue.bulkChecked('plant-bulk-print-qrcode', false);">{{ __('app.unselect_all') }}</a>
							</div>
						</div>

						@if ((isset($plants)) && (is_countable($plants)) && (count($plants) > 0))
							@foreach ($plants as $plant_item)
								<div class="field">
									<div class="control">
										<input type="checkbox" class="plant-bulk-print-qrcode" data-plantid="{{ $plant_item->get('id') }}" data-plantname="{{ $plant_item->get('name') }}" value="1"/>&nbsp;{{ $plant_item->get('name') }}
									</div>
								</div>
							@endforeach
						@endif
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="window.vue.bulkPrintQRCodes('plant-bulk-print-qrcode', '{{ ((isset($location_name)) ? $location_name : '') }}');">{{ __('app.print') }}</button>
						<button class="button" onclick="window.vue.bShowPlantBulkPrint = false;">{{ __('app.close') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowAddCustomPlantAttribute}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.add_custom_attribute') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowAddCustomPlantAttribute = false;"></button>
					</header>
					<section class="modal-card-body modal-anchors is-stretched">
					<form id="frmAddCustomPlantAttribute" method="POST" action="{{ url('/plants/attributes/add') }}">
							@csrf

							<input type="hidden" name="plant" id="custom-plant-attribute-plant-id"/>

							<div class="field">
								<label class="label">{{ __('app.label') }}</label>
								<div class="control">
									<input type="text" class="input" name="label" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.datatype') }}</label>
								<div class="control">
									<select class="input" name="datatype" onchange="window.vue.selectDataTypeInputField(this, document.querySelector('#field-custom-add-attribute-content'));" onfocus="this.selectedIndex = -1;" required>
										<option value=""></option>
										@foreach (CustPlantAttrModel::$data_types as $datatype)
											<option value="{{ $datatype }}">{{ $datatype }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field is-hidden" id="field-custom-add-attribute-content">
								<label class="label">{{ __('app.content') }}</label>
								<div class="control">
									<div class="is-hidden">
										<fieldset>
											<legend class="legend-title"></legend>

											<div class="field">
												<div class="control">
													<input type="radio" name="content" id="field-custom-add-attribute-radiobutton-yes" value="1">
													<label for="field-custom-add-attribute-radiobutton-yes">{{ __('app.yes') }}</label>
												</div>
											</div>

											<div class="field">
												<div class="control">
													<input type="radio" name="content" id="field-custom-add-attribute-radiobutton-no" value="0">
													<label for="field-custom-add-attribute-radiobutton-no">{{ __('app.no') }}</label>
												</div>
											</div>
										</fieldset>
									</div>

									<input type="text" class="input is-hidden" name="content">
									<input type="date" class="input is-hidden" name="content">
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmAddCustomPlantAttribute').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowAddCustomPlantAttribute = false;">{{ __('app.close') }}</button>
					</footer>
				</div>
			</div>

			<div class="modal" :class="{'is-active': bShowEditCustomPlantAttribute}">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head is-stretched">
						<p class="modal-card-title">{{ __('app.edit_custom_attribute') }}</p>
						<button class="delete" aria-label="close" onclick="window.vue.bShowEditCustomPlantAttribute = false;"></button>
					</header>
					<section class="modal-card-body modal-anchors is-stretched">
					<form id="frmEditCustomPlantAttribute" method="POST" action="{{ url('/plants/attributes/edit') }}">
							@csrf

							<input type="hidden" name="id" id="edit-plant-attribute-attr"/>
							<input type="hidden" name="plant" id="edit-plant-attribute-plant"/>

							<div class="field">
								<label class="label">{{ __('app.label') }}</label>
								<div class="control">
									<input type="text" class="input" name="label" id="edit-plant-attribute-label" required>
								</div>
							</div>

							<div class="field">
								<label class="label">{{ __('app.datatype') }}</label>
								<div class="control">
									<select class="input" name="datatype" id="edit-plant-attribute-datatype" onchange="window.vue.selectDataTypeInputField(this, document.querySelector('#field-custom-edit-attribute-content'));" onfocus="this.selectedIndex = -1;" required>
										@foreach (CustPlantAttrModel::$data_types as $datatype)
											<option value="{{ $datatype }}">{{ $datatype }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="field is-hidden" id="field-custom-edit-attribute-content">
								<label class="label">{{ __('app.content') }}</label>
								<div class="control">
									<div class="is-hidden">
										<fieldset>
											<legend class="legend-title"></legend>

											<div class="field">
												<div class="control">
													<input type="radio" name="content" id="field-custom-edit-attribute-radiobutton-yes" value="1">
													<label for="field-custom-edit-attribute-radiobutton-yes">{{ __('app.yes') }}</label>
												</div>
											</div>

											<div class="field">
												<div class="control">
													<input type="radio" name="content" id="field-custom-edit-attribute-radiobutton-no" value="0">
													<label for="field-custom-edit-attribute-radiobutton-no">{{ __('app.no') }}</label>
												</div>
											</div>
										</fieldset>
									</div>	

									<input type="text" class="input is-hidden" name="content">
									<input type="date" class="input is-hidden" name="content">
								</div>
							</div>

							<div class="field">
								<div class="control">
									<a class="plant-custom-attribute-removal" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_custom_attribute') }}')) { window.vue.removeCustomPlantAttribute(document.getElementById('edit-plant-attribute-attr').value, 'table-custom-attribute-' + document.getElementById('edit-plant-attribute-attr').value); }">{{ __('app.remove_custom_attribute') }}</a>
								</div>
							</div>
						</form>
					</section>
					<footer class="modal-card-foot is-stretched">
						<button class="button is-success" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;{{ __('app.loading_please_wait') }}'; document.getElementById('frmEditCustomPlantAttribute').submit();">{{ __('app.save') }}</button>
						<button class="button" onclick="window.vue.bShowEditCustomPlantAttribute = false;">{{ __('app.close') }}</button>
					</footer>
				</div>
			</div>

			@include('scroller.php')

			@if (app('pwa_enable'))
				@include('bottomnav.php')
			@endif
		</div>

		<script src="{{ asset('js/app.js', true) }}"></script>
		<script>
			@if (app('pwa_enable'))
			window.onload = function() {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('./serviceworker.js', { scope: '/' })
                        .then(function(registration){
                            window.serviceWorkerEnabled = true;
                        }).catch(function(err){
                            window.serviceWorkerEnabled = false;
                            console.error(err);
                        });
                }
            };
			@endif

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
				window.vue.comboHealthState.push({ ident: 'in_good_standing', label: '{{ __(PlantsModel::$plant_health_states['in_good_standing']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'overwatered', label: '{{ __(PlantsModel::$plant_health_states['overwatered']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'withering', label: '{{ __(PlantsModel::$plant_health_states['withering']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'infected', label: '{{ __(PlantsModel::$plant_health_states['infected']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'pest_infestation', label: '{{ __(PlantsModel::$plant_health_states['pest_infestation']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'transplant_shock', label: '{{ __(PlantsModel::$plant_health_states['transplant_shock']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'nutritional_deficiency', label: '{{ __(PlantsModel::$plant_health_states['nutritional_deficiency']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'sunburn', label: '{{ __(PlantsModel::$plant_health_states['sunburn']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'frostbite', label: '{{ __(PlantsModel::$plant_health_states['frostbite']['localization']) }}'});
				window.vue.comboHealthState.push({ ident: 'root_rot', label: '{{ __(PlantsModel::$plant_health_states['root_rot']['localization']) }}'});

				window.vue.confirmPhotoRemoval = '{{ __('app.confirmPhotoRemoval') }}';
				window.vue.confirmPlantRemoval = '{{ __('app.confirmPlantRemoval') }}';
				window.vue.confirmPlantAddHistory = '{{ __('app.confirmPlantAddHistory') }}';
				window.vue.confirmPlantRemoveHistory = '{{ __('app.confirmPlantRemoveHistory') }}';
				window.vue.confirmSetAllWatered = '{{ __('app.confirmSetAllWatered') }}';
				window.vue.confirmSetAllRepotted = '{{ __('app.confirmSetAllRepotted') }}';
				window.vue.confirmSetAllFertilised = '{{ __('app.confirmSetAllFertilised') }}';
				window.vue.confirmInventoryItemRemoval = '{{ __('app.confirmInventoryItemRemoval') }}';
				window.vue.newChatMessage = '{{ __('app.new') }}';
				window.vue.currentlyOnline = '{{ __('app.currentlyOnline') }}';
				window.vue.loadingPleaseWait = '{{ __('app.loading_please_wait') }}';
				window.vue.noListItemsSelected = '{{ __('app.noListItemsSelected') }}';
				window.vue.editProperty = '{{ __('app.edit_property') }}';
				window.vue.copiedToClipboard = '{{ __('app.copied_to_clipboard') }}';

				window.vue.chatTypingEnable = {{ (app('chat_indicator', false)) ? 'true' : 'false' }};

				window.vue.initNavBar();

				@if (app('pwa_enable'))
					let scroller = document.querySelector('.scroll-to-top');
					if (scroller) {
						scroller.style.bottom = '83px';
					}
				@endif

				window.currentLocale = '{{ UtilsModule::getLanguage() }}';
				window.currentOpenTaskCount = {{ TasksModel::getOpenTaskCount() }};

				@if (isset($_action_query))
					document.getElementById('{{ $_action_query }}').click();
				@endif

				@if (isset($_expand_inventory_item))
					window.vue.expandInventoryItem('inventory-item-body-{{ $_expand_inventory_item }}');
				@endif

				window.calendarChart = null;
				let elCalendar = document.getElementById('calendar');
				if (elCalendar) {
					window.vue.renderCalendar(elCalendar.id, null, null);
				}

				@if ((isset($user)) && ($user->get('show_calendar_view')) && (isset($calendar_sv_date_from)) && (isset($calendar_sv_date_till)))
				let elCalendarSmallView = document.getElementById('calendar-small-view');
				if (elCalendarSmallView) {
					window.vue.renderCalendar(elCalendarSmallView.id, '{{ $calendar_sv_date_from }}', '{{ $calendar_sv_date_till }}');
				}
				@endif

				@if (app('chat_enable'))
					window.vue.fetchUnreadMessageCount(document.getElementById('unread-message-count'));
				@endif

				@if ((isset($_refresh_chat)) && ($_refresh_chat === true))
					window.vue.refreshChat({{ $user->get('id') }});
					
					@if (app('chat_showusers', false))
						window.vue.refreshUserList();
					@endif

					@if (app('chat_indicator', false))
						window.vue.handleTypingIndicator();
						window.vue.animateChatTypingIndicator();
					@endif
				@endif

				let plantsFilterSearchInput = document.getElementById('sorting-control-filter-text');
				if (plantsFilterSearchInput) {
					plantsFilterSearchInput.addEventListener('input', function() {
						window.vue.textFilterElements(this.value);
					});
				}

				let tasksFilterSearchInput = document.getElementById('tasks-filter');
				if (tasksFilterSearchInput) {
					tasksFilterSearchInput.addEventListener('input', function() {
						window.vue.filterTasks(this.value);
					});
				}

				let inventoryFilterSearchInput = document.getElementById('inventory-filter');
				if (inventoryFilterSearchInput) {
					inventoryFilterSearchInput.addEventListener('input', function() {
						window.vue.filterInventory(this.value);
					});
				}

				@if (app('chat_system'))
					window.vue.fetchNewSystemMessage(document.getElementById('small-system-messages'));
				@endif
			});
		</script>
	</body>
</html>