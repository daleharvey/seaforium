A YayHooray.com clone
=====================

Installing
==========

 * Add 127.0.0.1 yayhooray.dev to /etc/hosts
 * Clone https://github.com/castis/seaforium to your github account

    $ git clone git@github.com:%YOURNAME%/seaforium.git
    $ cd seaforium

Then follow the instructions for your platform

### Ubuntu (Oneiric Ocelot)

    $ sudo apt-get install tasksel python-pip
    $ sudo tasksel install lamp-server
    $ sudo pip install simplejson lxml MySQL-Python requests

    $ cp conf/yayhooray.conf.default conf/yayhooray.conf
    $ cp application/config/config.default.php application/config/config.php
    $ cp application/config/database.default.php application/config/database.php
    $ cp tests/yay.ini.default tests/yay.ini

In the above files replace the required values, replacements are marked by %REPLACE%

    $ sudo ln -s %FORUM_ROOT%/conf/yayhooray.conf /etc/apache2/sites-available/yayhooray.conf

    $ sudo a2enmod rewrite
    $ sudo a2ensite yayhooray.conf
    $ sudo service apache2 reload

    $ ./yayclient reset

Now open http://yayhooray.dev in your browser and you should be good to go :)


### Apple OSX (Lion)

    $ sudo pip install simplejson lxml MySQL-Python requests

Install XCode from the App Store

Download and Install mysql from http://www.mysql.com/downloads/mysql/ add the following to you `.bashrc` file.

    $ export PATH=/usr/local/mysql/:$PATH
    $ export DYLD_LIBRARY_PATH=/usr/local/mysql/lib/

Open `System Preferences -> Sharing` and enable Web Sharing

open /etc/apache2/httpd.conf and uncomment

    #LoadModule php5_module libexec/apache2/libphp5.so

Enable the php ini

    $ sudo cp /etc/php.ini.default /etc/php.ini

Edit /etc/php.ini

    mysqli.default_socket = /tmp/mysql.sock

Copy over the config files

    $ cp conf/yayhooray.conf.default conf/yayhooray.conf
    $ cp application/config/config.default.php application/config/config.php
    $ cp application/config/database.default.php application/config/database.php
    $ cp tests/yay.ini.default tests/yay.ini

In the above files replace the required values, replacements are marked by %REPLACE%

    $ sudo ln -s /Users/daleharvey/src/seaforium/conf/yayhooray.conf /etc/apache2/users/yayhooray.conf

    $ sudo apachectl restart

Now open http://yayhooray.dev in your browser and you should be good to go :)
