<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>
		
		<meta name="viewport" content="with=device-width, initial-scale=1.0"/>
		
		<style>
			html, body {
				width: 100%;
				max-width: 800px;
				background-color: rgb(31, 31, 31);
				color: rgb(150, 150, 150);
				margin: 0 auto;
				overflow-x: hidden;
				font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
			}

			.headline {
				width: 100%;
				padding: 10px;
				color: rgb(230, 230, 230);
				background-color: rgb(55, 55, 55);
			}

			.headline img {
                position: relative;
				width: 32px;
				height: 32px;
			}

			.headline a {
				position: relative;
				font-size: 2.0em;
				text-decoration: none;
				color: rgb(111, 205, 132);
				font-family: Gabriola;
				font-weight: bold;
			}

			.headline a:hover {
				text-decoration: none;
				color: rgb(111, 205, 132);
			}

			.content {
				width: 100%;
				padding: 10px;
				color: rgb(200, 200, 200);
				background-color: rgb(31, 31, 31);
			}

			.content p {
				width: 93%;
			}

			.content a {
				color: rgb(105, 159, 202);
				text-decoration: none;
			}

			.content a:hover {
				color: rgb(105, 159, 202);
				text-decoration: underline;
			}

			.footer {
				width: 100%;
				padding: 10px;
				color: rgb(150, 150, 150);
				background-color: rgb(55, 55, 55);
				font-size: 0.76em;
			}

			.footer p {
				width: 93%;
			}

			.footer small {
				font-size: 1.0em;
			}

			.footer a {
				color: rgb(150, 135, 73);
				text-decoration: none;
			}

			.footer a:hover {
				color: rgb(150, 135, 73);
				text-decoration: underline;
			}
		</style>

        @if (ThemeModule::hasMailStyles())
        <style>
            {{ ThemeModule::getMailStyles() }}
        </style>
        @endif
    </head>

    <body>
		<div class="headline">
			<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td><img src="{{ asset('logo.png') }}"/>&nbsp;&nbsp;</td>
						<td><a href="{{ url('/') }}">{{ app('workspace') }}</a></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="content">
			{%mail_content%}
		</div>
		
		<div class="footer">
            <p>
                <small><a href="{{ url('/') }}">{{ app('workspace') }}</a> &#x25CF; Powered by {{ env('APP_NAME') }}</small>
            </p>

            <p>
                <small><a href="{{ env('APP_GITHUB_URL') }}">{{ env('APP_NAME') }}</a> is open-sourced software licensed under the MIT license.</small>
            </p>
		</div>
    </body>
</html>