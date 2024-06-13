#!/bin/bash

directory="/public/logs_archive"

threshold_date=$(date -d "7 days ago" '+%Y%m%d')

for file in "$directory"/push_notification.log-*; do
  if [[ -f "$file" ]]; then
    filename=$(basename "$file")

    date_string=$(echo "$filename" | sed -e 's/^push_notification\.log-//')

    year=$(echo "$date_string" | cut -c1-4)
    month=$(echo "$date_string" | cut -c5-6)
    day=$(echo "$date_string" | cut -c7-8)
    formatted_date="${year}-${month}-${day}"

    new_date=$(date -d "$formatted_date - 7 days" '+%Y%m%d')

    if [[ "$new_date" < "$threshold_date" ]]; then
      rm "$file"

      echo "Deleted $filename (new date: $new_date)"
    fi
  fi
done