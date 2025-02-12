# Laravel News API (News Aggregation API)

_News Aggregation API service built on Laravel_

### Configuration

Used for the API for News Aggregation, from NewsAPI, The Guardian & New York Times.

#### DB

- `DB_DATABASE` - MySQL DB Name. Defaults to `news_aggregator`
- `DB_USERNAME` - MySQL DB Username.
- `DB_PASSWORD` - MySQL DB Password

#### NEWSAPI

- `NEWSAPI_KEY` - The API key is given at [NewsAPI](https://newsapi.org/register).

#### THE GUARDIAN NEWS

- `GUARDIANAPI_KEY` - The API key is given at [The Guardian](https://open-platform.theguardian.com/access).

#### THE NEW YORK TIMES

- `NEWYORKTIMESAPI_KEY` - The API key is given at [The New York Times](https://developer.nytimes.com/).

### Setup

1. **PHP 8.0+**
1. **MySQL 8.0+**
1. **[Composer](https://getcomposer.org/) installed.**
1. `git clone https://github.com/aamirshan/news-aggregator-challenge.git`
1. `cd news-aggregator-challenge`
1. `cp .env.example .env`
1. Populate your `.env` file with the API keys and database credentials as specified in the configuration section.
1. `composer install`
1. `php artisan key:generate`
1. `php artisan migrate`
1. `php artisan serve`

### SET Schedule for fetching news from above API'section

path : app/console/kernel.php
$schedule->command('fetch:news')->everyMinute();
you can set refresh time of news apis as per your requirments

### Usage

- Visit `http://127.0.0.1:8000/articles` to display the latest news with a design layout.
- Visit `http://127.0.0.1:8000/api/getarticles` to fetch all top headlines in JSON format.

### Author

[Aamir shan](http://github.com/aamirshan)

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
