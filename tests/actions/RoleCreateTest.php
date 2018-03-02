<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\RoleCreate;
use yii2tech\tests\unit\admin\data\UserProfile;
use yii2tech\tests\unit\admin\TestCase;

class RoleCreateTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!class_exists('yii2tech\ar\role\RoleBehavior')) {
            $this->markTestSkipped('"yii2tech/ar-role" extension is required.');
        }
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function setupTestDbData()
    {
        $this->setupTestRoleDbData();
    }

    /**
     * Runs the action.
     * @param array $config
     * @return array|Response response.
     */
    protected function runAction(array $config = [])
    {
        $action = new RoleCreate('create', $this->createController(['modelClass' => UserProfile::class]), $config);
        return $action->run();
    }

    // Tests :

    public function testViewForm()
    {
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitSuccess()
    {
        $newUserAddress = 'new user address';
        $newUserName = 'newuser';
        Yii::$app->request->setParsedBody([
            'UserProfile' => [
                'address' => $newUserAddress
            ],
            'User' => [
                'username' => $newUserName,
                'email' => 'newuser@domain.com',
            ]
        ]);
        $response = $this->runAction();
        //var_dump($response['params']['model']->getErrors());
        $this->assertEquals('view', $response['url'][0]);

        /* @var $model UserProfile */
        $model = UserProfile::find()->where(['address' => $newUserAddress])->one();
        $this->assertTrue(is_object($model));
        $roleModel = $model->getUser()->andWhere(['username' => $newUserName])->one();
        $this->assertTrue(is_object($roleModel));
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitError()
    {
        Yii::$app->request->setParsedBody([
            'UserProfile' => [
                'address' => ''
            ]
        ]);
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }
}