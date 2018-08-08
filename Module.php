<?php

namespace miolae\billing;

use miolae\billing\models\Account;
use miolae\billing\models\Invoice;
use miolae\billing\models\Transaction;
use yii\base\Component;
use yii\base\Module as BaseModule;
use yii\db\ActiveRecord;

/**
 * Class Module
 * @package miolae\billing
 *
 * @property-read Component|null db
 */
class Module extends BaseModule
{
    /** @var array $modelMapDefault */
    protected $modelMapDefault = [
        'Invoice'     => Invoice::class,
        'Account'     => Account::class,
        'Transaction' => Transaction::class,
    ];

    /** @var ActiveRecord[] Model map */
    public $modelMap = [];

    /** @var string DB connection name */
    public $dbConnection = 'db';

    public function init()
    {
        parent::init();

        foreach ($this->modelMapDefault as $key => $model) {
            if (empty($this->modelMap[$key])) {
                $this->modelMap[$key] = $model;
            }
        }
    }

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

    /**
     * @param int|ActiveRecord $accountFrom
     * @param int|ActiveRecord $accountTo
     * @param $amount
     *
     * @return Invoice|ActiveRecord
     */
    public function createInvoice($accountFrom, $accountTo, float $amount): ActiveRecord
    {
        if (is_subclass_of($accountFrom, ActiveRecord::class)) {
            $accountFrom = $accountFrom->id;
        }

        if (is_subclass_of($accountTo, ActiveRecord::class)) {
            $accountTo = $accountTo->id;
        }

        $accountFrom = (int)$accountFrom;
        $accountTo = (int)$accountTo;

        $attributes = [
            'amount'          => $amount,
            'account_id_from' => $accountFrom,
            'account_id_to'   => $accountTo,
        ];

        return new $this->modelMap['Invoice']($attributes);
    }
}
