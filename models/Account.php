<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Account
 * @package miolae\billing\models
 *
 * @property int    id
 * @property float  amount
 * @property int    owner_id
 * @property float  hold
 * @property int    type
 * @property string title
 * @property int    created_at
 * @property int    updated_at
 * @property int    created_by
 * @property int    updated_by
 */
class Account extends ActiveRecord
{
    use BlameableTrait;

    const TYPE_BLACKHOLE = 1;
    const TYPE_NORMAL = 2;

    public static function tableName()
    {
        return '{{%billing_accounts}}';
    }

    public function behaviors()
    {
        return array_merge(self::getBehaviors(), [TimestampBehavior::class]);
    }
}
