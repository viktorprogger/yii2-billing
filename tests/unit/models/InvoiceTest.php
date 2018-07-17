<?php

namespace models;

use Codeception\Test\Unit;
use miolae\billing\models\Invoice;

class InvoiceTest extends Unit
{
    /**
     * @dataProvider validateProvider
     * @param $result
     * @param $attributes
     */
    public function testValidate(bool $result, array $attributes)
    {
        $invoice = new Invoice($attributes);

        $this->assertEquals($result, $invoice->validate(), implode(PHP_EOL, $invoice->getErrorSummary(true)));
    }

    public function validateProvider(): array
    {
        return [
            'try to validate' => [
                true,
                [
                'account_id_from' => 0,
                'account_id_to'   => 0,
                'amount'  => 1,
                ]
            ],
        ];
    }
}
