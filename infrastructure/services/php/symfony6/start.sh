#!/bin/bash
service cron start &
# service supervisor start ; supervisorctl reread ; supervisorctl update &
/usr/local/bin/docker-php-entrypoint apache2-foreground
