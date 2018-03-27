start-server-dbg:
	sudo php -dxdebug.remote_enable=1 -dxdebug.remote_autostart=On -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -S localhost:81 -t public/ index.php

start-server:
	sudo php -S localhost:81 -t public/ index.php

build:
	composer update
	composer install
	composer dump-autoload