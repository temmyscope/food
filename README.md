## Foodics App (Code Assessment)

- Requirements To Run Application: Docker

- This is the foodics API

### Set Up

```sh
git clone https://github.com/temmyscope/foodics.git
```

- After cloning the repo, update the `.env` file with the appropriate information, using `.env.example` as guideline

- ***Note: `ADMIN_EMAIL` env key is the address you want all restock reminder to be sent***

- ***Note: the values in the `.env.example` are sufficient for running this application using the preconfigured Dockerfile; only `APP_KEY` needs to be updated ***

- Generate new laravel app key: `php artisan key:generate`

### Run Code (With Docker)

- With Docker
```sh
# build docker
docker compose --env-file .env build

# run it
docker compose --env-file .env up -d

# Enter into container shell by running: 
docker exec -it foodics sh  

# run migrations within container shell
php artisan migrate; 

# seed db with dataset within container shell
php artisan db:seed;

# flush queue within container shell
php artisan queue:flush
```

### To view sent emails

- Open (localhost)[!http://localhost:8025/] in the browser

### To view queue logs

- Run the following in the terminal while application is up: ```docker logs foodics-queue-1```


### Run Code (Without Docker)

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

  - Enter into App shell in interactive session
  ```sh
  docker exec -it foodics sh
  ```
  - Run test in shell
  ```sh
  php artisan test
  ```

- Without Docker
```sh
php artisan test
```

***If there are errors, please ensure migrations have run successfully first by running `php artisan migrate:status`, if there are pending migrations, then run: `php artisan migrate`; after which the tests can be re-run***

### Send Request
- see `request.http` file in the root folder to send requests

- if request was successful, it should return `order` field containing all products ordered

### System/Application Requirements

 - If you choose to run without using `docker-compose` and the preconfigured `Dockerfile`, then the following are the system requirements needed to run this application successfully:

 - PHP >= 8.1
 - Swoole PHP extension >= 4.5，with `swoole.use_shortname` set to `Off` in your `php.ini`
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
  needs_restock: enum('true', 'false') -> default('false')
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
  quantity: float -> default(0) #In grams -> always convert kg to g (where 1kg -> 1000g)

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
  quantity: int -> default(1)
  timestamp: string -> datetime #contains the updated_at & created_at fields

  belongsTo: Order
}
```

### Conventions

  - All endpoints should return a JSON encoded data that at the least contain the accurate HTTP Response code and a status value (boolean). The exception(s) to this rule (i.e. returning status value) is/are `Exception`(s) - no pun intended.
  
  - All variables make use of `snake_case`, properties, functions and method names make use of `camelCase`; while namespaces and class names make use of `PascalCase`.

  - Multi-words tables/schema/models are derived based on a `logical derivative` sense rather than `alphatical order`, as this is more convenient (in my opinion) for the sake of maintenance. E.g. `product_ingredients` instead of `ingredient_products`, etc.

### Important Notes

- In a real life application where users order from their account, other fields such as `price`, `user_id`, etc. would be required on the `Order` Model.


### After-Thoughts, Caveats and Errors 

- I believe that some performance improvements can be done on `OrderRepository::create` method but at the moment I can't seem to wrap my head around it

- Would be nice to use `laravel sail` but I have a preferred `openswoole/swoole` docker container, hence I would be using `docker` and `docker compose` directly instead of `sail`

- Redis `conf` file configurations were added to prevent external connection to the redis server

- Docker was not required in the assessment but it's the way to go if we're to reduce inconsistencies across environments and add reasonable amount of automation

- I used `mailpit` docker image but you can use an external provider like [MailTrap](https://mailtrap.io/) and add necessary `.env` values

- While a docker configuration has been provided, the project can work outside Docker as long as there is a `redis` and `MySQL` database servers available and `.env` should be updated appropriately to prevent issues

- If I were to addd `nginx` to the project, with a few environment configuration updates, the project would be `production ready`.

- If you encounter this error `1030 Got error 168 - 'Unknown (generic) error from engine' from storage engine` while running the `php artisan migrate`, then it's a result of low storage left; first run `docker system prune -a  --volumes`, then rebuild container and rerun

- The database is persisted to the harddisk, hence if you need a fresh start when you rebuild the container, enter the app shell and run the following commands in this order: 
  - `php artisan migrate:rollback`
  - `php artisan migrate`
  - `php artisan db:seed`
