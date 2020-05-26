#!/usr/bin/env sh
cd $(dirname ${0})

mkdir workspace AGENT MAP LOG
git clone https://github.com/roborescue/rcrs-server.git roborescue
sed --in-place 's/apache-ant-\*\/bin\/ant .*"/gradlew completeBuild"/' \
    script/rrscluster
