<?php

namespace miolae\billing\tests\unit\traits;

use Codeception\Test\Unit;
use miolae\billing\traits\BlameableTrait;
use yii\web\User;

class BleamableTest extends Unit
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function testHasUser()
    {
        $userConfig = [
            'class' => User::class,
            'identityClass' => \UserModel::class,
        ];

        \Yii::$app->set('user', $userConfig);

        $class = new class {use BlameableTrait;};
        self::assertEquals(['yii\behaviors\BlameableBehavior'], $class::getBlameableBehavior());
    }

    public function testHasNoUser()
    {
        $class = new class {use BlameableTrait;};
        self::assertEquals([], $class::getBlameableBehavior());
    }
}
