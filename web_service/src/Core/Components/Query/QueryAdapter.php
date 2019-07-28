<?php

namespace RestfulWS\Core\Components\Query;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class QueryAdapter.
 *
 * @package RestfulWS\Core\Components\Query
 */
class QueryAdapter implements QueryAdapterInterface {

  /**
   * @var QueryBuilderInterface
   */
  protected $builder;

  /**
   * @var int
   */
  protected $limit;

  /**
   * QueryAdapter constructor.
   *
   * @param QueryBuilderInterface $builder
   *   Builder.
   * @param $limit
   *   Query limit.
   */
  public function __construct(QueryBuilderInterface $builder, $limit) {

    $this->builder = $builder;
    $this->limit = $limit;
  }

  /**
   * Inline constructor.
   *
   * @param QueryBuilderInterface $builder
   *   Builder.
   * @param $limit
   *   Query limit.
   *
   * @return $this
   *   Instance.
   */
  public static function create(QueryBuilderInterface $builder, $limit) {

    return new static($builder, $limit);
  }

  /**
   * Returns query based on request params.
   *
   * @param Request $request
   *   Request.
   *
   * @return QueryBuilderInterface
   *   Query.
   */
  public function getQuery(Request $request) {

    if ($filter = $request->query->get('filter')) {
      if (is_array($filter)) {
        foreach ($filter as $item) {
          if (is_array($item)) {
            if (array_key_exists('name', $item)) {
              $name = $item['name'];
              $value = array_key_exists('value', $item) ? $item['value'] : NULL;
              $operator = array_key_exists('op', $item) ? $item['op'] : current($this->getAllowedOperators());
              $operator = in_array($operator, $this->getAllowedOperators()) ? $operator : current($this->getAllowedOperators());
              $this->builder->addWhere($name, $value, $operator);
            }
          }
        }
      }
    }
    $this->builder->setLimit($this->limit);
    if ($page = $request->query->getInt('page', 0)) {
      $offset = $page > 0 ? ($page -1) * $this->limit : 0;
      $this->builder->setOffset($offset);
    }

    return $this->builder;
  }

  /**
   * Allowed operators.
   *
   * @return array
   *   Values.
   */
  public function getAllowedOperators() {

    return ['=', 'LIKE', 'IN', '>', '<', '>=', '<=', '<>', 'IS NULL', 'IS NOT NULL'];
  }

}
