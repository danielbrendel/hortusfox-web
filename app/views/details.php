<div class="plant-details-title">
	<h1>{{ $plant->get('name') }}</h1>

	<h2>{{ $plant_ident }}</h2>
</div>

<div class="margin-vertical">
	<a class="is-default-link" href="{{ url('/plants/location/' . $plant->get('location')) }}">{{ __('app.back_to_list') }}</a>
</div>

@include('flashmsg.php')

<div class="margin-vertical is-default-text-color">
	{{ __('app.last_edited_by', ['name' => $edit_user_name, 'when' => $edit_user_when]) }}
</div>

@if ($plant->get('health_state') !== 'in_good_standing')
	<div class="plant-warning">{{ __('app.plant_warning', ['reason' => __('app.' . $plant->get('health_state'))]) }}</div>
@endif

<div class="columns plant-column">
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
					<td><strong>{{ __('app.name') }}</strong></td>
					<td>{{ $plant->get('name') }} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditText({{ $plant->get('id') }}, 'name', '{{ $plant->get('name') }}');"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.scientific_name') }}</strong></td>
					<td>
						@if ($plant->get('scientific_name'))
							@if ((is_string($plant->get('knowledge_link'))) && (strlen($plant->get('knowledge_link') > 0)))
								<a class="is-default-link" href="{{ $plant->get('knowledge_link') }}" target="_blank">{{ $plant->get('scientific_name') }}</a>
							@else
								{{ $plant->get('scientific_name') }}
							@endif
						@else
							<span class="is-not-available">N/A</span>
						@endif
					
						<span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditLinkText({{ $plant->get('id') }}, '{{ ($plant->get('scientific_name')) ?? '' }}', '{{ ($plant->get('knowledge_link')) ?? '' }}');"><i class="fas fa-edit is-color-darker"></i></a></span>
					</td>
				</tr>

				<tr>
					<td><strong>{{ __('app.location') }}</strong></td>
					<td>{{ LocationsModel::getNameById($plant->get('location')) }} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditCombo({{ $plant->get('id') }}, 'location', window.vue.comboLocation, {{ $plant->get('location') }});"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.last_watered') }}</strong></td>
					<td>
						@if ($plant->get('last_watered'))
							{{ date('Y-m-d', strtotime($plant->get('last_watered'))) }}
						@else
							<span class="is-not-available">N/A</span>
						@endif

						<span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditDate({{ $plant->get('id') }}, 'last_watered', '{{ ($plant->get('last_watered')) ? date('Y-m-d', strtotime($plant->get('last_watered'))) : '' }}');"><i class="fas fa-edit is-color-darker"></i></a></span>
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

						<span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditDate({{ $plant->get('id') }}, 'last_repotted', '{{ ($plant->get('last_repotted')) ? date('Y-m-d', strtotime($plant->get('last_repotted'))) : '' }}');"><i class="fas fa-edit is-color-darker"></i></a></span>
					</td>
					</td>
				</tr>

				<tr>
					<td><strong>{{ __('app.perennial') }}</strong></td>
					<td>{!! ($plant->get('perennial')) ? '<span class="is-color-yes">' . __('app.yes') . '</span>' : '<span class="is-color-no">' . __('app.no') . '</span>' !!} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditBoolean({{ $plant->get('id') }}, 'perennial', '{{ __('app.perennial') }}', {{ ($plant->get('perennial')) ? 'true' : 'false' }});"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.cutting_month') }}</strong></td>
					<td>{!! ($plant->get('cutting_month')) ? UtilsModule::getMonthList()[$plant->get('cutting_month')] : '<span class="is-not-available">N/A</span>' !!} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditCombo({{ $plant->get('id') }}, 'cutting_month', window.vue.comboCuttingMonth, {{ ($plant->get('cutting_month')) ?? '0' }});"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.date_of_purchase') }}</strong></td>
					<td>
						@if ($plant->get('date_of_purchase'))
							{{ date('Y-m-d', strtotime($plant->get('date_of_purchase'))) }}
						@else
							<span class="is-not-available">N/A</span>
						@endif
						
						<span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditDate({{ $plant->get('id') }}, 'date_of_purchase', '{{ ($plant->get('date_of_purchase')) ? date('Y-m-d', strtotime($plant->get('date_of_purchase'))) : '' }}');"><i class="fas fa-edit is-color-darker"></i></a></span>
					</td>
				</tr>

				<tr>
					<td><strong>{{ __('app.humidity') }}</strong></td>
					<td>{{ $plant->get('humidity') . '%' }} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditInteger({{ $plant->get('id') }}, 'humidity', '{{ $plant->get('humidity') }}');"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.light_level') }}</strong></td>
					<td>{{ __('app.' . $plant->get('light_level')) }} <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditCombo({{ $plant->get('id') }}, 'light_level', window.vue.comboLightLevel, '{{ $plant->get('light_level') }}');"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>

				<tr>
					<td><strong>{{ __('app.health_state') }}</strong></td>
					<td><span class="plant-state-{{ $plant->get('health_state') }}">{!! ($plant->get('health_state') === 'in_good_standing') ? '<i class="far fa-check-circle is-color-yes"></i>&nbsp;' : '' !!}{{ __('app.' . $plant->get('health_state')) }}</span> <span class="float-right"><a href="javascript:void(0);" onclick="window.vue.showEditCombo({{ $plant->get('id') }}, 'health_state', window.vue.comboHealthState, '{{ $plant->get('health_state') }}');"><i class="fas fa-edit is-color-darker"></i></a></span></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="column is-half">
		<a href="javascript:void(0);" onclick="window.vue.showEditPhoto({{ $plant->get('id') }}, 'photo');">
			<div class="plant-photo" style="background-image: url('{{ asset('img/' . $plant->get('photo')) }}');">
				<div class="plant-photo-overlay">
					<div class="plant-photo-edit"><i class="fas fa-upload fa-4x"></i></div>
				</div>
			</div>
		</a>
	</div>
</div>

<div class="columns plant-column">
	<div class="column is-full">
		<div class="plant-tags">
			<a name="plant-tags-anchor"></a>

			<div class="plant-tags-content">
				@if (strlen($plant->get('tags')) > 1)
					@foreach ($tags as $tag)
						@if (strlen($tag) > 0)
							<div class="plant-tags-item"><a href="{{ url('/search?query=' . $tag) }}">{{ $tag }}</a></div>
						@endif
					@endforeach
				@else
					<strong class="is-default-text-color">{{ __('app.no_tags_specified') }}</strong>
				@endif
			</div>

			<div class="plant-tags-edit">
				<a href="javascript:void(0);" onclick="window.vue.showEditText({{ $plant->get('id') }}, 'tags', '{{ $plant->get('tags') }}', 'plant-tags-anchor');">
					<i class="fas fa-edit is-color-darker"></i>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="columns plant-column">
	<div class="column is-full">
		<div class="plant-notes">
			<a name="plant-notes-anchor"></a>

			<div class="plant-notes-content">
				@if (is_string($plant->get('notes')))
					{{ $plant->get('notes') }}
				@else
					<span class="is-not-available">{{ __('app.no_notes_specified') }}</span>
				@endif
			</div>

			<div class="plant-notes-edit">
				<a href="javascript:void(0);" onclick="window.vue.showEditText({{ $plant->get('id') }}, 'notes', '{{ $plant->get('notes') ?? '' }}', 'plant-notes-anchor');">
					<i class="fas fa-edit is-color-darker"></i>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="columns plant-column">
	<div class="column is-full">
		<div class="plant-gallery">
			<div class="plant-gallery-title">{{ __('app.photos') }}</div>

			<div class="plant-gallery-upload">
				<a class="button is-link" href="javascript:void(0);" onclick="window.vue.showPhotoUpload({{ $plant->get('id') }});">{{ __('app.upload') }}</a>
			</div>

			<a name="plant-gallery-photo-anchor"></a>

			<div class="plant-gallery-photos">
				@if (count($photos) > 0)
					@foreach ($photos as $photo)
						<div class="plant-gallery-item" id="photo-gallery-item-{{ $photo->get('id') }}">
							<div class="plant-gallery-item-header">
								<div class="plant-gallery-item-header-label">{{ $photo->get('label') }}</div>

								<div class="plant-gallery-item-header-action">
									<a href="javascript:void(0);" onclick="window.vue.deletePhoto({{ $photo->get('id') }}, {{ $plant->get('id') }}, 'photo-gallery-item-{{ $photo->get('id') }}');"><i class="fas fa-trash-alt"></i></a>
								</div>
							</div>

							<div class="plant-gallery-item-photo">
								<a href="{{ asset('img/' . $photo->get('original')) }}" target="_blank">
									<div class="plant-gallery-item-photo-overlay"></div>

									<img class="plant-gallery-item-photo-image" src="{{ asset('img/' . $photo->get('thumb')) }}" alt="photo"/>
								</a>
							</div>

							<div class="plant-gallery-item-footer">
								{{ (new Carbon($photo->get('created_at')))->diffForHumans() }}
							</div>
						</div>
					@endforeach
				@else
					<strong>{{ __('app.no_photos_yet') }}</strong>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="columns plant-column">
	<div class="column is-full">
		@if (env('APP_ENABLEHISTORY'))
		<span>
			<a class="button is-warning" href="javascript:void(0);" onclick="window.vue.markHistorical({{ $plant->get('id') }}, {{ $plant->get('location') }});">{{ env('APP_HISTORY_NAME') }}</a>&nbsp;
		</span>
		@endif

		<span>	
			<a class="button is-danger" href="javascript:void(0);" onclick="window.vue.deletePlant({{ $plant->get('id') }}, {{ $plant->get('location') }});">{{ __('app.remove_plant') }}</a>&nbsp;
		</span>
	</div>
</div>
