<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Invoice
 * @package miolae\billing\models
 *
 * @property int     id
 * @property int     from_id
 * @property int     to_id
 * @property Account accountFrom
 * @property Account accountTo
 * @property float   amount
 * @property int     status Current invoice status.
 * @property string  reason The reason why this invoice is created. E.g. "refill account with PayPal".
 * @property int     created_at
 * @property int     updated_at
 * @property int     created_by
 * @property int     updated_by
 */
class Invoice extends ActiveRecord
{
    use BlameableTrait;

    /**
     * Newly created Invoice
     *
     * @see Invoice::status
     */
    const STATUS_CREATE = 1;
    /** Funds are held within the account */
    const STATUS_HOLD = 2;
    /** Funds are moved to the target account */
    const STATUS_SUCCESS = 3;
    /** Operation is cancelled */
    const STATUS_CANCEL = 4;

    public static function tableName()
    {
        return '{{%billing_invoices}}';
    }

    public function behaviors()
    {
        return array_merge(self::getBlameableBehavior(), [TimestampBehavior::class]);
    }

    public function rules()
    {
        return [
            [['from_id', 'to_id', 'amount'], 'required'],
            [['from_id', 'to_id', 'amount', 'reason'], 'safe'],
            [['from_id', 'to_id'], 'integer'],
            [['amount'], 'float'],
            [['status'], 'default', 'value' => static::STATUS_CREATE],
            [['amount'], 'number', 'min' => 1, 'message' => 'Возможен перевод от 1 рубля и больше'],
            [['from_id'], 'exist', 'targetRelation' => 'accountFrom'],
            [['to_id'], 'exist', 'targetRelation' => 'accountTo'],
        ];
    }

    public function getAccountFrom()
    {
        return $this->hasOne(Account::class, ['id' => 'from_id']);
    }

    public function getAccountTo()
    {
        return $this->hasOne(Account::class, ['id' => 'to_id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['invoice_id' => 'id']);
    }
}
