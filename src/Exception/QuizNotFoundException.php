<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class QuizNotFoundException extends QuizException
{
    public function __construct(
        string $message = 'Quiz not found',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
