<h1>{{ __('app.admin_area') }}</h1>

@include('flashmsg.php')

<div class="tabs admin-tabs">
    <ul>
        <li class="admin-tab-environment {{ ((!isset($_GET['tab'])) || ((isset($_GET['tab'])) && ($_GET['tab'] === 'environment'))) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('environment');">{{ __('app.environment') }}</a></li>
        <li class="admin-tab-media {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'media')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('media');">{{ __('app.admin_media') }}</a></li>
        <li class="admin-tab-users {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'users')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('users');">{{ __('app.users') }}</a></li>
        <li class="admin-tab-locations {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'locations')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('locations');">{{ __('app.locations') }}</a></li>
        <li class="admin-tab-mail {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'mail')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('mail');">{{ __('app.mail') }}</a></li>
        <li class="admin-tab-backup {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'backup')) ? 'is-active' : ''}}"><a href="javascript:void(0);" onclick="window.vue.selectAdminTab('backup');">{{ __('app.backup') }}</a></li>
        <li class="admin-tab-info {{ ((isset($_GET['tab'])) && ($_GET['tab'] === 'info')) ? 'is-active' : ''}}">
            <a href="javascript:void(0);" onclick="window.vue.selectAdminTab('info');">
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
            <div class="control">
                <input type="checkbox" class="checkbox" name="scroller" value="1" {{ (app('scroller')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_scroller') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablepwa" value="1" {{ (app('pwa_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.pwa_enable') }}</span>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablechat" value="1" {{ (app('chat_enable')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_chat') }}</span>
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

        <div class="field">
            <div class="control">
                <input type="checkbox" class="checkbox" name="enablephotoshare" value="1" {{ (app('enable_media_share')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_photo_share') }}</span>
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.cronpw') }}</label>
        </div>

        <div class="field has-addons">
            <div class="control is-stretched">
                <input type="text" class="input" name="cronpw" id="cronpw" value="{{ ((app('cronjob_pw') !== null) ? app('cronjob_pw') : '') }}">
            </div>
            <div class="control">
                <a class="button is-info" href="javascript:void(0);" onclick="window.vue.generateNewToken(document.getElementById('cronpw'), this);">{{ __('app.generate') }}</a>
            </div>
        </div>

        <div class="field belongs-to-previous-field">
            <small>{{ __('app.generate_cronpw_hint') }}</small>
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

    <div class="admin-users-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewUser = true;">{{ __('app.create') }}</a></span>
    </div>
</div>

<div class="admin-locations {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'locations')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.locations') }}</h2>

    <div class="admin-locations-list">
        @foreach ($locations as $location)
            <div class="admin-location">
                <form method="POST" action="{{ url('/admin/location/update') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $location->get('id') }}"/>

                    <div class="admin-location-item admin-location-item-input">
                        <input type="text" class="input" name="name" value="{{ $location->get('name') }}"/>
                    </div>

                    <div class="admin-location-item admin-location-item-input">
                        <input type="text" class="input" name="icon" value="{{ $location->get('icon') }}"/>
                    </div>

                    <div class="admin-location-item admin-location-item-centered">
                        <input type="checkbox" name="active" value="1" {{ ($location->get('active')) ? 'checked' : '' }}/>&nbsp;<span>{{ __('app.active') }}</span>
                    </div>

                    <div class="admin-location-actions">
                        <span class="admin-location-action-item"><input type="submit" class="button is-success" value="{{ __('app.update') }}"/></span>
                        <span class="admin-user-action-item"><a class="button is-danger" href="javascript:void(0);" onclick="document.getElementById('remove-location-id').value = {{ $location->get('id') }}; document.querySelectorAll('.remove-location-item-option').forEach((el) => { el.classList.remove('is-hidden') }); document.querySelector('#remove-location-item-{{ $location->get('id') }}').classList.add('is-hidden'); window.vue.bShowRemoveLocation = true;">{{ __('app.remove') }}</a></span> 
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <div class="admin-locations-actions">
        <span><a class="button is-info" href="javascript:void(0);" onclick="window.vue.bShowCreateNewLocation = true;">{{ __('app.add_location') }}</a></span>
    </div>
</div>

<div class="admin-mail {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'mail')) ? 'is-hidden' : ''}}">
    <h2>{{ __('app.mail') }}</h2>

    <form method="POST" action="{{ url('/admin/mail/save') }}">
        @csrf 

        <div class="field">
            <label class="label">{{ __('app.smtp_fromname') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_fromname" value="{{ app('smtp_fromname') }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_fromaddress') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_fromaddress" value="{{ app('smtp_fromaddress') }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_host') }}</label>
            <div class="control">
                <input type="text" class="input" name="smtp_host" value="{{ app('smtp_host') }}">
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
                <input type="text" class="input" name="smtp_username" value="{{ app('smtp_username') }}">
            </div>
        </div>

        <div class="field">
            <label class="label">{{ __('app.smtp_password') }}</label>
            <div class="control">
                <input type="password" class="input" name="smtp_password" value="{{ app('smtp_password') }}">
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
            <div class="control">
                <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
            </div>
        </div>
    </form>
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
            <button type="button" class="button is-link" onclick="window.vue.startBackup(this, document.getElementById('plants').checked, document.getElementById('gallery').checked, document.getElementById('tasks').checked, document.getElementById('inventory').checked);">{{ __('app.export') }}</button>
        </div>
    </div>

    <div class="admin-backup-result is-hidden" id="export-result"><i class="fas fa-download"></i>&nbsp;<a href="{{ url('/') }}"></a></div>

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
            <button type="button" class="button is-link" onclick="window.vue.startImport(this, document.getElementById('backup_file'), document.getElementById('locationsi').checked, document.getElementById('plantsi').checked, document.getElementById('galleryi').checked, document.getElementById('tasksi').checked, document.getElementById('inventoryi').checked);">{{ __('app.import') }}</button>
        </div>
    </div>

    <div class="admin-backup-result is-hidden" id="import-result">{{ __('app.import_successful') }}</div>
</div>

<div class="admin-info {{ ((!isset($_GET['tab'])) || ($_GET['tab'] !== 'info')) ? 'is-hidden' : ''}}">
    <div class="admin-info-version">
        <div class="margin-bottom">{{ __('app.hortusfox_version', ['version' => config('version')]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.php_version', ['version' => phpversion()]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.mysql_version', ['version' => VersionModel::getSqlVersion()]) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.server_time', ['time' => date('Y-m-d H:i') . ' (' . date_default_timezone_get() . ')']) }}</div>
        <div class="admin-info-version-smaller">{{ __('app.render_time', ['time' => round(microtime(true) - ASATRU_START, 4)]) }}</div>
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
            </div>
        <?php } ?>
    <?php } ?>
</div>
