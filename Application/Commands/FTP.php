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

require dirname(__FILE__).'/../../config.php';
require Config::dir('lib') . 'Database.php';

/**
 * ページ一覧取得
 */
$sql  = 'SELECT `id`, `ref_site`, `hash` FROM `entry` WHERE `status` = :status';
$stmt = Database::run()->prepare($sql);
$stmt->bindValue(':status', Config::RASTERIZE, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$list   = is_array($result) ? array_chunk($result, Config::MAX_CHILD):$result;

/**
 * ステータスの更新
 */
$sql  = 'UPDATE `entry` SET `status` = :status WHERE id = :id';
$update = Database::run()->prepare($sql);
$update->bindValue(':status', Config::UPLOADED, PDO::PARAM_INT);

$parent = curl_multi_init();

/**
 * サイトのサマリー取得
 */
array_walk($list, function ($row) use (&$parent, &$update)  {
	$children = $fileps = array();

	/**
	 * ハンドル生成
	 */
	array_walk($row, function ($entry, $key) use (&$parent, &$children, &$fileps) {
		$child   = &$children[$key];
		$fp      = &$fileps[$key];

		$child   = curl_init();
		$archive = 'archives/'.$entry['ref_site'].'/'.$entry['hash'].'.jpg';
		$fp      = fopen(Config::dir('www') . $archive, 'r');

		curl_setopt($child, CURLOPT_URL,                     Config::FTPURI . $archive);
		curl_setopt($child, CURLOPT_CONNECTTIMEOUT,          Config::TIMEOUT);
		curl_setopt($child, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
		curl_setopt($child, CURLOPT_UPLOAD,                  true);
		curl_setopt($child, CURLOPT_INFILE,                  $fp);
		curl_setopt($child, CURLOPT_INFILESIZE,              filesize(Config::dir('www') . $archive));
		curl_multi_add_handle($parent, $child);
	});

	/**
	 * ハンドル実行
	 */
	$active = null;
	do {
		$mrc = curl_multi_exec($parent, $active);
	} while ($mrc == CURLM_CALL_MULTI_PERFORM);

	while ($active && $mrc == CURLM_OK) {
		if (curl_multi_select($parent) != -1) {
			do {
				$mrc = curl_multi_exec($parent, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		}
	}

	/**
	 * ステータスの変更
	 */
	array_walk($row, function ($entry, $key) use (&$parent, &$children, &$fileps, &$update) {
		$error_no = curl_errno($children[$key]);
		error_log('-----------  '.date('Y-m-d H:i:s')."  [archives] --------------\n", 3, Config::dir('log') . 'apperror.log');
		error_log($entry['id'].':'.$error_no."\n", 3, Config::dir('log') . 'apperror.log');
		if ($error_no === 0) {
			$update->bindValue(':id', $entry['id'], PDO::PARAM_INT);
			$update->execute();
		}
		curl_multi_remove_handle($parent, $children[$key]);
		fclose($fileps[$key]);
	});
	$children = $fileps = null;
});

curl_multi_close($parent);
