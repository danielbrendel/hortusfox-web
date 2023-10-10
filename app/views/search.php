<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
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
                                            @if ($plant->get('health_state') === 'overwatered')
                                                <i class="fas fa-water plant-state-overwatered"></i>
                                            @elseif ($plant->get('health_state') === 'withering')
                                                <i class="fab fa-pagelines plant-state-withering"></i>
                                            @elseif ($plant->get('health_state') === 'infected')
                                                <i class="fas fa-biohazard plant-state-infected"></i>
                                            @endif
                                        </div>

                                        <div class="plant-card-title">{{ $plant->get('name') }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <span class="is-color-darker">{{ __('app.no_plants_found') }}</span>
                    @endif
                </div>
            @endif
		</div>
	</div>

	<div class="column is-2"></div>
</div>