#!/bin/sh

cd `dirname $0`
javac -source 7 -target 7 ./RrsoacisNodeCollector.java && jar cvfm ./RrsoacisNodeCollector.jar MANIFEST.MF *.class
rm *.class
