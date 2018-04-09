<?php
/**
 * This is the template for generating the main layout view file.
 */

/* @var $this yii\web\View */
/* @var $generator yii2tech\admin\gii\mainframe\Generator */

echo "<?php\n";
?>

use yii\bootstrap\Html;
use yii2tech\admin\widgets\ButtonContextMenu;

/* @var $this \yii\web\View */
/* @var $content string */
<?= "?>" ?>
<?= "<?php " ?> $this->beginContent($this->findViewFile('/layouts/overall')); ?>
<div class="<?= "<?= " ?>str_replace('/', '-', $this->context->action->getUniqueId()) ?>">
    <h1><?= "<?= " ?> Html::encode(isset($this->params['header']) ? $this->params['header'] : $this->title) ?></h1>

    <p>
        <?= "<?= " ?> ButtonContextMenu::widget([
            'items' => isset($this->params['contextMenuItems']) ? $this->params['contextMenuItems'] : []
        ]) ?>
    </p>

    <?= "<?= " ?> $content ?>
</div>
<?= "<?php " ?> $this->endContent(); ?>