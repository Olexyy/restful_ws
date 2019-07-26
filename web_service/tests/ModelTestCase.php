<?php

namespace Tests;

use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class ModelTestCase.
 *
 * @package Tests
 */
abstract class ModelTestCase extends KernelTestCase {

  /**
   * @var ModelFactoryInterface
   */
  protected $factory;

  /**
   * @var StorageInterface
   */
  protected $storage;

  /**
   * Model factory.
   *
   * @return ModelFactoryInterface
   *   Model factory.
   */
  abstract function getModelFactory();

  /**
   * Model storage.
   *
   * @return StorageInterface
   */
  abstract function getModelStorage();

  /**
   * {@inheritdoc}
   */
  public function setUp() : void {

    parent::setUp();
    $this->factory = $this->getModelFactory();
    $this->storage = $this->getModelStorage();
    $this->storage->dropSchema();
  }

  /**
   * Test model schema sync.
   */
  public function testModelUpDown() {

    $res = $this->getDatabase()->tableExists($this->storage->getTable());
    $this->assertFalse($res);
    $this->storage->ensureSchema();
    $res = $this->getDatabase()->tableExists($this->storage->getTable());
    $this->assertTrue($res);
  }

  /**
   * Test model creation workflow.
   */
  public function testModelCreation() {

    $this->storage->ensureSchema();
    $res = $this->storage->count();
    $this->assertEquals(0, $res);
    $model = $this->factory->generate();
    $this->assertTrue($model->isNew());
    $model->save();
    $this->assertFalse($model->isNew());
    $res = $this->storage->count();
    $this->assertEquals(1, $res);
  }

  /**
   * Test model load workflow.
   */
  public function testModelLoad() {

    $this->storage->ensureSchema();
    $model = $this->factory->generate();
    $model->save();
    $id = $model->getId();
    $this->assertNotEmpty($id);
    $res = $this->storage->find($id);
    $this->assertInstanceOf(ModelInterface::class, $res);
    $this->assertEquals($id, $res->getId());
  }

  /**
   * Test model update workflow.
   */
  public function testModelUpdate() {

    $this->storage->ensureSchema();
    $model = $this->factory->generate();
    $model->save();
    $id = $model->getId();
    $this->assertNotEmpty($id);
    $model->set('name', 'changed');
    $model->save();
    $res = $this->storage->find($id);
    $this->assertInstanceOf(ModelInterface::class, $res);
    $this->assertEquals('changed', $res->get('name'));
  }

  public function testModelDelete() {

    $count = 3;
    $this->storage->ensureSchema();
    $this->factory->generate($count, TRUE);
    $res = $this->storage->count();
    $this->assertEquals($count, $res);
    $models = $this->storage->all();
    $this->assertIsArray($models);
    $this->assertEquals(count($models), $count);
    $model = current($models);
    $model->delete();
    $this->assertEmpty($model->getId());
    $res = $this->storage->find($model->getId());
    $this->assertEmpty($res);
    $res = $this->storage->count();
    $this->assertEquals($count - 1, $res);
    $this->storage->deleteAll();
    $res = $this->storage->count();
    $this->assertEmpty($res);
  }

}
