<?php
namespace BrummelMW\core;

class PHPFileLoader implements ConfigLoaderInterface
{
    public function load($file)
    {
        if (!is_readable($file)) {
            if (!file_exists($file) ) {
                throw new \RunTimeException("File {$file} doesn't exist");
            }
            throw new \RunTimeException("Configuration file {$file} is not readable");
        }
        return include($file);
    }

    public function leer($file): array
    {
        if (!is_readable($file)) {
            if (!file_exists($file) ) {
                throw new \RunTimeException("File {$file} doesn't exist");
            }
            throw new \RunTimeException("Configuration file {$file} is not readable");
        }
        return json_decode(file_get_contents($file), true);
    }

    public function supports($resource): bool
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
