<?php
namespace WPPluginStarter\Core\Interfaces;

/**
 * Interface for classes that require initialization.
 */
interface Initable {
    public function init(): void;
}

