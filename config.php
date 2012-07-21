<?php
/**
 * The MIT License
 * 
 * Copyright (c) 2011-2012 hitSuji
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package         hitSuji
 * @copyright       Copyright (c) 2011-2012 sheeps.me
 * @author          Yujiro Takahashi <yujiro3@gmail.com>
 * @filesource
 */

namespace hitSuji;

/**
 * Configクラス
 *
 * @package         hitSuji
 * @subpackage      Config
 * @author          Yujiro Takahashi <yujiro3@gmail.com>
 */
class Config {
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
	
	/**
	 * 最大子数
	 * @const integer
	 */
	const MAX_CHILD = 4;
	
	/**
	 * 最大行数
	 * @const integer
	 */
	const MAX_ROWS = 20;

	/**
	 * タイムアウト
	 * @const integer
	 */
	const TIMEOUT = 5;
	
	/**
	 * 初期コード
	 * @const integer
	 */
	const INIT = 1;
	
	/**
	 * ラスタライズ
	 * @const integer
	 */
	const RASTERIZE = 2;
	
	/**
	 * 送信済み
	 * @const integer
	 */
	const UPLOADED = 3;
	
	/**
	 * JSONの作成
	 * @const integer
	 */
	const BUILD = 4;
	
	/**
	 * 終了
	 * @const integer
	 */
	const COMPLETE = 5;
	
	/**
	 * HTTPコードOK
	 * @const integer
	 */
	const SUCCESS = 200;
	
	/**
	 * エージェント
	 * @const string
	 */
	const AGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0';
	
	/**
	 * エンコードタイプ
	 * @const string
	 */
	const ENCODE_TYPE = 'UTF-8';
	
	/**
	 * 基本URL
	 * @const string
	 */
	const WP = '/';
	
	/**
	 * 有効
	 * @const integer
	 */
	const ENABLE = 1;
	
	/**
	 * 無効
	 * @const integer
	 */
	const DISABLE = 0;
	
	/**
	 * 情報
	 * @const array
	 */
	static public $info;

	/**
	 * 初期設定
	 *
	 * @access public
	 * @return void
	 */
	public static function iniSet() {
		/* 各種ディレクトリ設定 */
		define('DS', DIRECTORY_SEPARATOR);                                  // ディレクトリの区切り
		define('BASE_DIR', dirname(__FILE__).DS);                           // ベース
		$path = BASE_DIR.'Library'.DS .PATH_SEPARATOR. 
				BASE_DIR.'Library'.DS.'PEAR'.DS. PATH_SEPARATOR . 
				ini_get('include_path');
		ini_set('include_path', $path);

		ini_set('default_charset', 'utf-8');                                // マルチバイトデフォルト設定
		mb_language('ja');                                                  // 使用言語設定
		mb_internal_encoding('utf-8');                                      // 内部エンコード設定
		date_default_timezone_set('Asia/Tokyo');                            // タイムゾーン設定

		/* エラー設定 */
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', true);
		ini_set('display_startup_errors', false);
    }

	/**
	 * ディレクトリの取得
	 *
	 * @access public
	 * @return string
	 */
	public static function dir($name) {
		$dirs = array(
			'base' => BASE_DIR,                                     // ベース
			'app'  => BASE_DIR.'Application'.DS,                    // アプリケーション
			'cmd'  => BASE_DIR.'Application'.DS.'Commands'.DS,      // 実行系
			'db'   => BASE_DIR.'Application'.DS.'Database'.DS,      // データベース
			'log'  => BASE_DIR.'logs'.DS,                           // ログ
			'www'  => BASE_DIR.'www'.DS,                            // ドキュメントルート
			'lib'  => BASE_DIR.'Library'.DS,                        // ライブラリ
		);
		return $dirs[$name];
	}

	/**
	 * 情報の取得
	 *
	 * @access public
	 * @param string $key 
	 * @return string
	 */
	public static function get($key) {
		return self::$info[$key];
	}

	/**
	 * 情報の設定
	 *
	 * @access public
	 * @param string $key 
	 * @param mixed  $value 
	 * @return string
	 */
	public static function set($key, $value) {
		self::$info[$key] = $value;
	}
}

Config::iniSet();

