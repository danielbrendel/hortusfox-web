<div class="columns">
	<div class="column is-2"></div>

	<div class="column is-8 is-image-container" style="background-image: url('{{ asset('img/plants.jpg') }}');">
		<div class="column-overlay">
			<h1>{{ __('app.chat') }}</h1>

            <h2 class="smaller-headline">{{ __('app.chat_hint') }}</h2>

            @include('flashmsg.php')

            <div class="margin-vertical">
                <form id="frmSendChatMessage" method="POST" action="{{ url('/chat/add') }}">
                    @csrf

                    <div class="field has-addons">
                        <div class="control is-stretched">
                            <input class="input is-input-dark" type="text" name="message">
                        </div>
                        <div class="control">
                            <a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('frmSendChatMessage').submit();">{{ __('app.send') }}</a>
                        </div>
                    </div>
                </form>
            </div>

            @if (env('APP_SHOWCHATONLINEUSERS', false))
                <div class="chat-user-list" id="chat-user-list"></div>
            @endif

            @if (isset($messages))
                <div class="chat" id="chat">
                    @foreach ($messages as $message)
                        <div class="chat-message {{ ($message->get('userId') == $user->get('id')) ? 'chat-message-right' : '' }}">
                            <div class="chat-message-user">
                                <div class="is-inline-block" style="color: {{ UserModel::getChatColorForUser($message->get('userId')) }};">{{ UserModel::getNameById($message->get('userId')) }}</div>
                                @if (ChatViewModel::handleNewMessage($user->get('id'), $message->get('id')))
                                    <div class="chat-message-new">{{ __('app.new') }}</div>
                                @endif
                            </div>

                            <div class="chat-message-content">
                                <pre>{{ $message->get('message') }}</pre>
                            </div>

                            <div class="chat-message-info">
                                {{ (new Carbon($message->get('created_at')))->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
		</div>
	</div>

	<div class="column is-2"></div>
</div>