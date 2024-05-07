<h1>{{ app('history_name') }}</h1>

<div class="margin-vertical">
	<div class="is-inline-block is-action-button-margin"><a class="is-default-link is-fixed-button-link" href="{{ url('/') }}">{{ __('app.back_to_dashboard') }}</a></div>
</div>

@include('flashmsg.php')

<div class="sorting">
	<div class="sorting-control select is-rounded is-small">
		<select onchange="location.href = '{{ url('/plants/history?sorting=') }}' + this.value + '{{ ((isset($_GET['direction'])) ? '&direction=' . $_GET['direction'] : '') }}' + '{{ (isset($_GET['year']) ? '&year=' . $_GET['year'] : '') }}';">
			@foreach ($sorting_types as $sorting_type)
				<option value="{{ $sorting_type }}" {{ ((isset($_GET['sorting'])) && ($_GET['sorting'] === $sorting_type)) ? 'selected' : '' }}>{{ __('app.sorting_type_' . $sorting_type) }}</option>
			@endforeach
		</select>
	</div>

	<div class="sorting-control select is-rounded is-small">
		<select onchange="location.href = '{{ url('/plants/history?sorting=' . ((isset($_GET['sorting'])) ? $_GET['sorting'] : 'name')) }}&direction=' + this.value + '{{ (isset($_GET['year']) ? '&year=' . $_GET['year'] : '') }}';">
			@foreach ($sorting_dirs as $sorting_dir)
				<option value="{{ $sorting_dir }}" {{ ((isset($_GET['direction'])) && ($_GET['direction'] === $sorting_dir)) ? 'selected' : '' }}>{{ __('app.sorting_dir_' . $sorting_dir) }}</option>
			@endforeach
		</select>
	</div>

	<div class="sorting-control is-rounded is-small">
		<input type="text" id="sorting-control-filter-text" placeholder="{{ __('app.filter_by_text') }}">
	</div>
</div>

<div class="history-years">
	<div class="history-year {{ ((!isset($_GET['year'])) ? 'history-year-selected' : '') }}"><a href="{{ url('/plants/history') }}">{{ __('app.all') }}</a></div>

	@foreach ($years as $year)
		<div class="history-year {{ (((isset($_GET['year'])) && ($_GET['year'] == $year->get('history_year'))) ? 'history-year-selected' : '') }}"><a href="{{ url('/plants/history?year=' . $year->get('history_year')) }}">{{ $year->get('history_year') }}</a></div>
	@endforeach
</div>

<div class="plants">
	@if (count($history) > 0)
		@foreach ($history as $plant)
			<div class="plant-card" style="background-image: url('{{ asset('img/' . $plant->get('photo')) }}');">
				<div class="plant-card-overlay">
					<div class="plant-card-options">
						<div class="dropdown is-right" id="plant-card-item-{{ $plant->get('id') }}">
							<div class="dropdown-trigger">
								<i class="fas fa-ellipsis-v is-pointer" onclick="window.vue.toggleDropdown(document.getElementById('plant-card-item-{{ $plant->get('id') }}'));"></i>
							</div>
							<div class="dropdown-menu" role="menu">
								<div class="dropdown-content">
									<a href="javascript:void(0);" onclick="window.vue.unmarkHistorical({{ $plant->get('id') }});" class="dropdown-item">
										<i class="fas fa-undo-alt"></i>&nbsp;{{ __('app.restore_from_history') }}
									</a>

									<a href="javascript:void(0);" onclick="window.vue.deletePlant({{ $plant->get('id') }}, 0);" class="dropdown-item">
										<i class="fas fa-trash-alt"></i>&nbsp;{{ __('app.remove') }}
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="plant-card-title plant-card-title-with-hint">
						<div class="plant-card-title-first">
							@if ($user->get('show_plant_id'))
								<span class="plant-card-title-plant-id">{{ $plant->get('id') }}</span>
							@endif
							
							<span>{{ $plant->get('name') . ((!is_null($plant->get('clone_num'))) ? ' (' . strval($plant->get('clone_num') + 1) . ')' : '') }}</span>
						</div>

						<div class="plant-card-title-second">{{ date('Y-m-d', strtotime($plant->get('history_date'))) }}</div>
					</div>
				</div>
			</div>
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
