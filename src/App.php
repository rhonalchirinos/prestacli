<?php

namespace Presta;

use Presta\Commands\DockerCommand;
use Presta\Commands\InspireCommand;
use Presta\Commands\NewCommand;
use Presta\Commands\ReleaseCommand;
use Symfony\Component\Console\Application;

/**
 * 
 */
class App extends Application
{

    /**
     * @var InspireCommand 
     */
    function __construct()
    {
        parent::__construct('PRESTASHOP CLI FOR HUMAN', '1.0.0');
        $this->add(new InspireCommand());
        $this->add(new NewCommand());
        $this->add(new DockerCommand());
        $this->add(new ReleaseCommand());
    }
}
