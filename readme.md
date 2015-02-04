### Feng Access - Control 

Follow the steps to configure your local environment

#### Project Setup

* Create a new entry on your <code>hosts</code> file

        127.0.0.1   axceso-feng.dev

* Create a new Virtual host to handle the new entry on the hosts file

        #axceso-feng.dev
        <VirtualHost *:80>
            ServerAdmin your_email
            ServerName axceso-feng.dev
            DocumentRoot "your_path_to/webapp/public"
            <Directory "your_path_to/webapp/public">
                Options Indexes FollowSymLinks
                AllowOverride All
                Order allow,deny
                Allow from all
            </Directory>
        </VirtualHost>


* Make sure the configuration file <code>path_to_tour_webapp_folder/app/config/database.php</code> is updated with your local settings

* Execute the following commands under your <code>path_to_your_webapp folder</code>
    ###### Create migration Tables
        php artistan migrate:install
    ###### Create tables on the DB
        php artistan migrate
    ###### Populate tables with the default data
        php artistan db:seed

Feel free on update this file to add additional configuration settings.
