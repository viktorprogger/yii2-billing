<?php

namespace miolae\billing\traits;

use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;

trait BlameableTrait
{
    public static function getBlameableBehavior()
    {
        $result = [];

        try {
            $user = \Yii::$app->get('user', false);

            if (is_object($user)) {
                $result[] = BlameableBehavior::class;
            }
        } catch (InvalidConfigException $e) {
        }

        return $result;
    }
}
