# SwitchInput Widget Test Suite

This directory contains comprehensive tests for the SwitchInput widget, covering all major functionality and edge cases.

## Test Structure

### Test Files

1. **`SwitchInputTest.php`** - Main unit tests with mocking
   - Tests widget configuration and validation
   - Tests HTML output generation
   - Tests asset registration
   - Tests tristate functionality
   - Tests JavaScript generation

2. **`SwitchInputAssetTest.php`** - Asset bundle tests
   - Tests asset registration
   - Tests asset dependencies
   - Tests source path configuration

3. **`functional/SwitchInputFunctionalTest.php`** - Functional tests
   - Tests widget properties without heavy mocking
   - Tests configuration scenarios
   - Tests property assignments

## Test Coverage

### Configuration Tests
- ✅ Default widget configuration
- ✅ Type assignment (CHECKBOX/RADIO)
- ✅ Tristate configuration
- ✅ Indeterminate value configuration
- ✅ Items configuration for radio inputs
- ✅ Container options
- ✅ Label options
- ✅ Separator configuration
- ✅ Inline label configuration

### Validation Tests
- ✅ Invalid type throws exception
- ✅ Radio without items throws exception
- ✅ Radio with invalid items throws exception

### HTML Output Tests
- ✅ Checkbox input rendering
- ✅ Tristate checkbox rendering (hidden + dummy inputs)
- ✅ Radio input rendering
- ✅ Indeterminate toggle rendering
- ✅ Container HTML structure

### Asset & JavaScript Tests
- ✅ Asset registration
- ✅ Plugin options configuration
- ✅ JavaScript generation for tristate functionality
- ✅ Event handler registration

### Widget Features Tested

#### Checkbox Mode
- Basic checkbox functionality
- Tristate checkbox with indeterminate state
- Hidden input for tristate form submission
- Dummy checkbox for UI interaction

#### Radio Mode
- Multiple radio items
- Item options and label options
- Custom separators
- Value selection

#### Tristate Functionality
- Indeterminate value handling
- Toggle button for indeterminate state
- JavaScript event handling
- Form submission values

#### Asset Management
- CSS and JS file inclusion
- Bootstrap Switch plugin registration
- Custom asset path configuration

## Running Tests

### Quick Start (No Dependencies Required)

You can run basic tests immediately without installing any dependencies:

```bash
# Run simple tests without PHPUnit
php tests/SimpleTestRunner.php

# Or use make
make test-simple
```

### Full Test Suite (Requires Dependencies)

For the complete PHPUnit test suite:

```bash
# Install dependencies
composer install

# Run all tests
composer test

# Or use PHPUnit directly
vendor/bin/phpunit
```

### Automatic Test Selection

The makefile will automatically choose the best available test runner:

```bash
make test  # Runs PHPUnit if available, otherwise runs simple tests
```

### Running Specific Test Suites

```bash
# Run only unit tests
vendor/bin/phpunit tests/SwitchInputTest.php

# Run only asset tests
vendor/bin/phpunit tests/SwitchInputAssetTest.php

# Run only functional tests
vendor/bin/phpunit tests/functional/
```

### Code Coverage

Generate code coverage report:
```bash
composer test-coverage
```

The coverage report will be generated in `tests/coverage/index.html`.

## Test Environment

The tests use:
- **PHPUnit 9.x** for the testing framework
- **Mocking** for Yii2 dependencies to avoid full framework initialization
- **Reflection** for testing protected/private methods
- **Bootstrap file** (`tests/bootstrap.php`) for test environment setup

## Mock Objects

The tests use extensive mocking to isolate the widget functionality:

- `View` class is mocked to test asset registration
- `SwitchInput` methods are partially mocked to avoid Yii2 dependencies
- Asset bundle registration is mocked to test without actual file operations

## Test Scenarios

### Edge Cases Covered

1. **Empty/Null Values**
   - Empty items array for radio
   - Null indeterminate values
   - Missing configuration options

2. **Invalid Configurations**
   - Invalid widget types
   - Missing required radio items
   - Malformed configuration arrays

3. **Boundary Conditions**
   - Minimum and maximum item counts
   - Long label strings
   - Special characters in values

4. **Integration Scenarios**
   - Multiple widgets on same page
   - Complex nested configurations
   - Custom plugin options

## Extending Tests

When adding new features to the SwitchInput widget:

1. Add unit tests to `SwitchInputTest.php` for core functionality
2. Add functional tests to verify the feature works end-to-end
3. Add asset tests if new CSS/JS files are introduced
4. Update this documentation

### Test Naming Convention

- `test[FeatureName]()` - Basic functionality test
- `test[FeatureName]Configuration()` - Configuration options test
- `test[FeatureName]ThrowsException()` - Exception handling test
- `testRender[ComponentType]()` - HTML output test

## Common Issues

### Linter Errors
The test files may show linter errors for PHPUnit and Yii2 classes in development environments without these dependencies loaded. This is normal and doesn't affect test execution.

### Mock Limitations
Some tests use mocking to avoid Yii2 framework dependencies. When testing in a full Yii2 environment, consider creating integration tests that don't use mocks.

### Asset Path Issues
Asset-related tests assume the standard directory structure. If the project structure changes, update the asset path tests accordingly. 