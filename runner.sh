#!/bin/bash

MY_PATH="`dirname \"$0\"`"              # relative
while /bin/true; do
    setsid nohup php ${MY_PATH}/dps_rotations_checker.php > /dev/null &
done

