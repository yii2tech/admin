<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\caching\ArrayCache;
use yii\web\Response;
use yii2tech\admin\actions\FlushCache;
use yii2tech\tests\unit\admin\TestCase;

class FlushCacheTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Yii::$app->set('firstCache', \yii\caching\ArrayCache::class);
        Yii::$app->set('secondCache', \yii\caching\ArrayCache::class);
    }

    /**
     * Runs the action.
     * @param mixed $name
     * @param array|string|null $cache
     * @return array|Response response.
     */
    protected function runAction($name = null, $cache = null)
    {
        $action = new FlushCache('flush-cache', $this->createController());
        $action->cache = $cache;
        return $action->run($name);
    }

    // Tests :

    public function testFlushAutoDetect()
    {
        Yii::$app->firstCache->set('firstKey', 'firstValue');
        Yii::$app->secondCache->set('secondKey', 'secondValue');

        $this->runAction();

        $this->assertNull(Yii::$app->firstCache->get('firstKey'), 'First cache data should be flushed');
        $this->assertNull(Yii::$app->secondCache->get('secondKey'), 'Second cache data should be flushed');
    }

    /**
     * @depends testFlushAutoDetect
     */
    public function testFlushAutoDetectFilter()
    {
        Yii::$app->firstCache->set('firstKey', 'firstValue');
        Yii::$app->secondCache->set('secondKey', 'secondValue');

        $this->runAction('firstCache');

        $this->assertNull(Yii::$app->firstCache->get('firstKey'), 'First cache data should be flushed');
        $this->assertEquals('secondValue', Yii::$app->secondCache->get('secondKey'), 'Second cache data should NOT be flushed');
    }

    public function testFlushPredefined()
    {
        $objectCache = new ArrayCache();
        $objectCache->set('objectKey', 'objectValue');
        Yii::$app->firstCache->set('firstKey', 'firstValue');
        Yii::$app->secondCache->set('secondKey', 'secondValue');

        $this->runAction(null, ['firstCache', $objectCache]);

        $this->assertNull($objectCache->get('objectKey'), 'Object cache data should be flushed');
        $this->assertNull(Yii::$app->firstCache->get('firstKey'), 'First cache data should be flushed');
        $this->assertEquals('secondValue', Yii::$app->secondCache->get('secondKey'), 'Second cache data should NOT be flushed');
    }

    /**
     * @depends testFlushPredefined
     */
    public function testFlushPredefinedFilter()
    {
        Yii::$app->firstCache->set('firstKey', 'firstValue');
        Yii::$app->secondCache->set('secondKey', 'secondValue');

        $this->runAction('firstCache', ['firstCache', 'secondCache']);

        $this->assertNull(Yii::$app->firstCache->get('firstKey'), 'First cache data should be flushed');
        $this->assertEquals('secondValue', Yii::$app->secondCache->get('secondKey'), 'Second cache data should NOT be flushed');
    }
}