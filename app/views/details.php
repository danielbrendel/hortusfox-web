<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ $plant->get('name') }}</h1>

			<div class="margin-vertical">
                <a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('inpPlantId').value = {{ $plant->get('id') }}; window.vue.bShowEditPlant = true;">{{ __('app.edit_plant') }}</a>
				&nbsp;&nbsp;&nbsp;<a class="is-default-link is-fixed-button-link" href="{{ url('/plants/location/' . $plant->get('location')) }}">{{ __('app.back_to_list') }}</a>
            </div>

			<div class="margin-vertical is-default-text-color">
				{{ __('app.last_edited_by', ['name' => $edit_user_name, 'when' => $edit_user_when]) }}
			</div>

			@if ($plant->get('health_state') !== 'in_good_standing')
				<div class="plant-warning">{{ __('app.plant_warning', ['reason' => __('app.' . $plant->get('health_state'))]) }}</div>
			@endif

			<div class="plant-info">
				<div class="columns">
					<div class="column is-half">
						<table>
							<thead>
								<tr>
									<td>{{ __('app.attribute') }}</td>
									<td>{{ __('app.value') }}</td>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td><strong>{{ __('app.location') }}</strong></td>
									<td>{{ LocationsModel::getNameById($plant->get('location')) }}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.last_watered') }}</strong></td>
									<td>
									@if ($plant->get('last_watered'))
											{{ date('Y-m-d', strtotime($plant->get('last_watered'))) }}
										@else
											<span class="is-not-available">N/A</span>
										@endif
									</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.last_repotted') }}</strong></td>
									<td>
										@if ($plant->get('last_repotted'))
											{{ date('Y-m-d', strtotime($plant->get('last_repotted'))) }}
										@else
											<span class="is-not-available">N/A</span>
										@endif
									</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.perennial') }}</strong></td>
									<td>{!! ($plant->get('perennial')) ? '<span class="is-color-yes">' . __('app.yes') . '</span>' : '<span class="is-color-no">' . __('app.no') . '</span>' !!}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.cutting_month') }}</strong></td>
									<td>{{ UtilsModule::getMonthList()[$plant->get('cutting_month')] }}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.date_of_purchase') }}</strong></td>
									<td>{{ date('Y-m-d', strtotime($plant->get('date_of_purchase'))) }}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.humidity') }}</strong></td>
									<td>{{ $plant->get('humidity') . '%' }}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.light_level') }}</strong></td>
									<td>{{ $plant->get('light_level') }}</td>
								</tr>

								<tr>
									<td><strong>{{ __('app.health_state') }}</strong></td>
									<td><span class="plant-state-{{ $plant->get('health_state') }}">{!! ($plant->get('health_state') === 'in_good_standing') ? '<i class="far fa-check-circle is-color-yes"></i>&nbsp;' : '' !!}{{ __('app.' . $plant->get('health_state')) }}</span></td>
								</tr>

								<tr>
									<td><strong>{{ __('app.notes') }}</strong></td>
									<td>
										@if (is_string($plant->get('notes')))
											{{ $plant->get('notes') }}
										@else
											<span class="is-not-available">N/A</span>
										@endif
									</td>
								</tr>
							</tbody>
						</table>

						<div class="plant-description">{{ $plant->get('description') ?? '' }}</div>
					</div>

					<div class="column is-half">
						<img src="{{ asset('img/' . $plant->get('photo')) }}" alt="plant-photo"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="column is-2"></div>
</div>