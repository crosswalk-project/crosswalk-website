desc="chown and chmod all the non .git files"
function usage () {
cat << EOF
  usage: site.sh perm [<group>]

  Set group ownership to all content files to 'group'. If 'group'
  is not provided, defaults to www-data.
  
EOF
}

function run () {
    if [[ "$1" = "" ]]; then
        group="www-data"
    else
        group=$1
        shift
    fi
    
    find $dir/.. -not -path "*/.git/*" -and \( \( -not -group $group \) -or \( -not -perm -g+w \) \) | 
        while read file; do
            chown :$group $file &&
            chmod g+wX $file && {
                echo "chown/chmod success: $file"
            }
        done
}
