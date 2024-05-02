@echo off

set mydate=%date:~10,4%%date:~4,2%%date:~7,2%

echo Start of Upload  %time% >> D:\sap\logs\transactions_%mydate%.log
ftp -i -s:D:\sap\sap_script.txt >> D:\sap\logs\transactions_%mydate%.log
echo End of Upload  %time% >> D:\sap\logs\transactions_%mydate%.log

move D:\sap\mpesa\* D:\sap\mpesa_archive