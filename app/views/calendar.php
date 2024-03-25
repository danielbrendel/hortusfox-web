<h1>{{ __('app.calendar') }}</h1>

<h2 class="smaller-headline">{{ __('app.calendar_hint') }}</h2>

@include('flashmsg.php')

<div class="calendar-add">
    <a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowAddCalendarItem = true;">{{ __('app.add') }}</a>
</div>

<div class="calendar-menu">
    <input class="calendar-input" type="date" id="inp-date-from" value="{{ date('Y-m-d') }}"/>&nbsp;<input class="calendar-input" type="date" id="inp-date-till" value="{{ date('Y-m-d', strtotime('+30 days')) }}"/>&nbsp;
    <select class="calendar-input" onchange="window.vue.renderCalendar('calendar', '{{ date('Y-m-d') }}', this.value);">
        <option value="">{{ __('app.timespan_select') }}</option>
        <option value="{{ date('Y-m-d', strtotime('+1 week')) }}">{{ __('app.timespan_one_week') }}</option>
        <option value="{{ date('Y-m-d', strtotime('+2 weeks')) }}">{{ __('app.timespan_two_weeks') }}</option>
        <option value="{{ date('Y-m-d', strtotime('+1 month')) }}">{{ __('app.timespan_one_month') }}</option>
        <option value="{{ date('Y-m-d', strtotime('+3 months')) }}">{{ __('app.timespan_three_months') }}</option>
        <option value="{{ date('Y-m-d', strtotime('+6 months')) }}">{{ __('app.timespan_half_a_year') }}</option>
    </select>
    &nbsp;<a class="button is-link" href="javascript:void(0);" onclick="window.vue.renderCalendar('calendar', document.getElementById('inp-date-from').value, document.getElementById('inp-date-till').value);">{{ __('app.go') }}</a>
</div>

<div class="calendar-content">
    <canvas id="calendar"></canvas>
</div>
