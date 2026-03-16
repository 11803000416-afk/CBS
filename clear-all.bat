@echo off
echo ========================================
echo CBS CACHE CLEARING SCRIPT
echo ========================================
echo.

cd /d "c:\xampp\htdocs\CBS"

echo [1/4] Clearing Laravel caches...
php artisan optimize:clear
echo.

echo [2/4] Stopping Apache...
"C:\xampp\apache\bin\httpd.exe" -k stop
timeout /t 2 >nul
echo.

echo [3/4] Starting Apache...
"C:\xampp\apache\bin\httpd.exe" -k start
timeout /t 3 >nul
echo.

echo ========================================
echo SERVER-SIDE CACHES CLEARED!
echo ========================================
echo.
echo [4/4] NOW CLEAR YOUR BROWSER CACHE:
echo.
echo METHOD 1 - Hard Refresh (Fastest):
echo   Press: Ctrl + Shift + R
echo   Or: Ctrl + F5
echo.
echo METHOD 2 - Clear Browser Data:
echo   Press: Ctrl + Shift + Delete
echo   Select: Cached images and files
echo   Time range: Last hour
echo   Click: Clear data
echo.
echo METHOD 3 - Incognito Mode (Quick Test):
echo   Press: Ctrl + Shift + N
echo   Visit: http://localhost/CBS/public/cache-cleared.html
echo.
echo ========================================
echo Opening verification page in 5 seconds...
echo ========================================
timeout /t 5 >nul
start http://localhost/CBS/public/cache-cleared.html

echo.
echo DONE! Check your browser now.
pause
