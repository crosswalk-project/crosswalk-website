desc="Show difference between latest local live-* branch and live server"
function () {
	dir=$(dirname "$0")
	echo $dir
	LIVE=$(wget -qO - https://crosswalk-project.org/REVISION)
}
