# ActioPHP

[![Tests](https://github.com/zestic/actio/actions/workflows/tests.yml/badge.svg)](https://github.com/zestic/actio/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/zestic/actio/branch/main/graph/badge.svg)](https://codecov.io/gh/zestic/actio)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

## Installation
Install the latest version:
```bash
composer require actiophp/actio
```

## Usage
Actio::setHandler($handler);


## Tests

### Running Tests
Run all tests with coverage:
```bash
composer test
```

Or run specific test suites:
```bash
composer test:unit      # Unit tests only
composer test:integration # Integration tests only
```

### Integration Tests
Integration tests require a local MySQL instance. Configure your test database:
```sql
CREATE USER 'test'@'localhost' IDENTIFIED BY 'password1';
CREATE DATABASE actio_test;
GRANT ALL PRIVILEGES ON actio_test.* TO 'test'@'localhost';
FLUSH PRIVILEGES;
```

Test environment variables can be configured in `.env.testing`:
- ACTIO_MYSQL_HOST (default: localhost)
- ACTIO_MYSQL_DB_NAME (default: actio_test)
- ACTIO_MYSQL_USERNAME (default: test)
- ACTIO_MYSQL_PASSWORD (default: password1)

### Static Analysis
Run PHPStan (level 8):
```bash
composer phpstan
```

### Credits
Code and ideas borrowed from https://github.com/jbroadway/analog
