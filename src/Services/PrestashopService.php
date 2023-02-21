<?php

namespace Presta\Services;

use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

/**
 * 
 */
class PrestashopService
{

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var DockerService
     */
    private $serviceDocker;

    /**
     * 
     * @var Client
     */
    public $client;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->client = new Client(['cookies' => true]);
        $this->serviceDocker = new DockerService($this->input, $this->output);
    }

    /**
     * Get prestashop token form
     * 
     * @return string 
     */
    public function getTokenAndValidatorSession(): string
    {
        $response = $this->client->request('get', 'https://validator.prestashop.com/generator');
        $body = $response->getBody();
        $dom = new DOMDocument();
        $dom->loadHTML($body);
        $inputs = $dom->getElementsByTagName('input');
        foreach ($inputs as $value) {
            if ($value->getAttribute('type') == 'hidden') {
                return $value->getAttribute('value');
            }
        }
        throw new Exception('ERROR');
    }

    /**
     * @param $type define the type module to generate for example payment 
     * @param $moduleName 
     * @param $author 
     * @param $type
     * 
     * @return void 
     */
    private function generateFolder(string $type, string $moduleName, string $author, string $token)
    {
        $fileName = sys_get_temp_dir() .  '/' . $moduleName . '_file.zip';
        $options = [
            'multipart' => [
                [
                    'name' => '_token',
                    'contents' => $token
                ],
                [
                    'name' => 'module_type',
                    'contents' => $type
                ],
                [
                    'name' => 'tab',
                    'contents' => 'shipping_logistics'
                ],
                [
                    'name' => 'name',
                    'contents' => $moduleName
                ],
                [
                    'name' => 'display_name',
                    'contents' => $moduleName . ' display name....'
                ],
                [
                    'name' => 'description',
                    'contents' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr...'
                ],
                [
                    'name' => 'author',
                    'contents' => $author
                ],
                [
                    'name' => 'version_maj',
                    'contents' => '1'
                ],
                [
                    'name' => 'version_med',
                    'contents' => '0'
                ],
                [
                    'name' => 'version_min',
                    'contents' => '0'
                ],
                [
                    'name' => 'confirm_uninstall',
                    'contents' => '0'
                ],
                [
                    'name' => 'uninstall_message',
                    'contents' => ''
                ],
                [
                    'name' => 'database',
                    'contents' => '0'
                ],
                [
                    'name' => 'need_instance',
                    'contents' => '0'
                ],
                [
                    'name' => 'version_compliancy[min]',
                    'contents' => '1.6'
                ],
                [
                    'name' => 'version_compliancy[max]',
                    'contents' => '1.7'
                ]
            ],
            'sink' => $fileName
        ];
        $this->client->request('POST', 'https://validator.prestashop.com/generator', $options);
        $zip = new ZipArchive();
        if ($zip->open($fileName) === TRUE) {
            $zip->extractTo('./');
            $zip->close();
            return $this;
        }
        $this->output->writeln("Generated Prject :-D");
    }

    /**
     * @param $type define the type module to generate for example payment 
     * @param $moduleName 
     * @param $author 
     * 
     * @return void 
     */
    public function createProyect(string $type, string $moduleName, string $author): void
    {
        $token = $this->getTokenAndValidatorSession();
        $this->generateFolder($type, $moduleName, $author, $token);
        $this->serviceDocker->public($moduleName);
    }

}
