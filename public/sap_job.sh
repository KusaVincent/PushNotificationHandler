username="sapassV"
hostname="128.9.20.17"
directory="SAP_Files"

# date=
# log_name="push_notifications.log"

destination="$username@$hostname:$directory"
# archive_log="$log_name-$date"

echo "====================SAP======================================"
scp -r  /public/confirmation/* $destination
mv /public/confirmation/* /public/confirmation_archive/
echo "====================end SAP=================================="

# echo "====================LOG======================================"
# mv /public/log/* /public/logs_archive/$archive_log
# echo "====================end LOG=================================="