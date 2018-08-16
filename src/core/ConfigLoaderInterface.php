<?php
namespace BrummelMW\core;

interface ConfigLoaderInterface
{
    /**
     * @return array
     */
    public function load($file);

    /**
     * @return bool
     */
    public function supports($resource): bool;
}
