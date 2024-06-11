log_date=date
log_name=$LOG_FILE

archive_log="$log_name-$log_date"

echo "====================LOG======================================"
mv /public/log/* /public/logs_archive/$archive_log
echo "====================end LOG=================================="