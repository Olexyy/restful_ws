<?php

namespace RestfulWS\Core\Components\Query;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface QueryAdapterInterface.
 *
 * @package RestfulWS\Core\Components\Query
 */
interface QueryAdapterInterface {

  /**
   * QueryAdapter constructor.
   *
   * @param QueryBuilderInterface $builder
   *   Builder.
   * @param $limit
   *   Query limit.
   */
  public function __construct(QueryBuilderInterface $builder, $limit);

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
  public static function create(QueryBuilderInterface $builder, $limit);

  /**
   * Returns query based on request params.
   *
   * @param Request $request
   *   Request.
   *
   * @return QueryBuilderInterface
   *   Query.
   */
  public function getQuery(Request $request);

  /**
   * Allowed operators.
   *
   * @return array
   *   Values.
   */
  public function getAllowedOperators();

}
