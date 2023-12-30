<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Asatru PHP - Exception</title>

        <style>
            html, body {
                width: 100%;
                height: 100%;
				margin: 0 auto;
                background-color: rgb(255, 255, 255);
                color: rgb(0, 0, 0);
				overflow-y: hidden;
            }
			
			.ex_image {
				position: relative;
				display: inline-block;
				background-image: url('<?= asset('img/pattern.jpg')?>');
				background-size: cover;
				background-repeat: no-repeat;
				width: 30%;
				height: 100%;
			}

            @media screen and (max-width: 1087px) {
                .ex_image {
                    display: none;
                }
            }

            .ex_box {
                position: absolute;
				display: inline-block;
                width: 69%;
				height: 100%;
				overflow-y: auto;
            }

            @media screen and (max-width: 1087px) {
                .ex_box {
                    width: 100%;
                }
            }
			
			.ex_header_num {
                position: relative;
                margin-top: 5px;
                margin-left: 10px;
                margin-right: 10px;
                font-size: 5.0em;
                color: rgb(215, 50, 50);
            }
			
			.ex_header_text {
                position: relative;
                margin-top: -10px;
                margin-left: 10px;
                margin-right: 10px;
				margin-bottom: 20px;
                font-size: 2.0em;
                color: rgb(50, 50, 50);
            }

            .ex_origin {
                position: relative;
                margin-top: 5px;
                margin-left: 10px;
                margin-right: 10px;
                font-size: 1.2em;
            }

            .ex_msg {
                position: relative;
                margin-top: 5px;
                margin-left: 10px;
                margin-right: 10px;
                font-size: 1.2em;
            }

            .ex_msg strong {
                color: rgb(128, 0, 0);
            }
			
			.ex_refresh {
				position: relative;
				margin-left: 10px;
                margin-right: 10px;
				margin-top: 30px;
			}
			
			.ex_refresh a {
                width: 51px;
                padding-top: 10px;
				padding-bottom: 10px;
				padding-left: 50px;
				padding-right: 50px;
				color: rgb(235, 235, 235);
                background-color: rgb(45, 95, 245);
                border: 1px solid rgb(51, 63, 104);
				border-radius: 5px;
				text-decoration: none;
			}
			
			.ex_refresh a:hover {
				color: rgb(255, 255, 255);
                background-color: rgb(67, 111, 245);
				text-decoration: none;
			}

            .ex_trace_box {
                position: relative;
                margin-top: 35px;
                margin-left: 10px;
                margin-right: 10px;
                margin-bottom: 35px;
            }

            .ex_trace_title {
                position: relative;
                margin-left: 5px;
				font-size: 1.2em;
            }

            .ex_trace_content {
                position: relative;
                margin-top: 15px;
                margin-left: 15px;
                margin-right: 15px;
                margin-bottom: 15px;
                font-size: 1.0em;
				overflow-y: auto;
            }
			
			.ex_trace_content_col_1 {
				padding: 10px;
				background-color: rgb(135, 135, 135);
			}
			
			.ex_trace_content_col_2 {
				padding: 10px;
				background-color: rgb(200, 200, 200);
			}
        </style>
    </head>

    <body>
        <div class="ex_image"></div>

        <div class="ex_box">
            <div class="ex_header_num">500</div>
			
			<div class="ex_header_text">Internal Server Error</div>

            <div class="ex_origin">
                Exception at <strong><?= $exception->getFile(); ?>:<?= $exception->getLine(); ?></strong>
            </div>

            <div class="ex_msg">
                Reported error: <strong><?= $exception->getMessage(); ?></strong>
            </div>

            <div class="ex_refresh">
				<a href="javascript:void(0);" onclick="location.reload();">Refresh</a>
			</div>

            <div class="ex_trace_box">
                <div class="ex_trace_title">
                    Stack trace:
                </div>

                <div class="ex_trace_content"> 
                    <?php 
                        $stacktrace = $exception->getTrace();
                        $tableswitch = false;
                        $stackcounter = count($stacktrace) - 1;
                    ?>
                    
                    <?php foreach ($stacktrace as $item) { ?>
                        <div class="ex_trace_content_col_<?= (($tableswitch) ? '1' : '2') ?>">
                            #<?= $stackcounter ?> <?= ((isset($item['file'])) ? $item['file'] : 'internal function') ?><?= (isset($item['line']) ? '(' . $item['line'] . ')' : '') ?>: <?= isset($item['class']) ? $item['class'] . '::' : '' ?><?= $item['function'] ?>
                            <?php if ((isset($item['args'])) && (count($item['args']) > 0)) { ?>
                                (
                                <?php $argcnt = 0; ?>
                                <?php foreach ($item['args'] as $key => $arg) { ?>
                                    <?php 
                                        if (gettype($arg) === 'object') {
                                            echo get_class($arg);
                                        } else if (gettype($arg) === 'array') {
                                            echo 'array';
                                        } else if (gettype($arg) === 'string') {
                                            echo '\'' . $arg . '\'';
                                        } else if (gettype($arg) === 'integer') {
                                            echo 'int(' . $arg . ')';
                                        } else if (gettype($arg) === 'boolean') {
                                            echo 'bool(' . (($arg) ? 'true' : 'false') . ')';
                                        } else if (gettype($arg) === 'double') {
                                            echo 'double(' . $arg . ')';
                                        } else {
                                            echo gettype($arg);
                                        }
                                    ?>
                                    <?= (($argcnt <= count($item['args']) - 2) ? ', ' : '') ?>
                                    <?php $argcnt++ ?>
                                <?php } ?>
                                )
                            <?php } ?>
                        </div>
                    
                        <?php $tableswitch = !$tableswitch; ?>
                        <?php $stackcounter--; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>