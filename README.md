130words
======

A secure journaling website.

Deploy
------

`composer install;`
`bin/console doctrine:schema:update --force;`
`bin/console cache:clear`
`bin/console server:start`