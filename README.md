130words
======

A secure journaling website that encourages writing at least 130 words every day.

Deploy
------

First Time Only:
`export SYMFONY_ENV=prod`

```
composer install --no-dev --optimize-autoloader
bin/console doctrine:schema:update --force
bin/console cache:clear --env=prod --no-debug
bin/console server:start
```

http://symfony.com/doc/current/deployment.html
