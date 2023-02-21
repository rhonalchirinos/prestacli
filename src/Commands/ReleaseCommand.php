<?php

namespace Presta\Commands;

use RecursiveDirectoryIterator;
use RecursiveTreeIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

/**
 * 
 */
class ReleaseCommand  extends Command
{
    const EXCLUDE_FOLDER = [
        'release\/',
        'node_modules\/',
        'resources\/',
        '.git\/',
        '.config\/',
        '.prestashop\/',
        'backend.Dockerfile',
        'docker-compose.yml',
        '.gitignore',
        '.gitlab-ci.yml',
        'package.json',
        'package-lock.json',
        'tsconfig.json',
        'tsconfig.node.json',
        'vite.config.ts',
    ];

    public function __construct()
    {
        parent::__construct('release');
        $this->setDescription('Generate prestashop module .zip');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return int 0 if everything went fine, or an exit code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = [];

        $folder = new RecursiveTreeIterator(
            new RecursiveDirectoryIterator(dirname("./"))
        );

        foreach ($folder as $item) {
            $folder = explode('-./', $item)[1];
            $excludeFolders = join("|", ReleaseCommand::EXCLUDE_FOLDER);
            if (!!!preg_match_all("/$excludeFolders/", $folder) && !is_dir($folder)) {
                $files[] = $folder;
            }
        }

        if (!is_dir("./release")) {
            mkdir("./release");
        }

        $configPrestashop = simplexml_load_file('./config.xml');
        $zip = new ZipArchive();

        $name = 'release/' . $configPrestashop->name . '_' . $configPrestashop->version . '.zip';

        if (file_exists($name)) {
            unlink($name);
            $output->writeln("<fg=yellow>File Delete {$name}</>");
        }

        if ($zip->open($name, ZipArchive::CREATE)) {
            $zip->addEmptyDir($configPrestashop->name);
            foreach ($files as $value) {
                $zip->addFile($value, $configPrestashop->name . '/' .  $value);
            }
            $zip->close();
            $output->writeln('<info>GENERATED!</info>');
        }

        return 0;
    }
}
