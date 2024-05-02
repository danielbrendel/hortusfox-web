<h1>{{ __('app.weather') }}</h1>

<h2 class="smaller-headline">{{ __('app.weather_hint', ['region' => $forecast->city->name]) }}</h2>

@include('flashmsg.php')

<div class="weather-forecast">
    <?php $lastDate = '';  ?>

    <div class="weather-header">
        @foreach ($weekdays as $weekday)
            <div class="weather-header-item">
                <div><strong>{{ __('app.' . strtolower($weekday['day'])) }}</strong></div>
                <div>{{ $weekday['date'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="weather-day">
        @foreach ($forecast->list as $forecast_count => $forecast_item)
            @if ($forecast_count >= $forecast->cnt)
                @break
            @endif

            <?php
                if ($lastDate !== date('Y-m-d', $forecast_item->dt)) {
                    if ($lastDate !== '') {
                        echo '</div><div class="weather-day">';
                    }
                    
                    $lastDate = date('Y-m-d', $forecast_item->dt);
                }
            ?>

            @if (!isset($forecast_item->filled))
            <div class="weather-day-data">
                <div class="weather-day-data-title">{{ date('H:i', $forecast_item->dt) }}</div>
                <div class="weather-day-data-icon"><img src="{{ WeatherModule::WEATHER_ICON_ENDPOINT }}/wn/{{ $forecast_item->weather[0]->icon }}@2x.png" alt="icon"/></div>
                <div class="weather-day-data-attribute"><i class="fas fa-thermometer-half"></i> {{ round($forecast_item->main->temp) . '°' . WeatherModule::getUnitChar(app('owm_unittype')) }}</div>
                <div class="weather-day-data-attribute"><i class="fas fa-tint"></i> {{ $forecast_item->main->humidity . '%' }}</div>
                <div class="weather-day-data-attribute"><i class="fas fa-wind"></i> {{ round($forecast_item->wind->speed) . 'm/s' }}</div>
                <div><hr/></div>
            </div>
            @else
            <div class="weather-day-data">
                <div class="weather-day-data-title is-weather-fill-data">{{ date('H:i', $forecast_item->dt) }}</div>
                <div class="weather-day-data-icon"><img src="{{ WeatherModule::WEATHER_ICON_ENDPOINT }}/wn/01n@2x.png" alt="icon"/></div>
                <div class="weather-day-data-attribute">&nbsp;</div>
                <div class="weather-day-data-attribute">&nbsp;</div>
                <div class="weather-day-data-attribute">&nbsp;</div>
                <div><hr/></div>
            </div>
            @endif
        @endforeach
    </div>
</div>
