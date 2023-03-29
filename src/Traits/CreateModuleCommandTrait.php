<?php

namespace Presta\Traits;

use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use ZipArchive;

/**
 * 
 */
trait CreateModuleCommandTrait
{

    /**
     * 
     * @var Client
     */
    public $client;

    /**
     * Get prestashop token form
     * 
     * @return string 
     */
    private function getTokenAndValidatorSession(): string
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
    private function generateFolder(string $token)
    {
        $fileName = sys_get_temp_dir() .  '/' . $this->moduleName . '_file.zip';
        $options = [
            'multipart' => [
                [
                    'name' => '_token',
                    'contents' => $token
                ],
                [
                    'name' => 'module_type',
                    'contents' => $this->type
                ],
                [
                    'name' => 'tab',
                    'contents' => 'shipping_logistics'
                ],
                [
                    'name' => 'name',
                    'contents' => $this->moduleName
                ],
                [
                    'name' => 'display_name',
                    'contents' => $this->moduleName . ' display name....'
                ],
                [
                    'name' => 'description',
                    'contents' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr...'
                ],
                [
                    'name' => 'author',
                    'contents' => $this->author
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
    public function createProyect(): void
    {

        $this->client = new Client(['cookies' => true]);

        $token = $this->getTokenAndValidatorSession();

        $this->generateFolder($token);
    }
}
