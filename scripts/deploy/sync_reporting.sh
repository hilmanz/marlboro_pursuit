#!/bin/sh
#########################################################
## Deploy AExchange 2013 from Staging to Production    ##
## Phase 1                                             ##
## inong@kana.co.id                                    ##
## 09 April 2013                                       ##
#########################################################
## Login ke server Staging sebagai user amild (IP : 117.54.1.92)
##
## 20130409	: Phase 1
##            - Initial Deployment to Production Test.
##
##


#cd /home/amild/public_html/staging/tools

rsync -avz --delete --exclude-from=conf/exclude-reporting.txt -e "ssh -l rendra" /Users/inong/Documents/repository/a360/trunk/athreesix/ 117.54.1.82:/home/rendra/
