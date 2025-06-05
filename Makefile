# Makefile for SwitchInput Widget Development

.PHONY: help install test test-coverage clean lint

# Default target
help:
	@echo "Available targets:"
	@echo "  install        Install dependencies"
	@echo "  test           Run all tests (PHPUnit or simple)"
	@echo "  test-simple    Run simple tests without dependencies"
	@echo "  test-coverage  Run tests with coverage report"
	@echo "  test-unit      Run unit tests only"
	@echo "  test-functional Run functional tests only"
	@echo "  test-exceptions Run exception tests only"
	@echo "  clean          Clean generated files"
	@echo "  lint           Run linting (if available)"

# Install dependencies
install:
	composer install

# Run all tests
test:
	@if [ -f ./vendor/bin/phpunit ]; then \
		./vendor/bin/phpunit; \
	else \
		echo "PHPUnit not found, running simple tests..."; \
		php tests/SimpleTestRunner.php; \
	fi

# Run simple tests without PHPUnit
test-simple:
	php tests/SimpleTestRunner.php

# Run tests with coverage
test-coverage:
	./vendor/bin/phpunit --coverage-html tests/coverage

# Run unit tests only
test-unit:
	./vendor/bin/phpunit tests/SwitchInputTest.php tests/SwitchInputAssetTest.php tests/unit/

# Run functional tests only
test-functional:
	./vendor/bin/phpunit tests/functional/

# Run exception tests only
test-exceptions:
	./vendor/bin/phpunit tests/unit/ExceptionTest.php

# Clean generated files
clean:
	rm -rf tests/coverage/
	rm -rf vendor/
	rm -f composer.lock

# Run linting (if tools are available)
lint:
	@if command -v php-cs-fixer > /dev/null; then \
		php-cs-fixer fix --dry-run --diff; \
	else \
		echo "php-cs-fixer not found. Install it for code style checking."; \
	fi
	@if command -v phpstan > /dev/null; then \
		phpstan analyse; \
	else \
		echo "PHPStan not found. Install it for static analysis."; \
	fi

# Development setup
dev-setup: install
	@echo "Development environment setup complete!"
	@echo "Run 'make test' to execute the test suite" 