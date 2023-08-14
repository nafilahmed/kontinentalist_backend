Steps To Run this Application:

1- Intall PHP, Apatech Server, MYSQL and Composer on your machine.<br>
2- git pull this repo<br>
3- Create Database and configure it on .env file.<br>
4- Run "composer install".<br>
5- Run "php artisan migrate".<br>
6- Run "php artisan passport:install".<br>
7- Run "php artisan db:seed".<br>
8- Run "php artisan serve".<br>
9- Import "kontinentalist.postman_collection" Postman Collection for API's<br>
10- Following command for Testing:
	
	- For Authentication API's "./vendor/bin/phpunit tests/Feature/AuthTest.php"
	- For Story API's "./vendor/bin/phpunit tests/Feature/StoryTest.php"


