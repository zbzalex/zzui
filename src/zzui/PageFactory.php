<?php

namespace zzui;

/**
 * The page factory.
 * 
 * @author zbzalex
 */
interface PageFactory
{
    /**
     * New page factory.
     * 
     * @see \zzui\markup\html\Page
     */
    public function newPage($pageClass, array $params = []);
}
