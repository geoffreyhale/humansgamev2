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

## To Do
This section moved to `TODO.md`.

## Blog
This section moved to `BLOG.md`.

# Notes
- Firebase - Forget Firebase for now. Just use this for persistence. Users can re-register. This is alpha versions.