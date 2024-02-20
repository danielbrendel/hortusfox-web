<div class="bottomnav">
    <div class="bottomnav-items">
        <div class="bottomnav-item">
            <a href="{{ url('/') }}">
                <div><i class="fas fa-tachometer-alt"></i></div>
                <div>{{ __('app.home') }}</div>
            </a>
        </div>

        <div class="bottomnav-item">
            <a href="javascript:void(0);" onclick="document.getElementById('inpLocationId').value = 0; window.vue.bShowAddPlant = true;">
                <div><i class="fas fa-plus"></i></div>
                <div>{{ __('app.add_plant_short') }}</div>
            </a>
        </div>

        <div class="bottomnav-item">
            <a href="{{ url('/tasks') }}">
                <div><i class="fas fa-tasks"></i></div>
                <div>{{ __('app.tasks') }}</div>
            </a>
        </div>

        <div class="bottomnav-item">
            <a href="{{ url('/inventory') }}">
                <div><i class="fas fa-warehouse"></i></div>
                <div>{{ __('app.inventory') }}</div>
            </a>
        </div>

        <div class="bottomnav-item">
            <a href="{{ url('/search') }}">
                <div><i class="fas fa-search"></i></div>
                <div>{{ __('app.search') }}</div>
            </a>
        </div>
    </div>
</div>