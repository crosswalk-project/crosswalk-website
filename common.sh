#!/bin/bash
declare -i gollum_pid=0

function debug_msg () {
    (( debug )) && {
        echo -n $*
        echo " Enter to continue." 
        read
    }
}

function generate () {
    file=$1
	echo -n "Processing wiki/${file/.\//}..."
	php gfm.php ${file/.\//} > /dev/null
	echo "done."
	html=${file/.\//}.html
	[ ! -e ${html} ] && {
		echo "Could not create ${html}."
		exit 1
	}

	find ${html} -size 0 | grep -q ${html} && {
		echo "Could not generate ${html}."
		exit 1
	}
}
    
function launch_gollum () {
    gollum_pid=0
    test=$(ps -o pid= -C gollum)
    (( test==0 )) && {
        gollum --base-path wiki ${PWD}/wiki >/dev/null 2>&1 &
        gollum_pid=$!
        echo -n "Launching gollum."
        x=1
        while (( $x )); do
            echo -n "."
            sleep 0.5
            netstat -na 2>&1 | grep -q ":4567 "
            x=$?
        done
        echo ""
    }
}

function kill_gollum () {
    (( gollum_pid )) && {
        kill -9 ${gollum_pid}
    }
}

function check_perms () {
    echo ''
    echo -n 'Looking for files not writable by :www-data...'
    found=$(find . -not -group www-data -or \( \
            -not -user www-data \
            -and -not -perm -g=rwX \
        \) | grep -v \.git | wc -l)
    (( ${found} )) && {
        echo "${found} found."
        echo ''
        echo 'Please correct and try again.'
        echo 'Correct via:'
        echo 'chown :www-data . -R ; chmod g+rwX . -R'
        echo ''
        exit 1
    }
    echo 'none found. OK.'
}

function update_git () {
	git checkout master
	git pull --all || {
	        echo "Pulling from tip failed. Reverting to active branch: ${active}"
	        git checkout ${active} -f
	        exit 1
	}
}

function check_unstaged () {
    git diff --quiet --ignore-submodules --exit-code || {
		echo "Can't go live with uncommitted changes in $(basename ${PWD})"
		git diff --name-status --ignore-submodules
		[ "$(basename ${PWD})" = "wiki" ] && {
            echo "If lots of files are deleted, its probably the wiki "
            echo "was in a partial mklive state. Try:"
            echo "  cd wiki ; git checkout ; cd .."
		}
		exit 1
	}
}