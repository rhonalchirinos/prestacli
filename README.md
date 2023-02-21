 
# PrestaCLI 

Prestashop cli helper to generate project.

# How to install

```cmd
composer global require rhonalchirinos/prestacli
```

# Comands 

* Generate new project 

    For generate a new module for Prestashop you should execute that command indicate the author, type of module and name module. 

    ```cmd 
    prestacli new --a 'name author' type name 
    ``` 
    now, run the container 

    ```cmd 
    docker-compose up
    ```

    and write permision 

    ```cmd 
    docker-compose exec php chown -R www-data:www-data /var/www/html/modules/:name-module
    ```
    note: the options for type argument are shipping, payement, ...

* Generate docker file 
    for generate docker files 
    ```cmd 
    prestacli docker name
    ```
* Release 
    ```cmd
    prestacli release 
    ```
