<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\RoleUpdate;
use yii2tech\tests\unit\admin\data\UserProfile;
use yii2tech\tests\unit\admin\TestCase;

class RoleUpdateTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!class_exists(\yii2tech\ar\role\RoleBehavior::class)) {
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
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new RoleUpdate('update', $this->createController(['modelClass' => UserProfile::class]));
        return $action->run($id);
    }

    // Tests :

    public function testViewForm()
    {
        $response = $this->runAction(1);
        $this->assertEquals('update', $response['view']);
    }

    public function testMissingModel()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $response = $this->runAction(9999);
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitSuccess()
    {
        $newUserAddress = 'new user address';
        $newUserName = 'newuser';
        Yii::$app->request->bodyParams = [
            'UserProfile' => [
                'address' => $newUserAddress
            ],
            'User' => [
                'username' => $newUserName,
                'email' => 'newuser@domain.com',
            ]
        ];
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['url'][0]);

        $model = UserProfile::findOne(1);
        $this->assertEquals($newUserAddress, $model->address);

        $roleModel = $model->getUser()->andWhere(['username' => $newUserName])->one();
        $this->assertTrue(is_object($roleModel));
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitError()
    {
        Yii::$app->request->bodyParams = [
            'UserProfile' => [
                'address' => ''
            ]
        ];
        $response = $this->runAction(1);
        $this->assertEquals('update', $response['view']);
    }
}