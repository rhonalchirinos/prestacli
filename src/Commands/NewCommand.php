<?php

namespace Presta\Commands;

use Presta\Services\PrestashopService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class NewCommand extends Command
{

    public function __construct()
    {
        parent::__construct('new');
        $this->setDescription('create a new Prestashop Proyect');
    }

    /**
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');
        $moduleName = $input->getArgument('name');
        $author = $input->getOption('author');

        $service = new PrestashopService($input, $output);
        $service->createProyect($type, $moduleName, $author);
        return 0;
    }

    /**
     * 
     */

    protected function configure(): void
    {

        $this->addArgument('type', InputArgument::REQUIRED, 'Who do you want to make?')->setHelp(
            <<<EOT
            The <info>%command.name%</info> command make a new project, from 
            <comment>https://validator.prestashop.com/generator</comment>
            
            <info>presta new shipping -m moduleName </info> make a shipping module
            <info>presta new payment  -m moduleName </info> make a payment module

            EOT
        );

        $this->addArgument('name', InputArgument::REQUIRED, 'What is the Module name ?')->setHelp(
            <<<EOT
            The <info>%command.name%</info> need you indicate the module name
            
            <info>presta new shipping moduleName </info>
            <info>presta new payment moduleName </info> 

            EOT
        );

        $this->addOption('author', 'a', InputOption::VALUE_REQUIRED);
    }
}
