@if (FlashMessage::hasMsg('error'))
    <div id="error-message">
        <article class="message is-danger">
            <div class="message-header">
                <p>{{ __('app.error') }}</p>
                <button class="delete" aria-label="delete" onclick="document.getElementById('error-message').style.display = 'none';"></button>
            </div>
            <div class="message-body">
                {!! FlashMessage::getMsg('error') !!}
            </div>
        </article>
    </div>
@endif