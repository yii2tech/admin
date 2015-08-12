<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Index action displays the models listing with search support.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Index extends Action
{
    /**
     * @var string class name of the model which should be used as search model.
     */
    public $searchModelClass;
    /**
     * @var string name of the view, which should be rendered
     */
    public $view = 'view';


    /**
     * Creates new model instance.
     * @return Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newSearchModel()
    {
        $modelClass = $this->searchModelClass;
        if ($modelClass === null) {
            if ($this->modelClass === null) {
                throw new InvalidConfigException('Either "' . get_class($this) . '::searchModelClass" or "' . get_class($this) . '::modelClass" must be set.');
            }
            $modelClass = $this->modelClass;
        }

        return new $modelClass();
    }

    public function run()
    {
        $searchModel = $this->newSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}