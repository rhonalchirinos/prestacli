<?php

namespace Presta;

use Presta\Commands\DockerCommand; 
use Presta\Commands\CreateModuleCommand;
use Presta\Commands\ReleaseCommand;
use Symfony\Component\Console\Application;

/**
 * 
 */
class App extends Application
{

    /** 
     */
    function __construct()
    {
        parent::__construct('PRESTASHOP CLI FOR HUMAN', '1.0.0');
        $this->add(new CreateModuleCommand());
        $this->add(new DockerCommand());
        $this->add(new ReleaseCommand());
    }
}
