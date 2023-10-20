> The measurement of the rating of an individual might well be compared with the measurement of the position of a cork bobbing up and down on the surface of agitated water with a yardstick tied to a rope and which is swaying in the wind.
> 
> — Arpad Elo

## About Ratings

Ratings is a web application to:

- store players information
- keep game records
- calculate ratings among players

## File Format

Currently, the following format is supported:

- [LIHKG](https://github.com/horaceho/ranks/blob/master/data/)

## Installation
```
git clone git@github.com:horaceho/ratings.git
cd ratings
cp .env.example .env
composer install
php artisan key:generate
php artisan storage:link
touch database/database.sqlite
php artisan migrate
php artisan serve
php artisan make:filament-user
```

## License

Ratings is licensed under the [MIT license](https://opensource.org/licenses/MIT).

&copy; 2023 [Horace Ho](https://horaceho.com)
