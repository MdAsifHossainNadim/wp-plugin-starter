<?php

declare(strict_types=1);

namespace WPPluginStarter\Dependencies\League\Container\Argument;

interface DefaultValueInterface extends ArgumentInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();
}
