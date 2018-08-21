<?php

namespace miolae\billing\tests\fixtures;

use miolae\billing\models\Account;
use tests\fixtures\BaseFixture;

class AccountFixture extends BaseFixture
{
    public $modelClass = Account::class;
    public $dataFile = 'accounts';
}
