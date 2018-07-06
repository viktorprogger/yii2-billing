<?php

namespace miolae\billing\traits;

use yii\behaviors\BlameableBehavior;
use yii\web\IdentityInterface;

trait BlameableTrait
{
    public static function getBlameableBehavior() {
        $result = [];

        if (\Yii::$app->has('user') && \Yii::$app->user->identityClass) {
            $class = \Yii::$app->user->identityClass;
            if ((new $class) instanceof IdentityInterface) {
                $result[] = BlameableBehavior::class;
            }
        }

        return $result;
    }
}
