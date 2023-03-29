<?php

namespace Presta\Traits;

/**
 * 
 */
trait DockerCommandTrait
{

    /**
     * 
     */
    public function docker()
    {
        $mysql = $this->mysql();
        $php = $this->php($this->moduleName);

        return <<<DOCKER
        version: '3'
        services:
            $php
            $mysql
        networks:
            presta:
                driver: bridge
        volumes:
            prestamysql:
                driver: local
        DOCKER;
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
                    MYSQL_ROOT_PASSWORD: '12345678'
                    MYSQL_DATABASE: 'prestashop'
                    MYSQL_USER: 'prestashop'
                    MYSQL_PASSWORD: '12345678'
                    MYSQL_ALLOW_EMPTY_PASSWORD: 'yes'
                volumes:
                    - 'prestamysql:/var/lib/mysql'
                networks:
                    - presta
                command: --innodb-buffer-pool-size=2G
        MYSQL;
    }

    /** 
     * @return string 
     */
    public function php()
    {
        $version = "prestashop/prestashop:" . $this->version;

        return <<<PHP
        php:
                image: $version
                build:
                    context: .
                    dockerfile: backend.Dockerfile
                ports:
                    - 8080:80
                volumes:
                    - ./.prestashop:/var/www/html:rw
                    - ./.:/var/www/html/modules/{$this->moduleName}:rw
                networks:
                    - presta
                working_dir: /var/www
                depends_on:
                    - mysql
        PHP;
    }

    /**
     * @return string
     */
    public function dockerFile(): string
    {
        $version = "prestashop/prestashop:" . $this->version;
        return <<<DOCKER
        FROM $version
        RUN apt-get update -y && apt-get install --no-install-recommends -y openssh-server 
        RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && php -r "unlink('composer-setup.php');"
        RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash
        RUN apt-get update -y && apt-get install nodejs
        DOCKER;
    }

    /**
     * 
     */
    public function publishDockerFiles()
    {
        $docker = $this->docker($this->moduleName);
        $dokerfile = $this->dockerFile($this->moduleName);
        file_put_contents("$this->moduleName/backend.Dockerfile", $dokerfile);
        file_put_contents("$this->moduleName/docker-compose.yml", $docker);
        $this->output->writeln('<info>GENERATED DOCKER FILES </info>');
    }
}
