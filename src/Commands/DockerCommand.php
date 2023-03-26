<?php

namespace Presta\Commands;

use Presta\Traits\DockerCommandTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class DockerCommand extends Command
{

    use DockerCommandTrait;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var String
     */
    private $version;

    /**
     * @var String
     */
    private $moduleName;


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
        $this->input = $input;
        $this->output = $output;
        $this->moduleName = $input->getArgument('name');
        $this->version = $input->getOption('docker-version') ?? 'latest';
        $this->publishDockerFiles();
        return Command::SUCCESS;
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

        $this->addOption('docker-version', 'dv', InputOption::VALUE_OPTIONAL, 'Imagen version for Prestashop');
    }
}
