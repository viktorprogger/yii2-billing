<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

abstract class BaseFixture extends ActiveFixture
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (!empty($this->dataFile) && strpos($this->dataFile, '.php') === false) {
            $this->dataFile .= '.php';
        }

        parent::init();

        if (empty($this->dataDirectory)) {
            $this->dataDirectory = dirname(\Yii::getAlias('@app')) . '/_data/fixtures';
        }
    }
}
