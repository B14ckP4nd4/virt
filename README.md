# Virtualizor API Wrapper

### **Install** ( laravel 6+ && PHP 7.2+ Required )

**install with composer**

```
composer require blackpanda/virt
```

 **edit config/app.php**

```
        /*
         * Package Service Providers...
         */
          blackpanda\virt\VirtualizorServiceProvider::class,
```

run publish command

```
php artisan vendor:publish --provider=blackpanda\virt\VirtualizorServiceProvider --force
```

and run migrations

this package use my own database encryption and you have to learn how encryption works, after that try to use this package or remove encryptable trait and encryptor package.



