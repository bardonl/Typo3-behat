@echo off
color 0a
echo Starting servers...
java -jar selenium-server-standalone-3.3.1.jar &
start chromedriver
