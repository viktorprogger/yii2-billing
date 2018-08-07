<?php

use miolae\billing\models\Account;

return [
    'first test'  => [
        'id'     => 1,
        'title'  => 'first test',
        'amount' => 0,
        'hold'   => 0,
        'type'   => Account::TYPE_NORMAL,
    ],
    'second test' => [
        'id'     => 2,
        'title'  => 'second test',
        'amount' => 0,
        'hold'   => 0,
        'type'   => Account::TYPE_NORMAL,
    ],
];
