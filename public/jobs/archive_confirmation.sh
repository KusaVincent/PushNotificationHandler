#!/bin/bash

directory="/public/confirmation_archive"

threshold_date=$(date -d "7 days ago" '+%Y%m%d')

for file in "$directory"/*; do
  if [[ -f "$file" ]]; then
    filename=$(basename "$file")

    date_string=""

    if [[ "$filename" =~ ^([0-9]{8})_.*_C2B\.csv$ ]]; then
      date_string="${BASH_REMATCH[1]}"
    elif [[ "$filename" =~ ^push_notification\.log-([0-9]{8})$ ]]; then
      date_string="${BASH_REMATCH[1]}"
    fi

    if [[ -n "$date_string" ]]; then
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
  fi
done