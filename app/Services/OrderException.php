<?php

namespace App\Services;

use RuntimeException;

/** A purchase that cannot go through (sold out, unavailable); the message is safe to show the buyer. */
class OrderException extends RuntimeException
{
}
