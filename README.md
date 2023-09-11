# Laravel Error Notify

This package is used to send out error log through email to developer account.

```
composer require ygthor/laravel-error-notify
```

Need to create following variable in .env to use
IP_STACK_API_KEY need request using this url https://ipstack.com/, ipstack allow to trace visitor ip location.
```
IP_STACK_API_KEY=xxxxxxx  
DEBUGGER_EMAIL=developer@company.com
DEBUGGER_MAIL_HOST=smtp.gmail.com
DEBUGGER_MAIL_PORT=587
DEBUGGER_MAIL_USERNAME=xxxx@gmail.com
DEBUGGER_MAIL_PASSWORD=xxxxxx
DEBUGGER_MAIL_ENCRYPTION=tls
```

use this command to publish config
```
php artisan vendor:publish --tag=config --provider="YGThor\LaravelErrorNotify\LaravelErrorNotifyServiceProvider"
```