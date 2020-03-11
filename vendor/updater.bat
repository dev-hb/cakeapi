echo off

echo Downloading API Provider ... please wait

rmdir tmp

git clone https://github.com/dev-hb/apiprovider.git tmp

echo Updating API Provider ...

rem move the files
for %%i in (tmp\*) do move "%%i" .
rem move the directories
for /d %%i in (tmp\*) do move "%%i" .

rmdir tmp /S /Q

echo Finished!