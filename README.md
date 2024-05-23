# Hexagonal Architecture Example in PHP

Code example for [Hexagonal Architecture](https://othercode.io/blog/hexagonal-architecture) article.

# Usage 

First, install the dependencies and deploy the database schema.

```bash
composer install
composer db:initialize
```

Next, run the commands:

```bash
php bin/UserListController
php bin/UserCreateController "Vincent Vega"
```

## Testing

Running tests is quite easy, just execute the following command:

```bash
composer test
```