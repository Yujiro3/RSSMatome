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
 * ページ番号の取得
 */
$stmt = Database::run()->prepare('SELECT `number` FROM `increment` LIMIT 1');
$stmt->execute();
$number = $stmt->fetchColumn();

/**
 * ページ一覧取得
 */
$sql  = 'SELECT ent.`id`, ent.`ref_site`, ent.`hash`, ent.`title`, '.
               'ent.`date`, ent.`link`, sit.`title` `blog`, sit.`url` '.
        'FROM `entry` ent LEFT JOIN `site` sit ON ent.`ref_site` = sit.`id` '.
        'WHERE ent.`status` = :status ORDER BY ent.`date` DESC';
$stmt = Database::run()->prepare($sql);
$stmt->bindValue(':status', Config::UPLOADED, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$list   = is_array($result) ? array_chunk($result, Config::MAX_ROWS):$result;

/**
 * ステータスの更新
 */
$sql  = 'UPDATE `entry` SET `status` = :status WHERE id = :id';
$update = Database::run()->prepare($sql);
$update->bindValue(':status', Config::BUILD, PDO::PARAM_INT);


/**
 * ファイルの生成
 */
array_walk($list, function ($row) use (&$update, &$number)  {
	$result = array();

	/**
	 * データの整形
	 */
	array_walk($row, function ($entry) use(&$result, &$update) {
		$result[] = array(
			'title' => $entry['title'],
			'date'  => $entry['date'],
			'link'  => $entry['link'],
			'src'   => '/archives/'.$entry['ref_site'].'/'.$entry['hash'].'.jpg',
			'blog'  => $entry['blog'],
			'url'   => $entry['url'],
		);

		$update->bindValue(':id', $entry['id'], PDO::PARAM_INT);
		$update->execute();
	});
	$json = 'ajax/'.$number.'.json';
	file_put_contents(Config::dir('www') .$json, json_encode($result));

	$curl = curl_init();
	$fp   = fopen(Config::dir('www') .$json, 'r');

	curl_setopt($curl, CURLOPT_URL,                     Config::FTPURI . $json);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,          Config::TIMEOUT);
	curl_setopt($curl, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
	curl_setopt($curl, CURLOPT_UPLOAD,                  true);
	curl_setopt($curl, CURLOPT_INFILE,                  $fp);
	curl_setopt($curl, CURLOPT_INFILESIZE,              filesize(Config::dir('www') . $json));
	curl_exec($curl);

	$error_no = curl_errno($curl);
	error_log('-----------  '.date('Y-m-d H:i:s')."  [ajax]--------------\n", 3, Config::dir('log') . 'apperror.log');
	error_log($number.':'.$error_no."\n", 3, Config::dir('log') . 'apperror.log');

	curl_close($curl);
	fclose($fp);

	$number++;
});


/**
 * JSON番号の更新
 */
$sql  = 'UPDATE `increment` SET `number` = :number LIMIT 1';
$stmt = Database::run()->prepare($sql);
$stmt->bindValue(':number', $number, PDO::PARAM_INT);
$stmt->execute();

echo "\n";
