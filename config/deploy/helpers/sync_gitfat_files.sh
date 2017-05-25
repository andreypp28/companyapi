#!/usr/bin/env bash

# Checks for gitfat files (double check)
# and sync them into the current branch folder

export user=$1
export remote=$2
extensions=$3

extensions_list=`echo $extensions | tr ":" " "`
export tag="#\$# git-fat"

sync_gitfat_file () {
  file="$*"

  head=`head -c 11 "$file"`
  if [ "$head" = "$tag" ] ; then
    file_hash=`cat "$file" | awk '{print $3}'`
    echo "getting gitfat file $file_hash as $file"
    scp $user@$remote/$file_hash "$file"
  fi
}

export -f sync_gitfat_file

for extension in $extensions_list ; do
  find . -name "$extension" -exec bash -c 'sync_gitfat_file "$0"' {} \;
done
