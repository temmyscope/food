## Foodics App (Code Assessment)

- Requirements To Run Application: Docker

- This is the foodics API

### Set Up

```sh
git clone https://github.com/temmyscope/foodics.git
```

- After cloning the repo, update the `.env` file with the appropriate information, using `.env.example` as guideline
- ***Note: `ADMIN_EMAIL` env key is the address you want all restock reminder to be sent***

### Run Code

- With Docker
```sh
docker compose --env-file .env up --build
```

- Without Docker (Start Server)
```sh
php artisan octane:start --workers=auto --task-workers=auto --server=swoole --host=0.0.0.0 --port=8080
```

- Without Docker (Run Queue in new terminal)
```sh
php artisan queue:work --verbose --tries=3 --timeout=90
```

### Run Tests

- With Docker

```sh

```

- Without Docker
```sh
php artisan test
```

### System Requirements

- Application Requirements:
 - If you choose to run without using `docker-compose` and the preconfigured `Dockerfile`, then the following are the system requirements needed to run this application successfully:

 - PHP >= 8.1
 - Swoole PHP extension >= 4.5，with `swoole.use_shortname` set to `Off` in your `php.ini`
 - JSON PHP extension
 - Pcntl PHP extension
 - PDO PHP extension
 - Redis PHP extension
 - Composer
 - Relational Database (MySQL preferrably, PostgresQL)
 - Redis Server

- Our system should meet the following requirements:

  - Functional requirements:

    - Creation of Order.
    - Update Ingredient Stocks.
    - Notify Admin Of Ingredient Qty Level (when equal to or below 50% once).

  - Non-functional requirements:

    - The system should be scalable and efficient.

### Design Pattern and Architecture

- The Application was built using the Repository Design Pattern; which follows the Clean Code Architecture quite nicely (maybe not so religiously but to a large extent).


### Models

- The Model Structure below is neither a syntactically correct PHP code nor is it the actual structure of a model; but it is provided to give a mental map of each model, its schema and the roles each field plays


```php

class Ingredient {

  id: int -> primary 
  name: string -> unique
  initial_qty: float -> default(0) #In grams -> always convert kg to g  (where 1kg -> 1000g)
  available_qty: float -> default(0) #In grams -> always convert kg to g (where 1kg -> 1000g)
  needs_restock: enum(true, false) -> default(false)
  timestamp: string -> datetime #contains the updated_at & created_at fields

  belongsToMany: Product

}

class Product {

  id: int -> primary 
  name: string -> unique
  timestamp: string -> datetime #contains the updated_at & created_at fields

  belongsToMany: Ingredient

}

#This Model/Schema is used as an intermediate Model linking the Products and Ingredients

class ProductIngredient {

  product_id: int -> index, foreign (Product)
  ingredient_id: int -> index, foreign (Ingredient)
  qty: float -> default(0) #In grams -> always convert kg to g (where 1kg -> 1000g)

  timestamp: string -> datetime #contains the updated_at & created_at fields

}

class Order {
  id: int -> primary
  timestamp: string -> datetime #contains the updated_at & created_at fields

  hasManyThrough: Product, OrderItem
}

class OrderItem {
  order_id: int -> index, foreign (Order)
  product_id: int -> index, foreign (Product)
  qty: int -> default(1)
  timestamp: string -> datetime #contains the updated_at & created_at fields

  belongsTo: Order
}
```

### Conventions

  - All endpoints should return a JSON encoded data that at the least contain the accurate HTTP Response code and status (boolean)
  - Multi-name tables/schema/models are chosen based on a `logical derivative` sense rather than `alphatical order`, as this is more convenient (in my opinion) for the sake of maintenance

### Important Notes

- In a real life application where users order from their account, other fields such as `price`, `user_id`, etc. would be required on the `Order` Model.

- Also, a `meta` json might be needed (not compulsorily) on the `Order` schema - that would be typecasted to array on retrieval from the database; it would contain data that may be useful in printing a receipt e.g. Name of customer, etc.)

### Caveats

- Would be nice to use `laravel sail` but I have a preferred `openswoole/swoole` docker container, hence I would be using `docker` and `docker compose` directly instead of `sail`

- Docker was not required in the assessment but it's the way to go if we're to reduce inconsistencies across environments and add reasonable amount of automation