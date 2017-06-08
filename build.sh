#!/bin/sh
# By default we use the Node.js version set in your package.json or the latest
# version from the 0.10 release
#
# You can use nvm to install any Node.js (or io.js) version you require.
#nvm install 4.0
# Install grunt-cli for running your tests or other tasks
# npm install -g grunt
# npm install -g grunt-cli
composer install
composer update

lftp -c "open -u $FTP_USER,$FTP_PASSWORD $FTP_SERVER; set ssl:verify-certificate no; mirror -R --parallel=4 --exclude-glob .git app/ /docs/app"
lftp -c "open -u $FTP_USER,$FTP_PASSWORD $FTP_SERVER; set ssl:verify-certificate no; mirror -R --parallel=4 --exclude-glob .git lib/ /docs/lib"
lftp -c "open -u $FTP_USER,$FTP_PASSWORD $FTP_SERVER; set ssl:verify-certificate no; put -O /docs/ index.php"
lftp -c "open -u $FTP_USER,$FTP_PASSWORD $FTP_SERVER; set ssl:verify-certificate no; put -O /docs/ .htaccess"
lftp -c "open -u $FTP_USER,$FTP_PASSWORD $FTP_SERVER; set ssl:verify-certificate no; put -O /docs/ web.config"

lftp -c "open -u $FTP_WEB_USER,$FTP_WEB_PASSWORD $FTP_WEB_SERVER; set ssl:verify-certificate no; mirror -R no/ /no"


