# Test sop
***
Test shop with DI container injection and some symfony compoments
* php DI
* Http Foundation component
* Cache component
* Twig component
* Routing component

### Requirements
* php >= 8.0
* composer

Installation step.
```
$ git clone https://github.com/ajaunasse/test_shop.git
$ cd ../path/to/the/file
$ composer install
$ php -S localhost:8000
```

Run tests 
```
$ vendor/bin/phpunit
```

Run cs-fixer
```
$ vendor/bin/phpunit fix
```

No databases needed for this project