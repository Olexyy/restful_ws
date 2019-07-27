<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Factory\BookFactory;
use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Query\QueryBuilder;
use RestfulWS\Core\Components\Storage\StorageInterface;
use Tests\KernelTestCase;

/**
 * Class QueryBuilderTest.
 *
 * @package Tests\Unit
 */
class QueryBuilderTest extends KernelTestCase {

  /**
   * Generator.
   *
   * @var ModelFactoryInterface
   */
  protected $bookFactory;

  /**
   * Storage.
   *
   * @var StorageInterface
   */
  protected $bookStorage;

  /**
   * {@inheritdoc}
   */
  public function setUp() : void {

    parent::setUp();
    $this->bookFactory = new BookFactory();
    $this->bookStorage = Book::getStorage();
    $this->bookStorage->dropSchema();
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown(): void {

    parent::tearDown();
    $this->bookStorage->dropSchema();
  }

  public function testQueryBuilderGeneral() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEmpty($res);
  }

  public function testQueryBuilderLimitOffset() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->setLimit(10);
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books LIMIT 10 OFFSET 0;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEmpty($res);
    $queryBuilder->setOffset(10)->setLimit(100);
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books LIMIT 100 OFFSET 10;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEmpty($res);
    $queryBuilder->setOffset(0)->setLimit(0);
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEmpty($res);
  }

  public function testQueryBuilderSingleDefaultParam() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->addWhere('name', 'Smith');
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books WHERE books.name = :name_0;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEquals([':name_0' => 'Smith'], $res);
  }

  public function testQueryBuilderSingleLikeParam() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->addWhere('author', 'Smith', 'LIKE');
    $res = $queryBuilder->getStatement();
    $this->assertEquals('SELECT * FROM books WHERE books.author LIKE :author_0;', $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEquals([':author_0' => '%Smith%'], $res);
  }

  public function testQueryBuilderMultipleDefaultParam() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->addWhere('name', 'John');
    $queryBuilder->addWhere('author', 'Smith', 'LIKE');
    $res = $queryBuilder->getStatement();
    $expect = 'SELECT * FROM books WHERE books.name = :name_0 AND books.author LIKE :author_0;';
    $this->assertEquals($expect, $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEquals([':name_0' => 'John', ':author_0' => '%Smith%'], $res);
  }

  public function testQueryBuilderReferenceInParam() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->addWhere('categories', [1,2], 'IN');
    $res = $queryBuilder->getStatement();
    $expect = 'SELECT * FROM books JOIN books_categories ON books_categories.books_id = books.id WHERE books_categories.categories_id IN (:categories_0, :categories_1);';
    $this->assertEquals($expect, $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEquals([':categories_0' => 1, ':categories_1' => 2], $res);
  }

  public function testQueryBuilderMultipleDuplicateParam() {

    $queryBuilder = QueryBuilder::create($this->bookStorage);
    $queryBuilder->addWhere('name', 'John', 'LIKE');
    $queryBuilder->addWhere('name', 'John');
    $queryBuilder->addWhere('author', 'Smith', 'LIKE');
    $queryBuilder->addWhere('company', 'Google');
    $res = $queryBuilder->getStatement();
    $expect = 'SELECT * FROM books WHERE books.name LIKE :name_0 AND books.name = :name_1 AND books.author LIKE :author_0;';
    $this->assertEquals($expect, $res);
    $res = $queryBuilder->getStatementParams();
    $this->assertEquals([
      ':name_0' => '%John%', ':name_1' => 'John', ':author_0' => '%Smith%', ':company_0' => 'Google'
    ], $res);
  }

}
