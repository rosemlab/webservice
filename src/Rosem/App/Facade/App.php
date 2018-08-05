<?php

namespace Rosem\App\Facade;

use Rosem\Psr\App\AppInterface;
use Rosem\Container\AbstractFacade;

class App extends AbstractFacade
{
    /**
     * @return string Facade accessor
     */
    protected static function getFacadeAccessor(): string
    {
        return AppInterface::class;
    }
}
