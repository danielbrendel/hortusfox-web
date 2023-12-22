<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/background.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.admin_area') }}</h1>

            @include('flashmsg.php')

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
                        <a class="button is-link" href="{{ url('/admin?cv=1') }}">{{ __('app.check_for_new_version') }}</a>
                    </div>
                <?php } ?>
            <?php } ?>

			<div class="admin-environment">
                <h2>{{ __('app.environment') }}</h2>

                <form method="POST" action="{{ url('/admin/environment/save') }}">
                    @csrf

                    <div class="field">
                        <label class="label">{{ __('app.workspace') }}</label>
                        <div class="control">
                            <input type="text" class="input" name="workspace" value="{{ env('APP_WORKSPACE') }}">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.language') }}</label>
                        <div class="control">
                            <select class="input" name="lang">
                                @foreach (UtilsModule::getLanguageList() as $lang)
                                    <option value="{{ $lang }}" {{ (env('APP_LANG') === $lang) ? 'selected' : ''}}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="checkbox" class="checkbox" name="scroller" value="1" {{ (env('APP_ENABLESCROLLER')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_scroller') }}</span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="checkbox" class="checkbox" name="enablechat" value="1" {{ (env('APP_ENABLECHAT')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_chat') }}</span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.online_time_limit') }}</label>
                        <div class="control">
                            <input type="number" class="input" name="onlinetimelimit" value="{{ env('APP_ONLINEMINUTELIMIT') }}" required>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="checkbox" class="checkbox" name="chatonlineusers" value="1" {{ (env('APP_SHOWCHATONLINEUSERS')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.show_chat_onlineusers') }}</span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="checkbox" class="checkbox" name="chattypingindicator" value="1" {{ (env('APP_SHOWCHATTYPINGINDICATOR')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.show_chat_typingindicator') }}</span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="checkbox" class="checkbox" name="enablehistory" value="1" {{ (env('APP_ENABLEHISTORY')) ? 'checked': '' }}>&nbsp;<span>{{ __('app.enable_history') }}</span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.history_name') }}</label>
                        <div class="control">
                            <input type="text" class="input" name="history_name" value="{{ env('APP_HISTORY_NAME') }}">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.cronpw') }}</label>
                        <div class="control">
                            <input type="text" class="input" name="cronpw" value="{{ ((env('APP_CRONPW') !== null) ? env('APP_CRONPW') : '') }}">
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="submit" class="button is-success" value="{{ __('app.save') }}"/>
                        </div>
                    </div>
                </form>
            </div>

            <div class="admin-media">
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
            </div>

            <div class="admin-users">
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

            <div class="admin-locations">
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
		</div>
	</div>

	<div class="column is-2"></div>
</div>