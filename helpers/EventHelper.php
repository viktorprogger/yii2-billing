<?php

namespace miolae\billing\helpers;

use miolae\billing\models\Account;
use miolae\billing\Module;
use yii\base\Event;

class EventHelper
{
    public static function accountBlackHoleZero(Event $event)
    {
        if ($event->data['blackHoleStrategy'] === Module::BLACK_HOLE_ZERO) {
            /** @var Account $account */
            $account = $event->sender;
            $account->amount = 0;
            $account->hold = 0;
        }
    }
}
