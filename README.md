# Untitled

# How to install

You can follow this few steps to install this project

1. Clone the repository
2. Set up your database informations in .env.local file
3. run 
    
    ```bash
    composer install
    yarn install
    yarn run build
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    symfony serve --no-tls
    ```
    

You can then acces the application.

If you have run the fixtures, you can access admin pannel through [http://localhost:8000/admin/](http://localhost:8000/admin/)

The credentials are 

email : contact@form.fr

password: admin

Some sample datas have already been loaded from the fixtures command.