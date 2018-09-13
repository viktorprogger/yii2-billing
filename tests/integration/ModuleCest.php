<?php

namespace miolae\billing\tests\integration;

use miolae\billing\Module;
use miolae\billing\tests\fixtures\AccountFixture;

class ModuleCest
{
    public function _before(\UnitTester $I)
    {
        $I->haveFixtures([AccountFixture::class]);
    }

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
        $errors = implode(PHP_EOL, $invoice->getErrorSummary(true));
        $I->assertNotEmpty($invoice->id, 'Invoice is not saved. Reason: ' . $errors);
    }
}
