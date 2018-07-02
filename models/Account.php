<?php

namespace miolae\billing\models;

use yii\db\ActiveRecord;

/**
 * Class Account
 * @package miolae\billing\models
 *
 * @property float  amount
 * @property int    owner_id
 * @property float  hold
 * @property int    type
 * @property string title
 */
class Account extends ActiveRecord
{
    const TYPE_BLACKHOLE = 1;
    const TYPE_NORMAL = 2;

    public static function tableName()
    {
        return '{{%billing_accounts}}';
    }
}
