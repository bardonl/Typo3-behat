#!/usr/bin/env bash
echo Starting servers...
java -jar selenium-server-standalone-3.3.1.jar &
/bin/bash ./chromedriver