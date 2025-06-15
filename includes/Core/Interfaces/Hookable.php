<?php
namespace WPPluginStarter\Core\Interfaces;

/**
 * Interface for classes that register WordPress hooks.
 */
interface Hookable {
    public function register_hooks(): void;
}

