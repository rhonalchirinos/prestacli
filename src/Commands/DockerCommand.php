<?php

namespace Presta\Commands;

use Presta\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class DockerCommand extends Command
{

    /**
     * @var DockerService
     */
    private $dockerService;

    public function __construct()
    {
        parent::__construct('docker');
        $this->setDescription('Public Docker files');
    }

    /**
     * 
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moduleName = $input->getArgument('name');

        $this->dockerService = new DockerService($input, $output);
        $this->dockerService->public($moduleName);

        return 0;
    }

    /**
     * 
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'What is the Module name ?')->setHelp(
            <<<EOT
            The <info>%command.name%</info> need you indicate the module name
            
            <info>presta new shipping moduleName </info>
            <info>presta new payment  moduleName </info> 

            EOT
        );
    }
}
