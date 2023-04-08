<?php

namespace Presta\Commands;

use Exception;
use Presta\Traits\CreateModuleCommandTrait;
use Presta\Traits\DockerCommandTrait;
use Presta\Validations\PrestashopValidation;
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
    use DockerCommandTrait, CreateModuleCommandTrait;

    public static $typeService = [];

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string 
     */
    private $type;

    /**
     * @var string
     */
    private $tab;

    /**
     * @var string 
     */
    private $moduleName;

    /**
     * @var string 
     */
    private $author;

    /**
     * @var String
     */
    private $version;

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
        $this->input = $input;
        $this->output = $output;
        $this->type = $input->getArgument('type_module');
        $this->moduleName = $input->getArgument('name_module');
        $this->author = $input->getOption('author');
        $this->tab = $input->getOption('tab_module');
        $this->version = $input->getOption('image') ?? 'latest';
        $this->validated();
        $this->createProyect();
        $this->publishDockerFiles();

        return Command::SUCCESS;
    }

    protected function validated()
    {
        $presta = new PrestashopValidation($this->type, $this->tab, $this->moduleName);

        if (empty($this->tab)) {
            $this->tab = $this->type == 'payment' ? 'payments_gateways' : 'shipping_logistics';
        }

        return  $presta->validated() ? true : throw new Exception($presta->getMessage());
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
        $this->addOption('tab_module', 't', InputOption::VALUE_OPTIONAL);
        $this->addOption('author', 'a', InputOption::VALUE_REQUIRED);
        $this->addOption('image', 'i', InputOption::VALUE_OPTIONAL, 'Imagen version for Prestashop');
    }
}
