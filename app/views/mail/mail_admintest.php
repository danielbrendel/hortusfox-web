<h1>{{ __('app.hortusfox_version', ['version' => config('version')]) }}</h1>

<p>
    {{ __('app.php_version', ['version' => phpversion()]) }}<br/>
    {{ __('app.mysql_version', ['version' => VersionModel::getSqlVersion()]) }}<br/>
    {{ __('app.server_system_info', ['osn' => php_uname('s'), 'osv' => php_uname('v'), 'mt' => php_uname('m')]) }}<br/>
    {{ __('app.server_timezone', ['time' => date('Y-m-d H:i') . ' (' . date_default_timezone_get() . ')']) }}<br/>
</p>
