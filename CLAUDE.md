# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
```bash
# Install dependencies
composer install

# Run static analysis
composer phpstan

# Generate PHPStan baseline for new violations
composer phpstan:baseline

# Run tests locally (requires vendor directory)
vendor/bin/codecept run unit

# Run specific test
vendor/bin/codecept run unit ValidatorTest.php

# Run tests with SLIC (if available)
slic use validation
slic composer install --ignore-platform-reqs
slic run unit --ext DotReporter
```

## Architecture Overview

### Core Components

1. **Config** (`src/Config.php`): Static configuration manager
   - Must call `Config::setServiceContainer()` before use
   - Initialize with `Config::initialize()` to register rules

2. **Validator** (`src/Validator.php`): Main validation engine
   - Takes rules array, values array, and labels array
   - Key methods: `passes()`, `fails()`, `validated()`, `errors()`
   - Returns sanitized data via `validated()`

3. **ValidationRulesRegistrar**: Rule registry accessed via service container
   - Prevents duplicate rule registration
   - Maps rule IDs to class implementations

### Design Patterns

- **Strategy Pattern**: Each rule implements `ValidationRule` interface
- **Command Pattern**: Rules can return `ExcludeValue` or `SkipValidationRules` commands
- **Abstract Factory**: Rules created from strings via `fromString()` method
- **Registry Pattern**: Rules registered in `ValidationRulesRegistrar`
- **Trait**: `HasValidationRules` adds validation to value objects

### Rule System

Rules can be specified as:
- Strings: `'required'`, `'max:255'`, `'email'`
- Instances: `new Max(255)`, `new Required()`
- Closures: Custom validation logic

Built-in rule categories:
- Basic: Required, Optional, Nullable, Exclude
- Type: Boolean, Integer, Numeric, Email, Currency, DateTime
- Comparison: Min, Max, Size, In, InStrict
- Conditional: ExcludeIf, NullableIf, OptionalIf (with Unless variants)

### Key Interfaces

- `ValidationRule`: Core rule interface with `__invoke()` method
- `Sanitizer`: Rules that modify values during validation
- `ValidatesOnFrontEnd`: Rules that can serialize to JSON for client-side validation
- `ConditionalRule`: Abstract base for conditional validation rules

### WordPress Integration

- Uses `__()` for internationalization with `%TEXTDOMAIN%` placeholder
- Hook system via configurable prefix: `{prefix}register_validation_rules`
- Compatible with WordPress coding standards and PHPStan WordPress rules

### Testing

Tests use custom assertions in `TestCase`:
- `assertValidationRulePassed($rule, $value, $values = [])`
- `assertValidationRuleFailed($rule, $value, $values = [])`

Mock rules available for testing validators (e.g., `MockRequiredRule`).

### Extension Points

1. Create custom rules by implementing `ValidationRule`
2. Register rules via WordPress action hook
3. Custom exceptions via `Config::setValidationExceptionClass()`
4. Extend `ConditionalRule` for conditional validation logic