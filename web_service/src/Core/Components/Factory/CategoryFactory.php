<?php

namespace RestfulWS\Core\Components\Factory;

use Faker\Factory;
use RestfulWS\Core\Components\Model\Category;

/**
 * Class CategoryFactory.
 *
 * @package RestfulWS\Core\Components\Factory
 */
class CategoryFactory implements ModelFactoryInterface {

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
   * @return array|Category[]|Category
   *   Generated models.
   */
  public function generate($count = 1, $save = FALSE) {

    $generated = [];
    while ($count) {
      $category = Category::create([
        'name' => $this->factory->text,
      ]);
      if ($save) {
        $category->save();
      }
      $generated[] = $category;
      $count--;
    }

    return count($generated) == 1 ? current($generated) : $generated;
  }
}
