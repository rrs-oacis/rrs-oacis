#!/bin/sh

cd `dirname $0`
javac ./RrsoacisNodeCollector.java && jar cvfm ./RrsoacisNodeCollector.jar MANIFEST.MF *.class
rm *.class
