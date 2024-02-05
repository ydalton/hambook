set address=127.0.0.1:8000

REM Start the server
start /b php\php.exe -S %address% -t dist\

REM Opens the link in the browser
timeout 1 /nobreak && "c:\Program Files\Google\Chrome\Application\chrome" %address%
