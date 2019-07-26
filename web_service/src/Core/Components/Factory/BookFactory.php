<?php

namespace RestfulWS\Core\Components\Factory;

use Faker\Factory;
use RestfulWS\Core\Components\Model\Book;

/**
 * Class BookFactory.
 *
 * @package RestfulWS\Core\Components\Factory
 */
class BookFactory implements ModelFactoryInterface {

  /**
   * Generator.
   *
   * @var \Faker\Generator
   */
  protected $factory;

  /**
   * BookFactory constructor.
   *
   * @param null|Factory $factory
   */
  public function __construct($factory = NULL) {

    $this->factory = $factory?: Factory::create();
  }

  /**
   * Generator.
   *
   * @param int $count
   *   Count of items to generate.
   * @param bool $save
   *   Save flag.
   *
   * @return array|Book[]|Book
   *   Generated models.
   */
  public function generate($count = 1, $save = FALSE) {

    $generated = [];
    while ($count) {
      $book = Book::create([
        'name' => $this->factory->text,
        'author' => $this->factory->name,
        'publisher' => $this->factory->company,
        'year' => $this->factory->numberBetween(1900, 2012),
        'words' => $this->factory->numberBetween(0, 100000),
        'price' => $this->factory->randomFloat(2, 1, 100000)
      ]);
      if ($save) {
        $book->save();
      }
      $generated[] = $book;
      $count--;
    }

    return count($generated) == 1 ? current($generated) : $generated;
  }
}
