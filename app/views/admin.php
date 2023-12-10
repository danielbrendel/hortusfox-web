<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.admin_area') }}</h1>

            @include('flashmsg.php')

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
		</div>
	</div>

	<div class="column is-2"></div>
</div>