<?php

namespace Presta\Services;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class DockerService
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
     * @var String 
     */
    private $prestashopVersion;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->prestashopVersion = 'prestashop:1.7.8.8-apache';
    }

    /**
     * 
     */
    public function docker($moduleName)
    {
        $mysql = $this->mysql();
        $mailhog = $this->mailhog();
        $memcached = $this->memcached();
        $php = $this->php($moduleName);

        return <<<DOCKER
        version: '3'
        services:
            $php
            $mysql
            $mailhog
            $memcached
        networks:
            presta:
                driver: bridge
        volumes:
            prestamysql:
                driver: local
        DOCKER;
    }

    /**
     * @return 
     */
    public function mailhog(): string
    {
        return <<<MAILHOG
        mailhog:
                image: mailhog/mailhog
                logging:
                    driver: 'none'
                ports:
                    - '1025:1025'
                    - '8025:8025'
                networks:
                    - presta
        MAILHOG;
    }

    /**
     * @return string 
     */
    public function memcached(): string
    {
        return <<<MEMCACHED
        memcached:
                image: memcached:latest
                ports:
                    - "11211:11211"
                networks:
                    - presta
        MEMCACHED;
    }

    /**
     * @return String
     */
    public function mysql(): string
    {
        return <<<MYSQL
        mysql:
                image: 'mysql:8.0'
                ports:
                    - '4306:3306'
                environment:
                    MYSQL_ROOT_PASSWORD: '123445678'
                    MYSQL_DATABASE: 'prestashop'
                    MYSQL_USER: 'prestashop'
                    MYSQL_PASSWORD: '12345678'
                    MYSQL_ALLOW_EMPTY_PASSWORD: 'yes'
                volumes:
                    - 'prestamysql:/var/lib/mysql'
                networks:
                    - presta
                healthcheck:
                    test: [ "CMD", "mysqladmin", "ping" ]
                command: --innodb-buffer-pool-size=2G
        MYSQL;
    }

    /** 
     * @return string 
     */
    public function php($moduleName)
    {
        return <<<PHP
        php:
                image: php:7.4-apache
                build:
                    context: .
                    dockerfile: backend.Dockerfile
                ports:
                    - 8080:80
                volumes:
                    - ./.prestashop:/var/www/html:rw
                    - ./.:/var/www/html/modules/{$moduleName}:rw
                networks:
                    - presta
                working_dir: /var/www
                depends_on:
                    - mysql
                    - memcached
        PHP;
    }

    /**
     * @return string
     */
    public function dockerFile(): string
    {
        $version = $this->prestashopVersion;
        return <<<DOCKER
        FROM prestashop/$version
        RUN apt-get update -y && apt-get install --no-install-recommends -y openssh-server 
        RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && php -r "unlink('composer-setup.php');"
        RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash
        RUN apt-get update -y && apt-get install nodejs
        DOCKER;
    }

    /**
     * 
     */
    public function public($moduleName)
    {
        $docker = $this->docker($moduleName);
        $dokerfile = $this->dockerFile($moduleName);
        file_put_contents("$moduleName/backend.Dockerfile", $dokerfile);
        file_put_contents("$moduleName/docker-compose.yml", $docker);
        $this->output->writeln('<info>GENERATED DOCKER FILES :-) </info>');
    }
}
