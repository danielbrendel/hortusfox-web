<div class="banner" style="background-image: url('{{ ThemeModule::data()->banner_url }}'); {{ ThemeModule::data()->inline_rules }}">
    @if (isset(ThemeModule::data()->icon))
        <div class="banner-icon" style="{{ ThemeModule::data()->icon->inline_rules->base }}">
            <img src="{{ ThemeModule::data()->icon->url }}" alt="banner-icon" style="{{ ThemeModule::data()->icon->inline_rules->img }}"/>
        </div>
    @endif

    @if (isset(ThemeModule::data()->accessory))
        <div class="banner-accessory" style="{{ ThemeModule::data()->accessory->inline_rules->base }}">
            <img src="{{ ThemeModule::data()->accessory->url }}" alt="banner-accessory" style="{{ ThemeModule::data()->accessory->inline_rules->img }}"/>
        </div>
    @endif
</div>