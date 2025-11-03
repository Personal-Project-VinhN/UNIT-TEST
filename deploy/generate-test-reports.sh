#!/bin/bash

echo "============================================"
echo "  Generating Test Reports"
echo "============================================"
echo ""

# Get script directory and move to project root
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR/.."

# Check if PHPUnit exists
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "ERROR: PHPUnit not found. Please run: composer install"
    exit 1
fi

# Check PHPUnit version
echo "Checking PHPUnit installation..."
vendor/bin/phpunit --version

# Create results directory
echo ""
echo "Creating results directories..."
mkdir -p tests/results/coverage

echo ""
echo "============================================"
echo "  Running Tests with Coverage"
echo "============================================"
echo ""

# Run tests with all reports
vendor/bin/phpunit \
    --coverage-html tests/results/coverage \
    --coverage-text \
    --coverage-clover tests/results/clover.xml \
    --log-junit tests/results/junit.xml \
    --testdox-html tests/results/testdox.html

EXIT_CODE=$?

if [ $EXIT_CODE -ne 0 ]; then
    echo ""
    echo "WARNING: Some tests failed, but reports are still generated."
    echo ""
fi

echo ""
echo "============================================"
echo "  Reports Generated Successfully!"
echo "============================================"
echo ""
echo "Report Locations:"
echo "  - HTML Coverage: tests/results/coverage/index.html"
echo "  - Text Coverage: tests/results/coverage.txt"
echo "  - Clover XML: tests/results/clover.xml"
echo "  - JUnit XML: tests/results/junit.xml"
echo "  - TestDox HTML: tests/results/testdox.html"
echo ""

# Try to open HTML coverage report
if command -v open &> /dev/null; then
    # macOS
    echo "Opening HTML coverage report..."
    open tests/results/coverage/index.html
elif command -v xdg-open &> /dev/null; then
    # Linux
    echo "Opening HTML coverage report..."
    xdg-open tests/results/coverage/index.html
else
    echo "Please open manually: tests/results/coverage/index.html"
fi

echo ""
echo "Done!"
