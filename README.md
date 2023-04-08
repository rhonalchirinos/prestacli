 
# PrestaCLI 

Prestashop cli helper to generate project.

# How to install

```cmd
composer global require rhonalchirinos/prestacli
```

# Generate new project 

For generate a new module for Prestashop you should execute that command. 

    ```cmd 
    prestacli new type name --a 'name author' 
    ``` 

    now, run the container 

    ```cmd 
    docker-compose up
    ```

    and write permision 

    ```cmd 
    docker-compose exec php chown -R www-data:www-data /var/www/html/modules/:name-module
    ```
 
note: the options for type and tab arguments are. 

    | -------- | ------- |
    | standard \| service | administration, advertising_marketing, analytics_stats, billing_invoicing, checkout, content_management, emailing, export, front_office_features, i18n_localization, market_place, merchandizing, migration_tools, mobile, others, payment_security, payments_gateways, pricing_promotion, quick_bulk_update, seo, search_filter, shipping_logistics, slideshows, smart_shopping, social_networks     |
    | shipping |     |
    | payement |     | 

# Generate docker file 

    This command generate a docker-compose with minimum requirement for develop, it is necessary to have docker installed
 
    ```cmd 
    prestacli docker name -i latest
    ```

    Note: For select the version of prestashop you can select it in [prestashop](https://hub.docker.com/r/prestashop/prestashop)

# Release 

    Release command generate a .zip valid for prestashop in the release folder, this command ignore these files   

    ```note
    release
    node_modules
    resources
    .git
    .config
    .prestashop
    backend.Dockerfile
    docker-compose.yml
    .gitignore
    .gitlab-ci.yml
    package.json
    package-lock.json
    tsconfig.json
    tsconfig.node.json
    vite.config.ts
    ```

    ```cmd
    prestacli release --t prod
    ```
    
    ```cmd
    prestacli release --t stage
    ```

    ```cmd
    prestacli release --t test
    ```