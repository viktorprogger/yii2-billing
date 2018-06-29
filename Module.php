<?php

namespace miolae\billing;

use yii\base\Component;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    /** @var array Model map */
    public $modelMap = [];

    /** @var string DB connection name */
    public $dbConnection;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getDb(): ?Component
    {
        /** @noinspection OneTimeUseVariablesInspection */
        /** @var Component $db */
        $db = \Yii::$app->get($this->dbConnection);
        return $db;
    }
}
