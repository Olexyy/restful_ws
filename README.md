## Restful Web Service `restful_ws`

Provides very basic restful web service.

### Tips:
 - Uses composer autoloader and some Symfony dependencies so `composer install` should be run in `web_service` scope.
 - Uses `docker-compose` so `docker` and `docker-compose` should be installed.
 - Ports are `8000` for Apache and `3306` Mysql.
 - To run project in container run `docker-compose up` in root scope.
 - Default health check endpoint is `http://localhost:8000`.
 - Run tests: `docker-compose exec php php ./vendor/bin/phpunit`.

### Specs:
 - Examples:
 ```
 curl localhost:8000/api/books -d '{"title":"Harry Potter The Chamber of Secrets", "author":"J. K. Rowling", "words": 10000, "year": 1998}' -X POST
 curl localhost:8000/api/books
 curl localhost:8000/api/books/1
 curl localhost:8000/api/books/1 -d '{"words": 10}' -X PATCH
 curl localhost:8000/api/books/1 -X DELETE
 ```
 
 ### TODO:
 - search queries (related method not yet implemented)
 - tests for search queries
 - test resource controllers
 - coding standards
 
