<!-- Error 404 yield file -->

<div class="outer">
	<div class="inner">
        <div class="title">
            <h1>Error 404</h1>
        </div>

        <div class="text">
            <p>The requested resource {{ $_SERVER['REQUEST_URI'] }} was not found on the server.</p>
        </div>

        <div class="links">
            <button type="button" class="button btn-col-contact" onclick="location.href = '{{ url('/') }}';">Go home</button>
        </div>
    </div>
</div>