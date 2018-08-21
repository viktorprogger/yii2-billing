<?php

namespace miolae\billing\tests\integration;

use miolae\billing\models\Account;
use miolae\billing\Module;

class ModuleCest
{
    public function createInvoice(\IntegrationTester $I)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var Module $module */
        $module = \Yii::createObject(Module::class, ['billing']);

        $accountFrom = 1;
        $accountTo = 2;
        $amount = 3;
        $invoice = $module->createInvoice($accountFrom, $accountTo, $amount);

        $I->assertEquals($accountFrom, $invoice->account_id_from);
        $I->assertEquals($accountTo, $invoice->account_id_to);
        $I->assertEquals($amount, $invoice->amount);
    }

    public function createInvoiceWithAccountsAR(\IntegrationTester $I)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var Module $module */
        $module = \Yii::createObject(Module::class, ['billing']);

        $accountFrom = Account::findOne(1);
        $accountTo = Account::findOne(2);
        $amount = 3;
        $invoice = $module->createInvoice($accountFrom, $accountTo, $amount);

        $I->assertEquals($accountFrom, $invoice->account_id_from);
        $I->assertEquals($accountTo, $invoice->account_id_to);
        $I->assertEquals($amount, $invoice->amount);
    }
}
