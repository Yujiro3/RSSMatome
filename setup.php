#!/usr/bin/php -q
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

use \hitSuji\Config;
use \hitSuji\Database;

require dirname(__FILE__).'/config.php';

/**
 * 初期ファイルリスト
 */
$list = array(
	'assets/css/bootstrap.css',
	'assets/css/bootstrap-responsive.min.css',
	'assets/img/glyphicons-halflings.png',
	'assets/img/glyphicons-halflings-white.png',
	'assets/img/loading.gif',
	'assets/js/jquery.tmpl.min.js',
	'assets/js/jquery-1.7.2.min.js',
	'index.html',
);

/**
 * 初期ファイルの転送
 */
array_walk($list, function ($name) {
	$curl = curl_init();
	$fp   = fopen(Config::dir('www') .$name, 'r');

	curl_setopt($curl, CURLOPT_URL,                     Config::FTPURI . $name);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,          Config::TIMEOUT);
	curl_setopt($curl, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
	curl_setopt($curl, CURLOPT_UPLOAD,                  true);
	curl_setopt($curl, CURLOPT_INFILE,                  $fp);
	curl_setopt($curl, CURLOPT_INFILESIZE,              filesize(Config::dir('www') . $name));
	curl_exec($curl);
	curl_close($curl);
	fclose($fp);
});

