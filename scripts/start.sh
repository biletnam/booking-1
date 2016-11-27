#!/bin/sh

DIR=$(dirname $(readlink -f $0))

run() {
    docker run -it -p 80:80 -v $(dirname "$DIR"):/var/www/app lamp
}

setup() {
    docker build -t lamp $DIR
}

remove() {
    docker rmi -f lamp
}

echo "Launcher script:"

for i in "$@"
do
case $i in
    -i|--install)
    setup
    shift
    ;;
    -r|--remove)
    remove
    ;;
esac
done

if [[ $# -eq 0 ]]; then
    run
fi
