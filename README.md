# Humans Game

Humans Game is a game about humans.  It is an emergent world experiment.  It explores minimal foundations for maximum emergent value.

## Setup

```
git clone <thisRepository>
composer install
bin/console doctrine:schema:update --force
bin/console cache:clear --env=prod
bin/console server:start
```

## To Do
- [x] User Registration
- [x] Human Creation
- [ ] Pixel Farm
- [ ] Letter Game
- [ ] Public API