## Restful Web Service `restful_ws`

Provides very basic restful web service.

### Tips:
 - Uses composer autoloader and some Symfony dependencies so `composer install` should be run in `web_service` scope.
 - Uses `docker-compose` so `docker` and `docker-compose` should be installed.
 - Ports are `8000` for Apache and `3306` Mysql.
 - To run project in container run `docker-compose up` in root scope.
 - Default health check endpoint is `http://localhost:8000`.
 - Run tests: `docker-compose exec php php ./vendor/bin/phpunit` from root scope.
 - Seed database and ensure tables:
   `docker-compose exec php php seed.php 5 30`
   First param is used for `categories` count. Second is for `books` count.
   Params are not required.
   Expected output: 
   ```
   Created 10 categories.
   Created 50 books.
   Mapped 10 categories to 50 books.

   ```
 - Project uses Drupal 8 php coding standards.

### Specs:
 - Examples of access to resource:
 ```
 curl localhost:8000/api/books -d '{"title":"Harry Potter The Chamber of Secrets", "author":"J. K. Rowling", "words": 10000, "year": 1998}' -X POST
 curl localhost:8000/api/books
 curl localhost:8000/api/books/1
 curl localhost:8000/api/books/1 -d '{"words": 10}' -X PATCH
 curl localhost:8000/api/books/1 -X DELETE
 ```
 
  - Examples of query usage:
  ```
  // Get all books with category 2.
  http://localhost:8000/api/books?filter[1][name]=categories&filter[1][value]=2
  
  // Get all books with author John%20Smith.
  http://localhost:8000/api/books?filter[1][name]=author&filter[1][value]=John%20Smith
  
  // Get all books with name like Expedita.
  http://localhost:8000/api/books?filter[1][name]=name&filter[1][value]=Expedita&filter[1][op]=LIKE
  
  // Get 2 books with ids 16 and 17.
  http://localhost:8000/api/books?filter[1][name]=id&filter[1][value][]=16&filter[1][value][]=17&filter[1][op]=IN
  ```

