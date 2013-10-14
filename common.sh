#!/bin/bash

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
    gollum=0
    test=$(ps -o pid= -C gollum)
    [ -z ${test} ] && {
        gollum --base-path wiki ${PWD}/wiki >/dev/null 2>&1 &
        gollum=$!
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
    [ ! -z ${gollum} ] && {
        kill -9 ${gollum}
    }
}

function check_perms () {
    echo ''
    echo -n 'Looking for files not writable by :www-data...'
    found=$(find . -not -group www-data -or \( -not -user www-data -and -not -perm -g=rwX \) | grep -v \.git | wc -l)
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
	git diff-files --quiet --ignore-submodules || {
		echo "Can't go live with uncommitted changes in $(basename ${PWD})"
		git diff-files --name-status -r --ignore-submodules
		[ "$(basename ${PWD})" = "wiki" ] && {
            echo "If lots of files are deleted, its probably the wiki "
            echo "was in a partial mklive state. Try:"
            echo "  cd wiki ; git checkout ; cd .."
		}
		exit 1
	}

	git diff-index --cached --quiet HEAD --ignore-submodules || {
		echo "Can't go live with uncommitted changes in $(basename ${PWD})"
		git diff-index --cached --name-status -r --ignore-submodules HEAD
		echo ""
		[ "$(basename ${PWD})" = "wiki" ] && {
            echo "If lots of files are deleted, its probably the wiki "
            echo "was in a partial mklive state. Try:"
            echo "  cd wiki ; git checkout ; cd .."
		}
		exit 1
	}
}