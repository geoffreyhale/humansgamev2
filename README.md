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

## Blog

### Existential Checklist

**TMI:**

Started humans.game v2 by cloning 130words which includes (User-)identity, persistence (Doctrine, sql).

**TLDR:**

 - [x] Concept (conceptual seed)
 - [x] Existence (2017-11-22)
 - [x] Identity (gimme)
 - [x] Persistence (w git clone 130words as basis)
 - [ ] Value*
 - [ ] Ownership
 - [ ] Market (value exchange)
 - [ ] Emergence...
 
#### Emergence
 
 **Experience of:**
 - [ ] Growth (e.g. from Farming growing Human's Inventory / share of Farming Map)



#### Value*

**Emergent Value Systems**

Build systems that yield emergent value.

- [ ] Farming

##### Farming

**TMI:**

Farming := producing sustenance supports (/ is req by/for) "persistence"/User-existence.

Using minimal emergent system to simulate a farm-like experience might be like:

###### Pixel Farm

1. human selects one pixel per day on shared map
   - (select = "plant" (gain pixel ownership, lose "seed" from inventory))
   - (pixel = "seed" (seed-container))
2. - can select iff adjacent to existing pixel
3. (Inventory (ability to **has**) and) "Harvesting"  - ability to retrieve planted crops.
4. Enable experience of (human- (inventory- (value- ))) **growth**:
   - "harvesting" ONE (("ripe"?) pixel) yields TWO
5. Harvesting removes pixel ownership
   - Allows recycling, and relocation with consolidation.
     - Example: If you're pixel is surrounded by other farms, you can harvest your pixel and go plant all your pixels out at the edge of the map.
       - Expect emergent result: human-driven content-organizing expanding 2D map (pixel farm).
         - (title: Pixel Farm)
       - (good misplaced idea: in future ripeness implementation yields: one if unripe, 2 if ripe)

# Notes
- Firebase - Forget Firebase for now. Just use this for persistence. Users can re-register. This is alpha versions.