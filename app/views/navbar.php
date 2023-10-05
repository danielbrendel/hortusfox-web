<nav class="navbar is-dark" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item navbar-item-brand is-font-title" href="{{ url('/') }}">
            <img src="{{ asset('logo.png') }}"/>&nbsp;{{ __('app.workspace_title', ['name' => env('APP_WORKSPACE')]) }}
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
                <a href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = 0; window.vue.bShowAddPlant = true;">
                    <i class="fas fa-leaf" title="{{ __('app.add_plant') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.add_plant') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/tasks') }}">
                    <i class="fas fa-tasks" title="{{ __('app.tasks') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.tasks') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/search') }}">
                    <i class="fas fa-search" title="{{ __('app.search') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.search') }}</span>
                </a>
            </div>

            <div class="navbar-item">
                <a href="{{ url('/profile') }}">
                    <i class="fas fa-user" title="{{ __('app.profile') }}"></i><span class="navbar-item-only-mobile">&nbsp;{{ __('app.profile') }}</span>
                </a>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
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