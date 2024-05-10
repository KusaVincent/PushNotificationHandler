username="sapassV"
hostname="128.9.20.17"
directory="SAP_Files"

destination="$username@$hostname:$directory"

echo "====================SAP======================================"
scp -r  /sap/mpesa/* $destination
mv /sap/mpesa/* /sap/mpesa_archive/
echo "====================end SAP=================================="