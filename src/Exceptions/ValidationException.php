<?php

declare(strict_types=1);

namespace StellarWP\Validation\Exceptions;

use Exception;
use StellarWP\Validation\Exceptions\Contracts\ValidationExceptionInterface;

class ValidationException extends Exception implements ValidationExceptionInterface
{

}
