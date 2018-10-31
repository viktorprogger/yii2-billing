<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Account
 * @package miolae\billing\models
 *
 * @property int            id
 * @property float          amount
 * @property int            owner_id
 * @property float          hold
 * @property int            type
 * @property string         title
 * @property int            created_at
 * @property int            updated_at
 * @property int            created_by
 * @property int            updated_by
 *
 * @property-read Invoice[] invoicesIncoming
 * @property-read Invoice[] invoicesOutgoing
 * @property-read float     amountAvailable
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
        return array_merge(self::getBlameableBehavior(), [TimestampBehavior::class]);
    }

    public function rules()
    {
        return [
            [['owner_id', 'amount', 'hold'], 'default', 'value' => 0],
            [['type'], 'default', 'value' => static::TYPE_NORMAL],
            [['amount', 'hold', 'type', 'title'], 'required'],
            [['type', 'owner_id'], 'integer'],
            [['hold', 'owner_id',], 'number', 'min' => 0],
            [['amount', 'hold'], 'validateAmountAvailable'],
            [['amount'], 'validateAmount'],
        ];
    }

    public function getInvoicesIncoming()
    {
        return $this->hasMany(Invoice::class, ['to_id', 'id']);
    }

    public function getInvoicesOutgoing()
    {
        return $this->hasMany(Invoice::class, ['from_id', 'id']);
    }

    public function getAmountAvailable()
    {
        return $this->amount - $this->hold;
    }

    public function validateAmount()
    {
        if (!is_numeric($this->amount)) {
            $this->addError('Field \'amount\' must have a numeric value');

            return;
        }

        if ($this->amount < 0 && $this->type !== static::TYPE_BLACKHOLE) {
            $this->addError('Only BlackHole accounts can have negative amount');
        }
    }

    public function validateAmountAvailable()
    {
        if ($this->type !== static::TYPE_BLACKHOLE && $this->amountAvailable < 0) {
            $this->addError('Недостаточно средств на счете');
        }
    }
}
