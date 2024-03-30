<?php

if (!function_exists('module_path')) {
    /**
     * Get the path to the modules directory.
     *
     * @param string $path
     * @return string
     */
    function module_path(string $path = ''): string
    {
        return app()->basePath("modules/{$path}");
    }
}
