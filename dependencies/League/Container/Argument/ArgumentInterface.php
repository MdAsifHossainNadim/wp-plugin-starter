<?php

declare(strict_types=1);

namespace WPPluginStarter\Dependencies\League\Container\Argument;

interface ArgumentInterface
{
    /**
     * @return mixed
     */
    public function getValue();
}
