<?php

namespace miolae\billing\exceptions;

use Throwable;

class TransactionException extends \RuntimeException
{
    public function __construct(array $transactionErrors, int $code = 0, Throwable $previous = null)
    {
        $message = 'Can\'t save transaction with errors: ' . implode(', ', $transactionErrors);

        parent::__construct($message, $code, $previous);
    }
}
