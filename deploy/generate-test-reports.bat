@echo off
echo ============================================
echo   Generating Test Reports
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
echo Creating results directories...
if not exist "tests\results" mkdir tests\results
if not exist "tests\results\coverage" mkdir tests\results\coverage

echo.
echo ============================================
echo   Running Tests with Coverage
echo ============================================
echo.

vendor\bin\phpunit ^
    --coverage-html tests\results\coverage ^
    --coverage-text ^
    --coverage-clover tests\results\clover.xml ^
    --log-junit tests\results\junit.xml ^
    --testdox-html tests\results\testdox.html

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo WARNING: Some tests failed, but reports are still generated.
    echo.
)

echo.
echo ============================================
echo   Reports Generated Successfully!
echo ============================================
echo.
echo Report Locations:
echo   - HTML Coverage: tests\results\coverage\index.html
echo   - Text Coverage: tests\results\coverage.txt
echo   - Clover XML: tests\results\clover.xml
echo   - JUnit XML: tests\results\junit.xml
echo   - TestDox HTML: tests\results\testdox.html
echo.
echo Opening HTML coverage report...
timeout /t 2 /nobreak >nul
start tests\results\coverage\index.html

echo.
echo Done!
pause
