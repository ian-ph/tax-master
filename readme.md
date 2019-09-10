
# Tax Master

Tax Master is a simple app that displays the tax income and tax rate statistics of a given country, state and county combination. This app is built as a form of test.
Note that the app is inconviniently structured in order to showcase different areas of skill. 
- Statistic related pages (dashboard and states statistic pages) are made using the conventional MVC structure.
- Statistic related pages also demostrate knowledge in sql, eloquent (ORM in general) and common query optimization. You can seed the database with 1,000,000 records and get "real time" data in  2 seconds
- CRUDs (for countries, states, counties, tax rates) demonstrate knowlede in API design and development, API documentation structure, common validation and resource handling, and backend/frontend script interaction.
- CRUDs also demonstrate knowledge in javascript where the front end interaction is mostly ajax based. (though only made with Jquery)
- Unit testing focuses on the API as all computations are database level (mysql avg for instance)
- Unit testing also demostrate knowledge in factories, in memory data testing, and mocking.
- Database are designed using migration files. Seeder files are also provided for convinience.
- API endpoints are properly commented where the documentation can be generated automatically. You can find the docs at /docs/index.html.

### Making the App run

#### Requirements
- Basic knowledge on how httpd/apache work
- Composer is installed
- LAMP is installed
- Php is at least version 7.x 
- create an empty database

#### To run the app on your chosen machine, simply follow the steps below
- clone the repository
- go to the the directory and run ```composer install```
- copy the ```.env-example``` file as ```.env```. Modify the ```.env``` file, put the database sever, user and password there
- run ```php artisan key:generate```
- run ```php artisan migrate``` to create the tables
- run ```php artisan db:seed``` to seed the database
-- feel free to modify ```database/seeds/TaxIncomeSeeder.php``` ```$limit``` property  to indicate the number of records to insert.
-- run the tax income seeder as much as needed to add more records by running ```php artisan db:seed --class=TaxIncomeSeeder```
- Finally, run ```php artisan serve``` and open the app on your chosen browser

### Unit Testing
- go to the app directory
- run ```vendor/bin/phpunit```
