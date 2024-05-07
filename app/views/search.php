<h1>{{ __('app.search') }}</h1>

<h2 class="smaller-headline">{{ __('app.search_hint') }}</h2>

<div class="margin-vertical">
    <form method="POST" action="{{ url('/search/perform') }}">
        @csrf

        <div class="field">
            <label class="label is-default-text-color">{{ __('app.input_search') }}</label>
            <div class="control">
                <input type="text" class="input is-input-dark" name="text" value="{{ ($query) ?? '' }}" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="search_name" value="1" checked>&nbsp;<span class="is-default-text-color">{{ __('app.search_name') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="search_scientific_name" value="1" checked>&nbsp;<span class="is-default-text-color">{{ __('app.search_scientific_name') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="search_tags" value="1" checked>&nbsp;<span class="is-default-text-color">{{ __('app.search_tags') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="search_notes" value="1" checked>&nbsp;<span class="is-default-text-color">{{ __('app.search_notes') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-link" id="action-search" value="{{ __('app.search') }}">
            </div>
        </div>
    </form>
</div>

@if (isset($plants))
    <div class="plants">
        @if (count($plants) > 0)
            @foreach ($plants as $plant)
                <a href="{{ url('/plants/details/' . $plant->get('id')) }}">
                    <div class="plant-card" style="background-image: url('{{ asset('img/' . $plant->get('photo')) }}');">
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

                                <span>{{ $plant->get('name') . ((!is_null($plant->get('clone_num'))) ? ' (' . strval($plant->get('clone_num') + 1) . ')' : '') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <span class="is-color-darker">{{ __('app.no_plants_found') }}</span>
        @endif
    </div>
@endif
