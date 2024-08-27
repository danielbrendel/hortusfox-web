<div class="preferences-header">
	<div class="is-inline-block">
		<h1>{{ __('app.profile') }}</h1>
	</div>

	<div class="is-inline-block" title="{{ __('app.preferences') }}">
		<a href="javascript:void(0);" onclick="window.vue.bShowEditPreferences = true;">&nbsp;&nbsp;<i class="fas fa-cog fa-2x"></i></a>
	</div>
</div>

<div class="margin-vertical is-default-text-color">{{ __('app.profile_hint', ['name' => $user->get('name'), 'email' => $user->get('email')]) }}</div>

@include('flashmsg.php')

<div class="margin-vertical">
	<h2 class="smaller-headline">{{ __('app.personal_notes') }}</h2>

	<form method="POST" action="{{ url('/profile/notes/save') }}">
		@csrf

		<div class="field">
			<div class="control">
				<textarea class="textarea is-input-dark" name="notes">{{ $user->get('notes') ?? 'N/A' }}</textarea>
			</div>
		</div>

		<div class="field">
			<div class="control">
				<input type="submit" class="button is-info" value="{{ __('app.save') }}">
			</div>
		</div>
	</form>
</div>

<div><hr/></div>

@if ((app('enable_media_share')) && (count($sharelog) > 0))
<div class="margin-vertical profile-shared-photos">
	<h2 class="smaller-headline">{{ __('app.shared_photos') }}</h2>

	<table>
		<thead>
			<tr>
				<td>{{ __('app.link') }}</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			@foreach ($sharelog as $sharelog_item)
			<tr id="photo-share-entry-{{ $sharelog_item->get('ident') }}">
				<td><a href="{{ $sharelog_item->get('url') }}" target="_blank">{{ $sharelog_item->get('url') }}</a></td>
				<td><a href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_shared_photo') }}')) { window.vue.removeSharedPhoto('{{ $sharelog_item->get('ident') }}'); }"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

<div><hr/></div>
@endif

<div class="plants">
	<h2 class="smaller-headline">{{ __('app.last_authored_plants') }}</h2>

	@foreach ($plants as $plant)
		<a href="{{ url('/plants/details/' . $plant->get('id')) }}">
			<div class="plant-card" style="background-image: url('{{ abs_photo($plant->get('photo')) }}');">
				<div class="plant-card-overlay">
					<div class="plant-card-health-state">
						@if ($plant->get('health_state') !== 'in_good_standing')
							<i class="{{ PlantsModel::$plant_health_states[$plant->get('health_state')]['icon'] }} plant-state-{{ $plant->get('health_state') }}"></i>
						@endif
					</div>

					<div class="plant-card-title {{ ((strlen($plant->get('name')) > PlantsModel::PLANT_LONG_TEXT_THRESHOLD) ? 'plant-card-title-longtext' : '') }}">
						@if ($user->get('show_plant_id'))
							<span class="plant-card-title-plant-id">{{ $plant->get('id') }}</span>
						@endif

						<span>{{ $plant->get('name') }}</span>
					</div>
				</div>
			</div>
		</a>
	@endforeach
</div>

@if ($user->get('show_log'))
	@if (count($log) > 0)
		<div class="log">
			<div class="log-title">{{ __('app.log_title') }}</div>

			<div class="log-content">
				@foreach ($log as $entry)
					<div class="log-item">
						@if ($entry['link'])
							<a href="{{ $entry['link'] }}">[{{ $entry['date'] }}] ({{ $entry['user'] }}) {{ $entry['property'] }} =&gt; {{ $entry['value'] }} @ {{ $entry['target'] }}</a>
						@else
							[{{ $entry['date'] }}] ({{ $entry['user'] }}) {{ $entry['property'] }} =&gt; {{ $entry['value'] }} @ {{ $entry['target'] }}
						@endif
					</div>
				@endforeach
			</div>
		</div>
	@endif
@endif
