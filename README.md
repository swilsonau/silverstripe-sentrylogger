# SilverStirpe Sentry Logger
![helpfulrobot](https://helpfulrobot.io/swilsonalfa/sentrylogger/badge)

Publish SilverStripe errors and warnings to Sentry using the Raven client.

## Installation
__Composer (recommended):__
```
composer require swilsonalfa/sentrylogger
```

## Configuration

Add the following to your _config.php file in the mysite folder:
```php
SS_Log::add_writer(new SentryLogger());
```

Add the Sentry DSN (available from the Sentry website) into your config yml file:
```yml
SentryLogger:
  sentry_dsn: https://xxx:xxx@app.getsentry.com/xxx
```
