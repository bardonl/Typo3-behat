@echo off
color 0a
echo Starting servers...
java -jar selenium-server-standalone-3.4.0.jar &
start chromedriver
