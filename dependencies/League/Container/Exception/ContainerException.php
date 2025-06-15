<?php

declare(strict_types=1);

namespace WPPluginStarter\Dependencies\League\Container\Exception;

use WPPluginStarter\Dependencies\Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
}
