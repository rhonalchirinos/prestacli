<?php

namespace Presta\Commands;

use Exception;
use Presta\Services\PrestashopService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class CreateModuleCommand extends Command
{

    public function __construct()
    {
        parent::__construct('new');
        $this->setDescription('Create a new Prestashop Proyect');
    }

    /**
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type_module');
        $name = $input->getArgument('name_module');
        $author = $input->getOption('author');

        $validateType = in_array($type, ['payment', 'shipping']);
        if (!$validateType) throw new Exception('type module type is invalid');

        $validateName = strlen($name) < 5;
        if ($validateName) throw new Exception('name module type is invalid');

        $service = new PrestashopService($input, $output);
        $service->createProyect($type, $name, $author);
        return Command::SUCCESS;
    }

    /**
     * 
     */
    protected function configure(): void
    {

        $this->addArgument(
            'type_module',
            InputArgument::REQUIRED,
            'Who do you want to make?'
        )->setHelp(
            <<<EOT
            The <info>%command.name%</info> command make a new project, from <comment>https://validator.prestashop.com/generator</comment>
            
            <info>presta new shipping name_module --author=name@mail.com </info> make a shipping module
            <info>presta new payment  name_module --author=name@mail.com </info> make a payment module

            EOT
        );

        $this->addArgument(
            'name_module',
            InputArgument::REQUIRED,
            'What is the Module name ?'
        )->setHelp(
            <<<EOT
            The <info>%command.name%</info> need you indicate the module name
            
            <info>presta new shipping name_module --author=name@mail.com </info> make a shipping module
            <info>presta new payment  name_module --author=name@mail.com </info> make a payment module

            EOT
        );

        $this->addOption('author', 'a', InputOption::VALUE_REQUIRED);
    }
}
