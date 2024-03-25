<h1>{{ __('app.calendar') }}</h1>

<h2 class="smaller-headline">{{ __('app.calendar_hint') }}</h2>

@include('flashmsg.php')

<div class="calendar-add">
    <a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowAddCalendarItem = true;">{{ __('app.add') }}</a>
</div>

<div class="calendar-menu">
    <input class="calendar-input" type="date" id="inp-date-from" value="{{ date('Y-m-d') }}"/>&nbsp;<input class="calendar-input" type="date" id="inp-date-till" value="{{ date('Y-m-d', strtotime('+30 days')) }}"/>&nbsp;
    &nbsp;<a class="button is-link" href="javascript:void(0);" onclick="window.vue.renderCalendar('calendar', document.getElementById('inp-date-from').value, document.getElementById('inp-date-till').value);">{{ __('app.go') }}</a>
</div>

<div class="calendar-content">
    <canvas id="calendar"></canvas>
</div>
