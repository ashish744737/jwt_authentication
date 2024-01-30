# jwt_authentication

Steps : 
1. use the command : composer install
2. rename .env.example to .env 
3. For detabase setup please follow below setup in .env

------ for mysql database ---------
<br>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=database_name<br>
DB_USERNAME=root<br>
DB_PASSWORD=<br>

## for postgreSql database
## DB_CONNECTION=pgsql
## DB_HOST=127.0.0.1
## DB_PORT=5432
## DB_DATABASE=database_name
## DB_USERNAME=user_name
## DB_PASSWORD=your_choosen_password

you can use any of these

4. use "php artisan migrate" command to migrate database
5. use command to regenerate jwt secret in case its not in .env file : "php artisan jwt:secret"
6. to run the program use the command : "php artisan serve"