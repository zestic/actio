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
Integration tests require local MySQL and PostgreSQL instances.

#### MySQL Setup
Configure your MySQL test database:
```sql
CREATE USER 'test'@'localhost' IDENTIFIED BY 'password1';
CREATE DATABASE actio_test;
GRANT ALL PRIVILEGES ON actio_test.* TO 'test'@'localhost';
FLUSH PRIVILEGES;
```

MySQL environment variables in `.env.testing`:
- ACTIO_MYSQL_HOST (default: localhost)
- ACTIO_MYSQL_DB_NAME (default: actio_test)
- ACTIO_MYSQL_USERNAME (default: test)
- ACTIO_MYSQL_PASSWORD (default: password1)

#### PostgreSQL Setup
Configure your PostgreSQL test database:
```bash
# Create test user and database
sudo -u postgres psql -c "CREATE USER test WITH PASSWORD 'password1';"
sudo -u postgres psql -c "CREATE DATABASE test WITH OWNER test;"

# Create schema for Actio tests
sudo -u postgres psql -d test -c "CREATE SCHEMA actio_test AUTHORIZATION test;"
sudo -u postgres psql -d test -c "GRANT ALL ON SCHEMA actio_test TO test;"
```

PostgreSQL environment variables in `.env.testing`:
- ACTIO_PG_HOST (default: localhost)
- ACTIO_PG_DB_NAME (default: test)
- ACTIO_PG_SCHEMA (default: actio_test)
- ACTIO_PG_USERNAME (default: test)
- ACTIO_PG_PASSWORD (default: password1)

### Static Analysis
Run PHPStan (level 8):
```bash
composer phpstan
```

### Credits
Code and ideas borrowed from https://github.com/jbroadway/analog
