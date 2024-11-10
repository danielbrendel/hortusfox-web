<h1>{{ $location_name }}</h1>

<div class="margin-vertical">
	<div class="action-strip action-strip-left">
		<div class="is-inline-block is-action-button-margin"><a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = {{ $location }}; window.vue.bShowAddPlant = true;">{{ __('app.add_plant') }}</a></div>
		@if (plant_attr('last_watered'))
		<div class="is-inline-block is-action-button-margin"><a class="button is-info" href="javascript:void(0);" onclick="window.vue.showPerformBulkUpdate('last_watered', '{{ __('app.bulk_set_watered') }}', '{{ __('app.set_watered') }}', '{{ $location }}');">{{ __('app.set_watered') }}</a></div>
		@endif
		@if (plant_attr('last_repotted'))
		<div class="is-inline-block is-action-button-margin"><a class="button is-warning" href="javascript:void(0);" onclick="window.vue.showPerformBulkUpdate('last_repotted', '{{ __('app.bulk_set_repotted') }}', '{{ __('app.set_repotted') }}', '{{ $location }}');">{{ __('app.set_repotted') }}</a></div>
		@endif
		@if (plant_attr('last_fertilised'))
		<div class="is-inline-block is-action-button-margin"><a class="button is-chocolate" href="javascript:void(0);" onclick="window.vue.showPerformBulkUpdate('last_fertilised', '{{ __('app.bulk_set_fertilised') }}', '{{ __('app.set_fertilised') }}', '{{ $location }}');">{{ __('app.set_fertilised') }}</a></div>
		@endif
		@foreach (CustBulkCmdModel::getCmdList() as $bulk_cmd)
		<div class="is-inline-block is-action-button-margin"><a class="button" style="{{ $bulk_cmd->get('styles') }}" href="javascript:void(0);" onclick="window.vue.showPerformBulkUpdate('{{ $bulk_cmd->get('attribute') }}', '{{ $bulk_cmd->get('label') }}', '{{ $bulk_cmd->get('label') }}', '{{ $location }}', true);">{{ $bulk_cmd->get('label') }}</a></div>
		@endforeach
		<div class="is-inline-block is-action-button-margin"><a class="button" href="javascript:void(0);" onclick="window.vue.bShowPlantBulkPrint = true;">{{ __('app.bulk_print_qr_codes') }}</a></div>
		<div class="is-inline-block is-action-button-margin"><a class="is-default-link is-fixed-button-link is-fixed-margin-left-mobile" href="{{ url('/') }}">{{ __('app.back_to_dashboard') }}</a></div>
	</div>

	<div class="action-strip action-strip-right">
		<div class="is-inline-block is-action-button-margin float-right"><a class="is-gray-link" href="javascript:void(0);" onclick="document.querySelector('#location-log-anchor').scrollIntoView({behavior: 'smooth'});"><i class="far fa-file-alt fa-lg"></i></a></div>
	</div>
</div>

@include('flashmsg.php')

<div class="sorting">
	<div class="sorting-control sorting-mobile-only">
		<a class="{{ (((!isset($_GET['show'])) || ($_GET['show'] === 'cards')) ? 'is-selected' : '') }}" href="{{ url('/plants/location/' . $location . '?show=cards' . url_query('sorting', '&') . url_query('direction', '&')) }}">
			<i class="far fa-file-image"></i>
			<span>{{ __('app.plant_sorting_view_cards') }}</span>
		</a>
	</div>

	<div class="sorting-control sorting-mobile-only sorting-mobile-only-last-elem">
		<a class="{{ (((isset($_GET['show'])) && ($_GET['show'] === 'list')) ? 'is-selected' : '') }}" href="{{ url('/plants/location/' . $location . '?show=list' . url_query('sorting', '&') . url_query('direction', '&')) }}">
			<i class="far fa-list-alt"></i>
			<span>{{ __('app.plant_sorting_view_list') }}</span>
		</a>
	</div>

	<div class="sorting-control select is-rounded is-small">
		<select onchange="location.href = '{{ url('/plants/location/' . $location . '?sorting=') }}' + this.value + '{{ ((isset($_GET['direction'])) ? '&direction=' . $_GET['direction'] : '') . url_query('show', '&') }}';">
			@foreach ($sorting_types as $sorting_type)
				@if (strpos($sorting_type, 'history') === false)
					<option value="{{ $sorting_type }}" {{ ((isset($_GET['sorting'])) && ($_GET['sorting'] === $sorting_type)) ? 'selected' : '' }}>{{ __('app.sorting_type_' . $sorting_type) }}</option>
				@endif
			@endforeach
		</select>
	</div>

	<div class="sorting-control select is-rounded is-small">
		<select onchange="location.href = '{{ url('/plants/location/' . $location . '?sorting=' . ((isset($_GET['sorting'])) ? $_GET['sorting'] : 'name')) . url_query('show', '&') }}&direction=' + this.value;">
			@foreach ($sorting_dirs as $sorting_dir)
				<option value="{{ $sorting_dir }}" {{ ((isset($_GET['direction'])) && ($_GET['direction'] === $sorting_dir)) ? 'selected' : '' }}>{{ __('app.sorting_dir_' . $sorting_dir) }}</option>
			@endforeach
		</select>
	</div>

	<div class="sorting-control is-rounded is-small">
		<input type="text" id="sorting-control-filter-text" placeholder="{{ __('app.filter_by_text') }}">
	</div>
</div>

<div class="plants">
	@if (count($plants) > 0)
		@foreach ($plants as $plant)
			@if ((!isset($_GET['show'])) || ($_GET['show'] === 'cards'))
				<a href="{{ url('/plants/details/' . $plant->get('id')) }}">
					<div class="plant-card" style="background-image: url('{{ abs_photo($plant->get('photo')) }}');">
						<div class="plant-card-overlay">
							@if ((isset($_GET['sorting'])) && ($_GET['sorting'] !== 'name'))
								<div class="plant-card-sorting">{{ UtilsModule::readablePlantAttribute($plant->get($_GET['sorting']), $_GET['sorting']) }}</div>
							@endif

							<div class="plant-card-health-state">
								@if ($plant->get('health_state') !== 'in_good_standing')
									<i class="{{ PlantsModel::$plant_health_states[$plant->get('health_state')]['icon'] }} plant-state-{{ $plant->get('health_state') }}"></i>
								@endif
							</div>

							<div class="plant-card-title {{ ((strlen($plant->get('name')) > PlantsModel::PLANT_LONG_TEXT_THRESHOLD) ? 'plant-card-title-longtext' : '') }}">
								@if ($user->get('show_plant_id'))
									<span class="plant-card-title-plant-id">{{ $plant->get('id') }}</span>
								@endif

								<span>{{ $plant->get('name') . ((!is_null($plant->get('clone_num'))) ? ' (' . strval($plant->get('clone_num') + 1) . ')' : '') }}</span>
							</div>
						</div>
					</div>
				</a>
			@elseif ((isset($_GET['show'])) && ($_GET['show'] === 'list'))
				<a href="{{ url('/plants/details/' . $plant->get('id')) }}">
					<div class="plant-list-item">
						<div class="plant-list-id">#{{ sprintf('%04d', $plant->get('id')) }}</div>
						<div class="plant-list-name-full">{{ $plant->get('name') }}</div>
						<div class="plant-list-name-short">{{ substr($plant->get('name'), 0, PlantsModel::PLANT_LIST_MAX_STRLEN) . '...' }}</div>
						<div class="plant-list-scientific-name plant-list-item-hide-small-devices">{{ ($plant->get('scientific_name') ?? 'N/A') }}</div>
						@if ($plant->get('last_edited_date'))
						<div class="plant-list-last-edited">{{ (new Carbon($plant->get('last_edited_date')))->diffForHumans() }}</div>
						@endif
					</div>
				</a>
			@endif
		@endforeach
	@else
		<div class="plants-empty">
			<div class="plants-empty-image">
				<img src="{{ asset('img/plantsempty.png') }}" alt="image"/>
			</div>

			<div class="plants-empty-text">{{ __('app.content_empty') }}</div>
		</div>
	@endif
</div>

<div class="is-dark-delimiter"><hr/></div>

<div class="location-log">
	<div class="location-log-title">{{ __('app.location_log') }}</div>

	<a name="location-log-anchor" id="location-log-anchor"></a>

	@if ((is_countable($location_log_entries)) && (count($location_log_entries) > 0))
	<table id="location-log-table">
		<thead>
			<tr>
				<td>{{ __('app.location_log_content') }}</td>
				<td>{{ __('app.location_log_date') }}</td>
				<td><span class="float-right">{{ __('app.location_log_actions') }}</span></td>
			</tr>
		</thead>

		<tbody>
			@foreach ($location_log_entries as $location_log_entry)
			<tr id="location-log-entry-table-row-{{ $location_log_entry->get('id') }}">
				<td id="location-log-entry-item-{{ $location_log_entry->get('id') }}">{{ $location_log_entry->get('content') }}</td>
				<td>{{ date('Y-m-d', strtotime($location_log_entry->get('created_at'))) }} / {{ date('Y-m-d', strtotime($location_log_entry->get('updated_at'))) }}</td>
				<td>
					<span class="float-right">
						<span><a href="javascript:void(0);" onclick="window.vue.showEditLocationLogEntry('{{ $location_log_entry->get('id') }}', '{{ $location_log_entry->get('location') }}', document.getElementById('location-log-entry-item-{{ $location_log_entry->get('id') }}').innerText, 'location-log-anchor');"><i class="fas fa-edit is-color-darker"></i></a></span>&nbsp;<span class="float-right"><a href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_location_log_entry') }}')) { window.vue.removeLocationLogEntry('{{ $location_log_entry->get('id') }}', 'location-log-entry-table-row-{{ $location_log_entry->get('id') }}'); }"><i class="fas fa-trash-alt is-color-darker"></i></a></span>
					</span>
				</td>
			</tr>
			@endforeach

			@if ($location_log_entries->asArray()[count($location_log_entries) - 1]['id'] > 1)
				<tr id="location-log-load-more" class="location-log-paginate">
					<td colspan="3"><a href="javascript:void(0);" onclick="window.vue.loadNextLocationLogEntries(this, '{{ $location }}', document.getElementById('location-log-table'));" data-paginate="{{ $location_log_entries->asArray()[count($location_log_entries) - 1]['id'] }}">{{ __('app.load_more') }}</a></td>
				</tr>
			@endif
		</tbody>
	</table>
	@else
		<strong>{{ __('app.no_location_log_entries_yet') }}</strong>
	@endif

	<div class="location-log-action">
		<a class="button is-info" href="javascript:void(0);" onclick="window.vue.showAddLocationLogEntry('{{ $location }}', 'location-log-anchor');">{{ __('app.add_location_log_entry') }}</a>
	</div>
</div>
