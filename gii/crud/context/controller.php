<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii2tech\admin\gii\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\helpers\ArrayHelper;
use yii2tech\admin\behaviors\ContextModelControlBehavior;

/**
 * <?= $controllerClass ?> implements the CRUD actions for [[<?= $generator->modelClass ?>]] model.
 * @see <?= $generator->modelClass . "\n" ?>
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public $modelClass = '<?= $generator->modelClass ?>';
<?php if (!empty($generator->searchModelClass)): ?>
    /**
     * @inheritdoc
     */
    public $searchModelClass = '<?= $generator->searchModelClass ?>';
<?php endif ?>
    /**
     * CRUD model contexts
     * @see ContextModelControlBehavior::contexts
     */
    public $contexts = [
        // Specify actual contexts :
        'group' => [
            'class' => 'app\models\Group',
            'attribute' => 'groupId',
            'controller' => 'group',
            'required' => false,
        ],
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'class' => ContextModelControlBehavior::className(),
                'contexts' => $this->contexts,
            ]
        );
    }
}