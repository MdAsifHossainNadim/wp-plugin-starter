<?php

declare(strict_types=1);

namespace WPPluginStarter\Dependencies\League\Container\Exception;

use WPPluginStarter\Dependencies\Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
