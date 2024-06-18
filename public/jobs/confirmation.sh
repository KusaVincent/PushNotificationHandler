username="sapassV"
directory="SAP_Files"
hostname="128.9.20.17"

append_name="_C2B.csv"
original_name="C2B.csv"
date_format=$(date '+%Y%m%d_%H%M%S')

new_file="$date_format$append_name"

destination="$username@$hostname:$directory"

echo "=======================RENAME==================================="
mv /public/confirmation/$original_name /public/confirmation/$new_file
echo "=======================end RENAME==============================="

echo "===========================SAP=================================="
scp /public/confirmation/*$append_name $destination
mv /public/confirmation/*$append_name /public/confirmation_archive/
gzip /public/confirmation_archive/*
echo "===========================END SAP=============================="