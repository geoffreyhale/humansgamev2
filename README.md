# Humans Game

Humans Game is an emergent value existence-simulation.

## Initial Setup

```
git clone <thisRepository>
```

## Deploy

```
composer install
bin/console doctrine:schema:update --force
bin/console cache:clear --env=prod
bin/console server:start
```