<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Transaction
 * @package miolae\billing\models
 *
 * @property int     id
 * @property int     invoice_id
 * @property Invoice invoice
 * @property int     status
 * @property int     type
 * @property string  reason
 * @property int     created_at
 * @property int     updated_at
 * @property int     created_by
 * @property int     updated_by
 */
class Transaction extends ActiveRecord
{
    use BlameableTrait;

    /** Transaction status before any actions with accounts and invoices */
    const STATUS_CREATED = 1;
    /** Transaction status after all actions with accounts/invoices are successfully finished */
    const STATUS_SUCCESS = 2;
    /** Transaction status after any action with accounts/invoices are failed (e.g. not enough funds to transact) */
    const STATUS_FAIL = 3;

    /** Transaction is created on invoice creation */
    const TYPE_CREATE = 1;
    /** Transaction is created on holding account funds */
    const TYPE_HOLD = 2;
    /** Transaction is created on invoice success (move held funds to the target account) */
    const TYPE_SUCCESS = 3;
    /** Transaction is created on invoice cancellation */
    const TYPE_CANCEL = 4;

    public static function tableName()
    {
        return '{{%billing_transactions}}';
    }

    public function behaviors()
    {
        return array_merge(self::getBlameableBehavior(), [TimestampBehavior::class]);
    }

    public function getInvoice()
    {
        return $this->hasOne(Invoice::class, ['id', 'invoice_id']);
    }
}
