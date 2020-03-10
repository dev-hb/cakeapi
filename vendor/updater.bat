echo off

echo Downloading API Provider ... please wait

git clone https://github.com/dev-hb/apiprovider.git temp

echo Updating API Provider ...

rem move the files
for %%i in (temp\*) do move "%%i" .
rem move the directories
for /d %%i in (temp\*) do move "%%i" .

rmdir temp

echo Finished!