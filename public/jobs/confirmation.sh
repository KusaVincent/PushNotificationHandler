username="sapassV"
hostname="128.9.20.17"
directory="SAP_Files"

destination="$username@$hostname:$directory"

echo "====================SAP======================================"
scp -r  /public/confirmation/* $destination
mv /public/confirmation/* /public/confirmation_archive/
echo "====================end SAP=================================="