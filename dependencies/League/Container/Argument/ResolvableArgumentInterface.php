<?php

declare(strict_types=1);

namespace WPPluginStarter\Dependencies\League\Container\Argument;

interface ResolvableArgumentInterface extends ArgumentInterface
{
    public function getValue(): string;
}
