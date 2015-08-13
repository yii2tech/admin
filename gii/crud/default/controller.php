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

/**
 * <?= $controllerClass ?> implements the CRUD actions for [[<?= $generator->modelClass ?>]] model.
 * @see <?= $generator->modelClass ?>
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
}
