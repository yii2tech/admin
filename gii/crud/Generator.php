<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\gii\crud;

use Yii;

/**
 * Generates admin CRUD
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    /**
     * @inheritdoc
     */
    public $baseControllerClass = 'yii2tech\admin\CrudController';
    /**
     * @inheritdoc
     */
    public $messageCategory = 'admin';


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->templates['context'])) {
            $this->templates['context'] = dirname($this->defaultTemplate()) . '/context';
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Admin CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function formView()
    {
        return Yii::getAlias('@yii/gii/generators/crud') . DIRECTORY_SEPARATOR . 'form.php';
    }

    /**
     * Returns the root path to the original parent default code template files.
     * @return string the root path to the original parent default code template files.
     */
    public function parentDefaultTemplate()
    {
        return Yii::getAlias('@yii/gii/generators/crud/default');
    }

    /**
     * @inheritdoc
     */
    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'username') || !strcasecmp($name, 'email')) {
                return $name;
            }
        }
        return parent::getNameAttribute();
    }
}