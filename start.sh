#!/bin/sh

run() {
    docker run -it -p 80:80 -v $(PWD):/var/www/app lamp
}

setup() {
    docker build -t lamp $(PWD)
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