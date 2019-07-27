#!/usr/bin/php

<?php

use RestfulWS\App;
use RestfulWS\Core\Components\Model\Category;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Factory\BookFactory;
use RestfulWS\Core\Components\Factory\CategoryFactory;

require_once __DIR__ . '/vendor/autoload.php';

$categoriesCount = !empty($argv[1])? $argv[1] : 5;
$booksCount = !empty($argv[2])? $argv[2] : 30;

$app = App::create();

$bookStorage = Book::getStorage();
$categoryStorage = Category::getStorage();
$faker = Faker\Factory::create();
$bookFactory = new BookFactory();
$categoryFactory = new CategoryFactory();

$bookStorage->dropSchema();
$categoryStorage->dropSchema();

$categoryStorage->ensureSchema();
$bookStorage->ensureSchema();

$categories = $categoryFactory->generate($categoriesCount, TRUE);
print "Created {$categoriesCount} categories." . PHP_EOL;
$books = $bookFactory->generate($booksCount, TRUE);
print "Created {$booksCount} books." . PHP_EOL;
foreach ($books as $book) {
  $random = $faker->numberBetween(0, $categoriesCount-1);
  $book->set('categories', [$categories[$random]])->save();
}
print "Mapped {$categoriesCount} categories to {$booksCount} books." . PHP_EOL;

$app->down();
