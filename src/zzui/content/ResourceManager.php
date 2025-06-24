<?php

namespace zzui\content;

/**
 * The resource manager.
 * 
 * @author zbzalex
 */
class ResourceManager
{
    protected $loaders;

    public function __construct(array $loaders = [])
    {
        $this->loaders = $loaders;
    }

    public function load($resource)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                return $loader->load($resource);
            }
        }
    }
}
