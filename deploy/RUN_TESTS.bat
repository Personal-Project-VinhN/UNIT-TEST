@echo off
echo ============================================
echo   Running Unit Tests - Accounting System
echo ============================================
echo.

cd /d %~dp0\..

echo Checking PHPUnit installation...
vendor\bin\phpunit --version
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHPUnit not found. Please run: composer install
    pause
    exit /b 1
)

echo.
echo Running all Unit tests...
echo.

vendor\bin\phpunit --testsuite=Unit --testdox

echo.
echo ============================================
echo   Tests completed!
echo ============================================
pause
