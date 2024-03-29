:: Prompt for administrator privileges
cd /d %~dp0
powershell -Command "Start-Process -Verb RunAs cmd -ArgumentList '/c start /B npm run dev --prefix %~dp0 --host'"
powershell -Command "Start-Process -Verb RunAs cmd -ArgumentList '/k php %~dp0artisan serve'"
exit