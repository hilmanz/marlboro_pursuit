#!/bin/sh
#########################################################
## Deploy Marlboro The Pursuit to Production Server    ##
## inong@kana.co.id                                    ##
## 06 May 2013                                         ##
#########################################################

#cd /home/amild/public_html/staging/tools

rsync -avz --delete --exclude-from=conf/exclude-production.txt -e "ssh -l webuser" /Users/inong/Documents/repository/marlboro-hunt-2013/trunk/marlboro_hunt/ marlboro-prod-02.yellowhub.com:/home/webuser/www/marlboro.ph-phase02/
