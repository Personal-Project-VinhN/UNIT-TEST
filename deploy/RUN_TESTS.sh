#!/bin/bash

# Move to project root
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR/.."

echo "============================================"
echo "  Running Unit Tests - Accounting System"
echo "============================================"
echo ""

# Check if PHPUnit exists
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "ERROR: PHPUnit not found. Please run: composer install"
    exit 1
fi

# Check PHPUnit version
echo "Checking PHPUnit installation..."
vendor/bin/phpunit --version

echo ""
echo "Running all Unit tests..."
echo ""

# Run tests with testdox format
vendor/bin/phpunit --testsuite=Unit --testdox

echo ""
echo "============================================"
echo "  Tests completed!"
echo "============================================"
