#!/bin/bash

MY_PATH="`dirname \"$0\"`"              # relative
while /bin/true; do
    for i in {1..25}
    do
       echo "Welcome $i times"
       setsid nohup /usr/bin/flock -w 0 /tmp/cron${i}.lock php ${MY_PATH}/runs_rotation.php > /dev/null &
    done
done

