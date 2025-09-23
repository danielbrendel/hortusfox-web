<h1>{{ __('app.admin_area') }}</h1>

@include('flashmsg.php')

<div class="tabs admin-tabs">
    <ul>
        <li class="admin-tab-environment {{ ((!isset($_GET['tab'])) || ((isset($_GET['tab'])) && ($_GET['tab'] === 'environment'))) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('environment');">{{ __('app.environment') }}</a></li>
        <li class="admin-tab-media {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'media')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('media');">{{ __('app.admin_media') }}</a></li>
        <li class="admin-tab-users {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'users')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('users');">{{ __('app.users') }}</a></li>
        <li class="admin-tab-locations {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'locations')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('locations');">{{ __('app.locations') }}</a></li>
        <li class="admin-tab-auth {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'auth')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('auth');">{{ __('app.auth') }}</a></li>
        <li class="admin-tab-attributes {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'attributes')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('attributes');">{{ __('app.attributes') }}</a></li>
        <li class="admin-tab-calendar {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'calendar')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('calendar');">{{ __('app.calendar') }}</a></li>
        <li class="admin-tab-mail {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'mail')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('mail');">{{ __('app.mail') }}</a></li>
        <li class="admin-tab-themes {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'themes')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('themes');">{{ __('app.themes') }}</a></li>
        <li class="admin-tab-backup {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'backup')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('backup');">{{ __('app.backup') }}</a></li>
        <li class="admin-tab-weather {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'weather')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('weather');">{{ __('app.weather') }}</a></li>
        <li class="admin-tab-api {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'api')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.switchAdminTab('api');">{{ __('app.api') }}</a></li>
        <li class="admin-tab-info {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'info')) ? 'is-active' : ''}}">
            <a href="javascript:void(0);" onclick="window.vue.switchAdminTab('info');">
                {{ __('app.info') }}
                
                @if (VersionModule::getCachedVersion() > config('version'))
                    <i class="is-indicator-tab"></i>
                @endif
            </a>
        </li>
    </ul>
</div>

<div class="admin-environment {{ ((isset($_GET['tab'])) && ($_GET['tab'] !== 'environment')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.environment') }}</h2>

    <form method="POST" action="{{ url('/admin/environment/save') }}">
        @csrf

        <div class="field">
            <label class="label">{{ __('app.workspace') }}</label>
            <div class="control">
                <input type="text" class="input" name="workspace" value="{{ app('workspace') }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.language') }}</label>
            <div class="control">
                <select class="input" name="lang">
                    @foreach (UtilsModule::getLanguageList() as $lang)
                        <option value="{{ $lang['ident'] }}" {{ (app('language') === $lang['ident']) ? 'selected' : ''}}>{{ $lang['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.timezone') }}</label>
            <div class="control">
                <select class="input" name="timezone">
                    @foreach ($timezone_identifiers as $timezone_identifier)
                        <option value="{{ $timezone_identifier }}" {{ (($timezone_identifier === $current_timezone) ? 'selected' : '') }}>{{ $timezone_identifier }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="scroller" value="1" {{ (app('scroller')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_scroller') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="quick_add" value="1" {{ (app('quick_add')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_quick_add') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablepwa" value="1" {{ (app('pwa_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.pwa_enable') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enabletasks" value="1" {{ (app('tasks_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_tasks') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enableinventory" value="1" {{ (app('inventory_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_inventory') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablecalendar" value="1" {{ (app('calendar_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_calendar') }}</span>
            </div>
        </div>

        <div><hr></div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablechat" value="1" {{ (app('chat_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_chat') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablesysmsgs" value="1" {{ (app('chat_system')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_system_messages') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="system_message_plant_log" value="1" {{ (app('system_message_plant_log')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.system_message_plant_log') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.online_time_limit') }}</label>
            <div class="control">
                <input type="number" class="input" name="onlinetimelimit" value="{{ app('chat_timelimit') }}" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="chatonlineusers" value="1" {{ (app('chat_showusers')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.show_chat_onlineusers') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="chattypingindicator" value="1" {{ (app('chat_indicator')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.show_chat_typingindicator') }}</span>
            </div>
        </div>

        <div><hr></div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablehistory" value="1" {{ (app('history_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_history') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.history_name') }}</label>
            <div class="control">
                <input type="text" class="input" name="history_name" value="{{ app('history_name') }}">
            </div>
        </div>

        <div><hr></div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="plantrec_enable" value="1" {{ (app('plantrec_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.plantrec_enable_label') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.plantrec_apikey_label') }}</label>
            <div class="control">
                <input type="text" class="input" name="plantrec_apikey" value="{{ app('plantrec_apikey', '') }}">
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="plantrec_quickscan" value="1" {{ (app('plantrec_quickscan')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.plantrec_quickscan_label') }}</span>
            </div>
        </div>

        <div><hr></div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablephotoshare" value="1" {{ (app('enable_media_share')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_photo_share') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.share_api_host') }}</label>
            <div class="control">
                <input type="text" class="input" name="custom_media_share_host" value="{{ share_api_host() }}">
            </div>
        </div>

        <div><hr></div>

        <div class="field">
            <label class="label">{{ __('app.cronpw') }}</label>
        </div>

        <div class="field has-addons">
            <div class="control is-stretched">
                <input type="text" class="input" name="cronpw" id="cronpw" value="{{ ((app('cronjob_pw') !== null) ? app('cronjob_pw') : '') }}">
            </div>
            <div class="control">
                <a class="button is-info" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_generate_new_token') }}')) { window.vue.generateNewToken(document.getElementById('cronpw'), this); }">{{ __('app.generate') }}</a>
            </div>
        </div>

        <div class="field belongs-to-previous-field">
            <small>{{ __('app.generate_cronpw_hint') }}</small>
        </div>

        <div><hr></div>

        <div class="field">
            <label class="label">{{ __('app.custom_head_code') }}</label>
            <div class="control">
                <textarea class="textarea" name="custom_head_code">{{ app('custom_head_code') }}</textarea>
            </div>
        </div>

        <div class="field belongs-to-previous-field">
            <small>{{ __('app.custom_head_code_hint') }}</small>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>
</div>

<div class="admin-media {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'media')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.admin_media') }}</h2>

    <form method="POST" action="{{ url('/admin/media/logo') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label class="label">{{ __('app.media_logo') }}</label>
            <div class="control">
                <input type="file" class="input" name="asset" accept=".png" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ url('/admin/media/banner') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label class="label">{{ __('app.media_banner') }}</label>
            <div class="control">
                <input type="file" class="input" name="asset" accept=".jpg,.jpeg" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ url('/admin/media/background') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label class="label">{{ __('app.media_background') }}</label>
            <div class="control">
                <input type="file" class="input" name="asset" accept=".jpg,.jpeg" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ url('/admin/media/overlay/alpha') }}">
        @csrf
        
        <div class="field">
            <label class="label">{{ __('app.background_overlay_alpha') }}</label>
            <div class="control">
                <input type="text" class="input" name="overlayalpha" value="{{ ((app('overlay_alpha')) ? app('overlay_alpha') : '0.5') }}" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ url('/admin/media/sound/message') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label class="label">{{ __('app.media_sound_message') }}</label>
            <div class="control">
                <input type="file" class="input" name="asset" accept=".wav" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>
</div>

<div class="admin-users {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'users')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.users') }}</h2>

    <div class="admin-users-list">
        @foreach ($user_accounts as $user_account)
            <div class="admin-user-account">
                <form method="POST" action="{{ url('/admin/user/update') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $user_account->get('id') }}"/>

                    <div class="admin-user-account-item admin-user-account-item-input">
                        <input type="text" class="input" name="name" value="{{ $user_account->get('name') }}"/>
                    </div>

                    <div class="admin-user-account-item admin-user-account-item-input">
                        <input type="email" class="input" name="email" value="{{ $user_account->get('email') }}"/>
                    </div>

                    <div class="admin-user-account-item admin-user-account-item-centered">
                        <input type="checkbox" name="admin" value="1" {{ ($user_account->get('admin')) ? 'checked' : '' }}/>&nbsp;<span>{{ __('app.admin') }}</span>
                    </div>

                    <div class="admin-user-account-actions">
                        <span class="admin-user-account-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-user-account-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_user_removal') }}')) { location.href = '{{ url('/admin/user/remove?id=' . $user_account->get('id')) }}'; }">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    @if ((isset($_GET['user_password'])))
        <div class="admin-users-created-password">{!! __('app.user_created_password', ['pw' => $_GET['user_password']]) !!}</div>
    @endif

    <div class="admin-users-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewUser = true;">{{ __('app.create') }}</a></span>
        <span><a class="button is-success button-margin-left" href="javascript:void(0);" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>'; window.vue.saveAllAttributes('.admin-users-list');">{{ __('app.save_all') }}</a></span>
    </div>
</div>

<div class="admin-locations {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'locations')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.locations') }}</h2>

    <div>
        <form id="location-image-upload-form" class="is-hidden" method="POST" action="{{ url('/admin/location/photo') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="ident" id="location-image-upload-ident"/>

            <input type="file" name="photo" id="location-image-upload-input" accept="image/*" onchange="if (this.files.length) { document.getElementById('admin-location-item-icon-input-' + document.getElementById('location-image-upload-ident').value).innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>'; document.getElementById('location-image-upload-form').submit(); return false; } else { document.getElementById('admin-location-item-icon-input-' + document.getElementById('location-image-upload-ident').value).innerHTML = '<i class=\'fas fa-image\'></i>'; }"/>
        </form>
    </div>

    <div class="admin-locations-list">
        @foreach ($locations as $location)
            <div class="admin-location">
                <form method="POST" action="{{ url('/admin/location/update') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $location->get('id') }}"/>

                    <div class="admin-location-item">#{{ $location->get('id') }}</div>

                    <div class="admin-location-item admin-location-item-input">
                        <input type="text" class="input" name="name" value="{{ $location->get('name') }}"/>
                    </div>

                    <div class="admin-location-item admin-location-item-input">
                        <div class="field has-addons">
                            <div class="control admin-location-control-icon">
                                <input type="text" class="input" name="icon" value="{{ $location->get('icon') ?? '' }}"/>
                            </div>
                            <div class="control">
                                <a class="button is-warning" id="admin-location-item-icon-input-{{ $location->get('id') }}" href="javascript:void(0);" onclick="document.getElementById('location-image-upload-ident').value = '{{ $location->get('id') }}'; document.getElementById('location-image-upload-input').click();"><i class="fas fa-image"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="admin-location-item admin-location-item-centered">
                        <input type="checkbox" name="active" value="1" {{ ($location->get('active')) ? 'checked' : '' }}/>&nbsp;<span>{{ __('app.active') }}</span>
                    </div>

                    <div class="admin-location-actions">
                        <span class="admin-location-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-location-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="document.getElementById('remove-location-id').value = {{ $location->get('id') }}; document.querySelectorAll('.remove-location-item-option').forEach((el) => { el.classList.remove('is-hidden') }); document.querySelector('#remove-location-item-{{ $location->get('id') }}').classList.add('is-hidden'); window.vue.bShowRemoveLocation = true;">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="admin-locations-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewLocation = true;">{{ __('app.add_location') }}</a></span>
        <span><a class="button is-success button-margin-left" href="javascript:void(0);" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>'; window.vue.saveAllAttributes('.admin-locations-list');">{{ __('app.save_all') }}</a></span>
    </div>
</div>

<div class="admin-auth {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'auth')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.auth') }}</h2>

    <form method="POST" action="{{ url('/admin/auth/proxy/save') }}">
        @csrf 

        <div class="field">
            <div class="control">
                <input type="checkbox" name="auth_proxy_enable" value="1" onclick="window.vue.toggleAdminAuthInfoMessages(this.checked, '.admin-auth-warning', '.admin-auth-caution');" {{ ((app('auth_proxy_enable')) ? 'checked' : '') }}/>&nbsp;<span>{{ __('app.auth_proxy_enable') }}</span>
            </div>
        </div>

        <div class="admin-auth-warning"><i class="fas fa-exclamation-triangle"></i>&nbsp;{{ __('app.auth_proxy_warning') }}</div>

        @if (!UtilsModule::isHTTPS())
        <div class="admin-auth-caution"><i class="fas fa-exclamation-triangle"></i>&nbsp;{{ __('app.auth_proxy_no_https_detected') }}</div>
        @endif 

        <div class="field">
            <label class="label">{{ __('app.auth_proxy_header_email') }}</label>
            <div class="control">
                <input type="text" class="input" name="auth_proxy_header_email" value="{{ app('auth_proxy_header_email') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.auth_proxy_header_username') }}</label>
            <div class="control">
                <input type="text" class="input" name="auth_proxy_header_username" value="{{ app('auth_proxy_header_username') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="auth_proxy_sign_up" value="1" {{ ((app('auth_proxy_sign_up')) ? 'checked' : '') }}/>&nbsp;<span>{{ __('app.auth_proxy_sign_up') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.auth_proxy_whitelist') }}</label>
            <div class="control">
                <textarea class="textarea" name="auth_proxy_whitelist">{{ app('auth_proxy_whitelist') ?? '' }}</textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" name="auth_proxy_hide_logout" value="1" {{ ((app('auth_proxy_hide_logout')) ? 'checked' : '') }}/>&nbsp;<span>{{ __('app.auth_proxy_hide_logout') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}" />
            </div>
        </div>
    </form>
</div>

<div class="admin-attributes {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'attributes')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.attributes') }}</h2>

    <div>
        <div class="field">
            <div class="control">
                <span><input type="checkbox" class="checkbox" value="1" id="admin-attributes-checkbox-allow-custom-attributes" onclick="window.vue.toggleAdminBoolSetting('allow_custom_attributes'); return false;" {{ ((app('allow_custom_attributes')) ? 'checked': '') }}>&nbsp;{{ __('app.allow_custom_attributes') }}</span><br/>
            </div>
        </div>
    </div>

    <hr/>

    <p>{{ __('app.attributes_schema_hint') }}</p>

    <div class="admin-attribute-schema-list" data-sortable="admin-attributes">
        @foreach ($global_attributes as $global_attribute)
            <div class="admin-attribute-schema" data-attribute-id="{{ $global_attribute->get('id') }}" draggable="true" style="cursor: move;">
                <form method="POST" action="{{ url('/admin/attribute/schema/edit') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $global_attribute->get('id') }}"/>

                    <div class="admin-attribute-schema-item">
                        <i class="fas fa-grip-vertical" style="color: #999; margin-right: 8px; cursor: grab;"></i>
                    </div>

                    <div class="admin-attribute-schema-item admin-attribute-schema-item-input">
                        <input type="text" class="input" name="label" value="{{ $global_attribute->get('label') }}"/>
                    </div>

                    <div class="admin-attribute-schema-item admin-attribute-schema-item-input">
                        <select class="input" name="datatype" id="edit-plant-attribute-datatype" onchange="window.vue.selectDataTypeInputField(this, document.querySelector('#field-custom-edit-attribute-content'));" required>
                            @foreach (CustPlantAttrModel::$data_types as $datatype)
                                <option value="{{ $datatype }}" {{ ($global_attribute->get('datatype') === $datatype) ? 'selected' : '' }}>{{ __('app.custom_attribute_datatype_' . $datatype) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-attribute-schema-item admin-attribute-schema-item-centered">
                        <input type="checkbox" name="active" value="1" {{ ($global_attribute->get('active')) ? 'checked' : '' }}/>&nbsp;<span>{{ __('app.active') }}</span>
                    </div>

                    <div class="admin-attribute-schema-actions">
                        <span class="admin-attribute-schema-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-attribute-schema-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_attribute_schema') }}')) { location.href = '{{ url('/admin/attribute/schema/remove?id=' . $global_attribute->get('id')) }}'; }">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="admin-attribute-schema-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewAttributeSchema = true;">{{ __('app.add_custom_attribute') }}</a></span>
        <span><a class="button is-success button-margin-left" href="javascript:void(0);" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>'; window.vue.saveAllAttributes('.admin-attribute-schema-list');">{{ __('app.save_all') }}</a></span>
    </div>

    <hr/>

    <p>{{ __('app.attributes_visibility_hint') }}</p>

    <div>
        @foreach ($plant_attributes as $plant_attribute)
        <div class="field">
            <div class="control">
                <span><input type="checkbox" class="checkbox" value="1" id="admin-attributes-checkbox-{{ $plant_attribute->get('name') }}" onclick="window.vue.toggleAdminPlantAttribute('{{ $plant_attribute->get('name') }}'); return false;" {{ (($plant_attribute->get('active')) ? 'checked': '') }}>&nbsp;{{ __('app.' . $plant_attribute->get('name')) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <hr/>

    <p>{{ __('app.custom_bulk_commands') }}</p>

    <div class="admin-bulk-commands-list">
        @foreach ($bulk_cmds as $bulk_cmd)
            <div class="admin-bulk-command">
                <form method="POST" action="{{ url('/admin/attributes/bulkcmd/edit') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $bulk_cmd->get('id') }}"/>

                    <div class="admin-bulk-command-item">#{{ $bulk_cmd->get('id') }}</div>

                    <div class="admin-bulk-command-item admin-bulk-command-item-input">
                        <input type="text" class="input" name="label" value="{{ $bulk_cmd->get('label') }}"/>
                    </div>

                    <div class="admin-bulk-command-item admin-bulk-command-item-input">
                        <input type="text" class="input" name="attribute" value="{{ $bulk_cmd->get('attribute') }}"/>
                    </div>

                    <div class="admin-bulk-command-item admin-bulk-command-item-input">
                        <input type="text" class="input" name="styles" value="{{ $bulk_cmd->get('styles') }}"/>
                    </div>

                    <div class="admin-bulk-command-actions">
                        <span class="admin-bulk-command-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-bulk-command-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_bulk_cmd') }}')) { location.href = '{{ url('/admin/attributes/bulkcmd/remove?id=' . $bulk_cmd->get('id')) }}'; }">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="admin-bulk-command-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewBulkCmd = true;">{{ __('app.add_bulk_cmd') }}</a></span>
    </div>
</div>

<div class="admin-calendar {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'calendar')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.calendar') }}</h2>

    <div class="admin-calendar-classes-list">
        @foreach ($calendar_classes as $calendar_class)
            <div class="admin-calendar-class" id="admin-calendar-class-item-{{ $calendar_class->get('id') }}">
                <form method="POST" action="{{ url('/admin/calendar/class/edit') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $calendar_class->get('id') }}"/>

                    <div class="admin-calendar-class-item admin-calendar-class-item-input">
                        <input type="text" class="input" name="ident" value="{{ $calendar_class->get('ident') }}"/>
                    </div>

                    <div class="admin-calendar-class-item admin-calendar-class-item-input">
                        <input type="text" class="input" name="name" value="{{ $calendar_class->get('name') }}"/>
                    </div>

                    <div class="admin-calendar-class-item admin-calendar-class-item-input">
                        <input type="color" class="input" name="color_background" value="{{ UtilsModule::convertRgbToHex($calendar_class->get('color_background')) }}"/>
                    </div>

                    <div class="admin-calendar-class-item admin-calendar-class-item-input">
                        <input type="color" class="input" name="color_border" value="{{ UtilsModule::convertRgbToHex($calendar_class->get('color_border')) }}"/>
                    </div>

                    <div class="admin-calendar-class-actions">
                        <span class="admin-calendar-class-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-calendar-class-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_calendar_class') }}')) { window.vue.removeCalendarClass('{{ $calendar_class->get('id') }}'); }">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="admin-calendar-classes-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewCalendarClass = true;">{{ __('app.add_calendar_class') }}</a></span>
        <span><a class="button is-success button-margin-left" href="javascript:void(0);" onclick="this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>'; window.vue.saveAllAttributes('.admin-calendar-classes-list');">{{ __('app.save_all') }}</a></span>
    </div>
</div>

<div class="admin-mail {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'mail')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.mail') }}</h2>

    <form method="POST" action="{{ url('/admin/mail/save') }}">
        @csrf 

        <div class="field">
            <label class="label">{{ __('app.smtp_fromname') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_fromname" value="{{ app('smtp_fromname') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_fromaddress') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_fromaddress" value="{{ app('smtp_fromaddress') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_host') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_host" value="{{ app('smtp_host') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_port') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_port" value="{{ app('smtp_port') }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_username') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_username" value="{{ app('smtp_username') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_password') }}</label>
            <div class="control">
                <input type="password" class="input" name="smtp_password" value="{{ app('smtp_password') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_encryption') }}</label>
            <div class="control">
                <select class="input" name="smtp_encryption">
                    @foreach ($mail_encryption_types as $mail_encryption_type_key => $mail_encryption_type_value)
                        <option value="{{ $mail_encryption_type_value }}" {{ (($mail_encryption_type_value === app('smtp_encryption')) ? 'selected' : '') }}>{{ $mail_encryption_type_key }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.mail_rp_address') }}</label>
            <div class="control">
                <input type="text" class="input" name="mail_rp_address" value="{{ app('mail_rp_address') ?? '' }}">
            </div>
        </div>

        <div class="field">
            <div class="control">
                <span>
                    <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>&nbsp;
                    <a class="button is-warning" href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_test_mail', ['mail' => $user->get('email')]) }}')) { window.vue.sendTestMail(this); } return false;">{{ __('app.send_test_mail') }}</a>
                </span>
            </div>
        </div>
    </form>
</div>

<div class="admin-themes {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'themes')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.themes') }}</h2>

    <p>{{ __('app.themes_hint') }}</p>

    <div class="field">
        <div class="control">
            <input type="file" class="input" id="theme_import_file" accept=".zip"/>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button type="button" class="button is-link" onclick="window.vue.startThemeImport(document.getElementById('theme_import_file'), this);">{{ __('app.import') }}</button>
        </div>
    </div>

    <div class="admin-themes-result is-hidden" id="themes-import-result">{{ __('app.theme_import_successful') }}</div>

    <hr/>

    <div class="admin-themes-list">
        <h2>{{ __('app.theme_list') }}</h2>

        <table>
            <thead>
                <tr>
                    <td>{{ __('app.theme_name') }}</td>
                    <td>{{ __('app.theme_version') }}</td>
                    <td>{{ __('app.theme_author') }}</td>
                    <td>{{ __('app.theme_contact') }}</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($themes as $theme)
                    <tr id="admin-themes-list-item-{{ $theme->name }}">
                        <td>{{ $theme->name }}</td>
                        <td>{{ $theme->version }}</td>
                        <td>{{ $theme->author }}</td>
                        <td>{{ $theme->contact }}</td>
                        <td class="admin-theme-list-right"><a href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_theme_removal') }}')) { window.vue.removeTheme('{{ $theme->name }}'); }"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="admin-backup {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'backup')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.export') }}</h2>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="locations" value="1" checked/>&nbsp;<span>{{ __('app.locations') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="plants" value="1" checked/>&nbsp;<span>{{ __('app.plants') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="gallery" value="1" checked/>&nbsp;<span>{{ __('app.gallery') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="tasks" value="1" checked/>&nbsp;<span>{{ __('app.tasks') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="inventory" value="1" checked/>&nbsp;<span>{{ __('app.inventory') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="calendar" value="1" checked/>&nbsp;<span>{{ __('app.calendar') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button type="button" class="button is-link" onclick="window.vue.startBackup(this, document.getElementById('plants').checked, document.getElementById('gallery').checked, document.getElementById('tasks').checked, document.getElementById('inventory').checked, document.getElementById('calendar').checked);">{{ __('app.export') }}</button>
        </div>
    </div>

    <div class="admin-backup-result is-hidden" id="export-result"><i class="fas fa-download"></i>&nbsp;<a href="{{ url('/') }}"></a></div>

    <hr/>

    <form method="POST" action="{{ url('/admin/backup/cronjob/save') }}">
        @csrf

        <div class="field">
            <div class="control">
                <input type="checkbox" name="auto_backup" value="1" {{ ((app('auto_backup')) ? 'checked' : '') }}/>&nbsp;<span>{{ __('app.auto_backup') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.auto_backup_cronjob_url') }}</label>
            <div class="control">
               <input type="text" class="input" value="{{ url('/cronjob/backup/auto') }}" readonly/>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.backup_path') }}</label>
            <div class="control">
                <input type="text" class="input" name="backup_path" value="{{ app('backup_path') ?? '' }}"/>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-link" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>

    <hr/>

    <h2>{{ __('app.import') }}</h2>

    <div class="field">
        <div class="control">
            <input type="file" class="input" id="backup_file" accept=".zip"/>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="locationsi" value="1" checked/>&nbsp;<span>{{ __('app.locations') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="plantsi" value="1" checked/>&nbsp;<span>{{ __('app.plants') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="galleryi" value="1" checked/>&nbsp;<span>{{ __('app.gallery') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="tasksi" value="1" checked/>&nbsp;<span>{{ __('app.tasks') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="inventoryi" value="1" checked/>&nbsp;<span>{{ __('app.inventory') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="checkbox" id="calendari" value="1" checked/>&nbsp;<span>{{ __('app.calendar') }}</span>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button type="button" class="button is-link" onclick="window.vue.startImport(this, document.getElementById('backup_file'), document.getElementById('locationsi').checked, document.getElementById('plantsi').checked, document.getElementById('galleryi').checked, document.getElementById('tasksi').checked, document.getElementById('inventoryi').checked, document.getElementById('calendari').checked);">{{ __('app.import') }}</button>
        </div>
    </div>

    <div class="admin-backup-result is-hidden" id="import-result">{{ __('app.import_successful') }}</div>
</div>

<div class="admin-weather {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'weather')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.weather') }}</h2>

    <form method="POST" action="{{ url('/admin/weather/save') }}">
        @csrf

        <div class="field">
            <div class="control">
                <input type="checkbox" name="owm_enable" value="1" {{ ((app('owm_enable')) ? 'checked': '') }}/>&nbsp;<span>{{ __('app.enable_weather') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.weather_api_key') }}</label>
            <div class="control">
                <input class="input" type="text" name="owm_apikey" value="{{ (app('owm_api_key') ?? '') }}"/>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.weather_latitude') }}</label>
            <div class="control">
                <input class="input" type="text" id="geo-latitude" name="owm_latitude" value="{{ (app('owm_latitude') ?? '') }}"/>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.weather_longitude') }}</label>
            <div class="control">
                <input class="input" type="text" id="geo-longitude" name="owm_longitude" value="{{ (app('owm_longitude') ?? '') }}"/>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.weather_unittype') }}</label>
            <div class="control">
                <select class="input" name="owm_unittype">
                    @foreach (WeatherModule::getUnitTypes() as $unit_type => $unit_name)
                        <option value="{{ $unit_type }}" {{ (($unit_type === app('owm_unittype')) ? 'selected' : '') }}>{{ $unit_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.weather_cache') }}</label>
            <div class="control">
                <input class="input" type="text" name="owm_cache" value="{{ (app('owm_cache') ?? '300') }}"/>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>&nbsp;<a class="button is-link" href="javascript:void(0);" onclick="window.vue.acquireGeoPosition(document.getElementById('geo-latitude'), document.getElementById('geo-longitude'), this);">{{ __('app.weather_autodetect_latlong') }}</a>
            </div>
        </div>
    </form>
</div>

<div class="admin-api {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'api')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.api') }}</h2>

    <p>{{ __('app.admin_api_hint') }}</p>

    <a class="button is-link" href="{{ url('/admin/api/add') }}">{{ __('app.add') }}</a>

    <div>
        @if ((is_countable($api_keys)) && (count($api_keys) > 0))
        <table>
            <thead>
                <tr>
                    <td>{{ __('app.token') }}</td>
                    <td>{{ __('app.toggle') }}</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($api_keys as $api_key)
                <tr>
                    <td><span id="api-key-{{ $api_key->get('id') }}">{{ $api_key->get('token') }}</span>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="window.vue.copyToClipboard(document.getElementById('api-key-{{ $api_key->get('id') }}').innerText);"><i class="far fa-copy"></i></a></td>
                    <td><input type="checkbox" id="api-key-checkbox-{{ $api_key->get('id') }}" value="1" onclick="window.vue.toggleApiKey({{ $api_key->get('id') }}); return false;" {{ (($api_key->get('active')) ? 'checked': '') }}/>&nbsp;{{ __('app.active') }}</td>
                    <td><a href="javascript:void(0);" onclick="if (confirm('{{ __('app.confirm_remove_api_key') }}')) { location.href = '{{ url('/admin/api/' . $api_key->get('token') . '/remove') }}'; }"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<div class="admin-info {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'info')) ? 'is-hidden' : ''}}">
    <div class="admin-info-version">
        <div class="margin-bottom">{{ __('app.hortusfox_version', ['version' => config('version')]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.php_version', ['version' => phpversion()]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.mysql_version', ['version' => VersionModel::getSqlVersion()]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.server_system_info', ['osn' => php_uname('s'), 'osv' => php_uname('v'), 'mt' => php_uname('m')]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.server_timezone', ['time' => date('Y-m-d H:i') . ' (' . date_default_timezone_get() . ')']) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.render_time', ['time' => round(microtime(true) - ASATRU_START, 4)]) }}</div>
    </div>

    @if (env('APP_SERVICE_URL'))
    <div class="admin-info-support">
        <a class="button is-success" href="{{ env('APP_SERVICE_URL') }}/support" target="_blank"><i class="fas fa-headset"></i>&nbsp;{{ __('app.admin_support') }}</a>
    </div>
    @endif

    @if (env('APP_GITHUB_URL'))
    <div class="admin-info-github">
        <a class="button is-info" href="{{ env('APP_GITHUB_URL') }}"><i class="fab fa-github"></i>&nbsp;{{ __('app.github_repository') }}</a>
    </div>
    @endif

    @if (env('APP_GITHUB_SPONSOR'))
    <div class="admin-info-sponsor">
        <a class="button is-info" href="{{ env('APP_GITHUB_SPONSOR') }}"><i class="far fa-heart"></i>&nbsp;{{ __('app.donation_sponsoring') }}</a>
    </div>
    @endif

    @if (env('APP_DONATION_KOFI'))
    <div class="admin-info-donation">
        <a class="button is-info" href="{{ env('APP_DONATION_KOFI') }}"><i class="fas fa-coffee"></i>&nbsp;{{ __('app.donation_kofi') }}</a>
    </div>
    @endif

    <div class="admin-info-social">
        @if (env('APP_SOCIAL_DISCORD'))
        <a class="button admin-info-social-button is-social-discord" href="{{ env('APP_SOCIAL_DISCORD') }}" target="_blank"><i class="fab fa-discord"></i>&nbsp;{{ __('app.link_discord') }}</a>
        @endif
        
        @if (env('APP_SOCIAL_BLUESKY'))
        <a class="button admin-info-social-button is-social-bluesky" href="{{ env('APP_SOCIAL_BLUESKY') }}" target="_blank"><i class="fab fa-bluesky"></i>&nbsp;{{ __('app.link_bluesky') }}</a>
        @endif

        @if (env('APP_SERVICE_URL'))
        <a class="button admin-info-social-button is-social-videos" href="{{ env('APP_SERVICE_URL') }}/videos" target="_blank"><i class="fas fa-play-circle"></i>&nbsp;{{ __('app.link_videos') }}</a>
        @endif
    </div>

    <div class="admin-info-extensions">
        <h3>{{ __('app.extensions') }}</h3>

        <div>
            <a href="javascript:void(0);" onclick="document.getElementById('extenions-table').classList.toggle('is-hidden'); if (document.getElementById('extenions-table').classList.contains('is-hidden')) { this.innerHTML = `<i class='fas fa-plus'></i>&nbsp;{{ __('app.expand') }}`; } else { this.innerHTML = `<i class='fas fa-minus'></i>&nbsp;{{ __('app.collapse') }}`; }">
                <i class="fas fa-plus"></i>&nbsp;{{ __('app.expand') }}
            </a>
        </div>

        <div id="extenions-table" class="is-hidden">
            <table>
                <thead>
                    <tr>
                        <td>#</td>
                        <td>{{ __('app.name') }}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach (get_loaded_extensions() as $key => $php_extension)
                    <tr>
                        <td>#{{ $key }}</td>
                        <td>{{ $php_extension }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-action-clear-cache">
        <a class="button is-warning" href="javascript:void(0);" onclick="window.vue.clearCache(this);">{{ __('app.clear_cache') }}</a>
    </div>

    <?php if ((!empty($new_version)) && (!empty($current_version))) { ?>
        <div class="version-info">
            <?php if ($new_version > $current_version) { ?>
                <i class="fas fa-download"></i>&nbsp;{!! __('app.new_version_available', ['current_version' => $current_version, 'new_version' => $new_version, 'url' => env('APP_GITHUB_URL') . '/releases/tag/v' . $new_version]) !!}
            <?php } else { ?>
                <i class="fas fa-check"></i>&nbsp;{{ __('app.no_new_version_available') }}
            <?php } ?>
        </div>
    <?php } else { ?>
        <?php if (env('APP_SERVICE_URL')) { ?>
            <div class="version-check">
                <a class="button is-link" href="{{ url('/admin?cv=1&tab=info') }}">{{ __('app.check_for_new_version') }}</a>
                @if (VersionModule::getCachedVersion() > config('version'))
                    <i class="is-indicator-tab is-indicator-button"></i>
                @endif
            </div>
        <?php } ?>
    <?php } ?>
</div>
