cd /d %~dp0

set BATDIR=%~dp0

sudo docker run --rm -p 6040:6040 -v %BATDIR%src:/adf/src -v %BATDIR%public:/adf/public -v %BATDIR%ruby:/adf/ruby -t -i test/test:1.0  /bin/bash

pause