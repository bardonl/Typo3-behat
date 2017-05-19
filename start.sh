#!/usr/bin/env bash
echo Starting servers...
java -jar selenium-server-standalone-3.4.0.jar &
./chromedriver
