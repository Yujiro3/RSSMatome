RSSまとめ
======================
WEBサイトのスクリーンショットをPhantomJSにより生成するRSSリーダー。

PhantomJSのインストール
------

[PhantomJS](https://code.google.com/p/phantomjs/)

### ubuntu ###
    $ cd /usr/local/share/
    $ sudo wget http://phantomjs.googlecode.com/files/phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2
    $ sudo tar xjvf phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2
    $ sudo rm -rf phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2
    $ sudo mv phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2 phantomjs
    $ sudo ln -s /usr/local/share/phantomjs/bin/phantomjs /usr/local/bin/phantomjs

日本語フォントのインストール
------

[フォントをインストールするには](https://wiki.ubuntulinux.jp/UbuntuTips/Desktop/InstallFont)

    $ sudo aptitude install fontconfig
    
    $ sudo aptitude install fonts-takao
    $ sudo aptitude install fonts-ipafont
    $ sudo aptitude install otf-ipaexfont-gothic otf-ipaexfont-mincho
    $ sudo aptitude install fonts-horai-umefont
    $ sudo aptitude install fonts-umeplus
    $ sudo aptitude install ttf-sazanami-gothic ttf-sazanami-mincho
    $ sudo aptitude install ttf-kochi-gothic ttf-kochi-mincho
    $ sudo aptitude install ttf-vlgothic
    
    $ sudo fc-cache -fv

設定ファイルの編集
----------------
config.phpにDB情報とFTP情報を設定します。

    /**
     * DSN
     * @const string
     */
    const DSN = 'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=MatomeRSS';
    
    /**
     * ユーザー
     * @const string
     */
    const USER = 'MatomeRSS';
    
    /**
     * パスワード
     * @const string
     */
    const PASSWORD = 'passwd';
    
    /**
     * FTP URI
     * @const string
     */
    const FTPURI = 'ftp://username:password@ftp.hostname/';

初期ファイルのアップロード
----------------
wwwディレクトリの内容をアップロードします。  
setup.phpを実行するとFTPでアップロードします。

    $ chmod a+x ~/setup.php
    $ ~/setup.php


ファイル権限の変更
----------------
コマンドディレクトリ内のスクリプトに実行権限を付与します。  

    $ cd ~/Application/Commands
    $ chmod a+x ./*.php

crontabの設定
----------------
コマンドディレクトリ内のTrigger.phpをcrontabに追加します。  

    0   *  *   *   *     ~/Application/Commands/Trigger.php >/dev/null 2>&1


ライセンス
----------
Copyright &copy; 2012 Yujiro Takahashi  
Licensed under the [MIT License][MIT].  
Distributed under the [MIT License][MIT].  

[MIT]: http://www.opensource.org/licenses/mit-license.php
