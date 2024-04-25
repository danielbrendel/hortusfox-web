<nav class="navbar is-dark {{ ((app('pwa_enable')) ? 'is-fixed-top-pwa' : '') }}" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item navbar-item-brand is-font-title" href="{{ url('/') }}">
            <img src="{{ asset('logo.png') }}"/>&nbsp;{{ app('workspace') }}
        </a>

        <a class="navbar-item navbar-item-brand-mobile-right" href="{{ url('/profile') }}">
            <i class="fas fa-user" title="{{ __('app.profile') }}"></i>
        </a>

        <a id="burger-button" role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start"></div>

        <div class="navbar-end">
            <div class="navbar-item">
                @if (LocationsModel::getCount() > 0)
                <a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = {{ ((isset($location)) && (is_numeric($location)) ? $location : '0') }}; window.vue.bShowAddPlant = true;">
                    {{ __('app.add_plant') }}
                </a>
                @else
                <a class="button is-success" href="javascript:void(0);" onclick="window.vue.bShowAddFirstLocation = true;">
                    {{ __('app.add_plant') }}
                </a>
                @endif
            </div>

            <div class="navbar-item">
                <a href="{{ url('/tasks') }}">
                    <i class="fas fa-tasks" title="{{ __('app.tasks') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.tasks') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/inventory') }}">
                    <i class="fas fa-warehouse" title="{{ __('app.inventory') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.inventory') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/search') }}">
                    <i class="fas fa-search" title="{{ __('app.search') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.search') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/calendar') }}">
                    <i class="far fa-calendar-alt" title="{{ __('app.calendar') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.calendar') }}</span>
                </a>
            </div>

            @if (app('owm_enable'))
            <div class="navbar-item">
                <a href="{{ url('/weather') }}">
                    <i class="fas fa-cloud-sun-rain" title="{{ __('app.weather') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.weather') }}</span>
                </a>
            </div>
            @endif

            @if (app('chat_enable'))
            <div class="navbar-item">
                <a href="{{ url('/chat') }}" class="notification-badge">
                    <i class="fas fa-comments" title="{{ __('app.chat') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.chat') }}</span>
                    
                    <span class="notify-badge is-hidden" id="unread-message-count">
                        <span class="notify-badge-count"></span>
                    </span>
                </a>
            </div>
            @endif
            
            @if (app('history_enable'))
            <div class="navbar-item">
                <a href="{{ url('/plants/history') }}">
                    <i class="fas fa-history" title="{{ app('history_name') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ app('history_name') }}</span>
                </a>
            </div>
            @endif

            <div class="navbar-item">
                <a href="{{ url('/profile') }}">
                    <i class="fas fa-user" title="{{ __('app.profile') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.profile') }}</span>
                </a>
            </div>

            @if (UserModel::isCurrentlyAdmin())
            <div class="navbar-item">
                <a href="{{ url('/admin') }}">
                    <i class="fas fa-cog" title="{{ __('app.admin_area') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.admin_area') }}</span>
                </a>
                @if (VersionModule::getCachedVersion() > config('version'))
                    <span class="is-indicator"></span>
                @endif
            </div>
            @endif

            <div class="navbar-item">
                <a href="{{ url('/logout') }}">
                    <i class="fas fa-sign-out-alt" title="{{ __('app.logout') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.logout') }}</span>
                </a>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link navbar-dropdown-minwidth">
                    {{ __('app.locations') }}
                </a>

                <div class="navbar-dropdown">
                    @foreach (LocationsModel::getAll() as $location_item)
                        <a class="navbar-item" href="{{ url('/plants/location/' . $location_item->get('id')) }}">
                            {{ $location_item->get('name') }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>