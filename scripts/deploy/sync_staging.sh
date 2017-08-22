#!/bin/sh

#rsync -avz --delete --exclude-from=conf/exclude-staging.txt -e "ssh -l a360" /Users/inong/Documents/repository/a360/trunk/athreesix/ 119.2.66.29:/home/a360/
#rsync -avz --exclude-from=conf/exclude-staging.txt -e "ssh -l a360" /Users/inong/Documents/repository/a360/trunk/athreesix/ 119.2.66.29:/home/a360/
rsync -avz --delete --exclude-from=conf/exclude-staging.txt -e "ssh -l amild" /Users/inong/Documents/repository/a360/trunk/athreesix/ 117.54.1.98:/home/amild/public_html/staging/
