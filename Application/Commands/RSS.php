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
 * サイト一覧取得
 */
$summary = array(
	'rss' => function ($xml) {
		$result = array();
		foreach ($xml->item as $row) {
			$dc = $row->children('http://purl.org/dc/elements/1.1/');
			$result[] = array(
				'title' => strval($row->title),
				'desc'  => strval(trim($row->description)),
				'date'  => date('Y-m-d H:i:s', strtotime($dc->date)),
				'link'  => strval($row->link)
			);
		}
		return $result;
	},
	'atom' => function ($xml) {
		$result = array();
		foreach ($xml->entry as $row) {
			$result[] = array(
				'title' => strval($row->title),
				'desc'  => strval(trim($row->summary)),
				'date'  => date('Y-m-d H:i:s', strtotime($row->modified)),
				'link'  => strval($row->link->attributes()->href)
			);
		}
		return $result;
	}
);

/**
 * サイト一覧取得
 */
$sql  = 'SELECT `id`, `rss`,`type`  FROM `site` WHERE `category` = :category';
$stmt = Database::run()->prepare($sql);
$stmt->bindValue(':category', 'vip', PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$list   = is_array($result) ? array_chunk($result, Config::MAX_CHILD):$result;
$parent = curl_multi_init();

/**
 * サイトのサマリー取得
 */
array_walk($list, function ($row) use (&$parent)  {
	$children = array();

	/**
	 * ハンドル生成
	 */
	array_walk($row, function ($site, $key) use (&$parent, &$children) {
		$child = &$children[$key];
		$child = curl_init();

		curl_setopt($child, CURLOPT_CONNECTTIMEOUT, Config::TIMEOUT);
		curl_setopt($child, CURLOPT_HEADER,         false);
		curl_setopt($child, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($child, CURLOPT_USERAGENT,      Config::AGENT);
		curl_setopt($child, CURLOPT_URL,            $site['rss']);

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
	 * 結果の取得
	 */
	array_walk($row, function ($site, $key) use (&$parent, &$children) {
		global $summary;
		$report  = curl_getinfo($children[$key]);
		if (Config::SUCCESS == $report['http_code']) {
			$content = curl_multi_getcontent($children[$key]);
			$xml     = new \SimpleXMLElement($content);
			$result  = $summary[$site['type']]($xml);

			/**
			 * サマリー情報の保存
			 */
			$sql = 'INSERT IGNORE INTO `entry` (`ref_site`, `status`, `hash`, `title`, `description`, `date`, `link`) '.
				                       'VALUES (:ref_site,  :status,  :hash,  :title,  :desc,         :date,  :link)';
			$stmt  = Database::run()->prepare($sql);
			$stmt->bindValue(':ref_site', $site['id'],  PDO::PARAM_INT);
			$stmt->bindValue(':status',   Config::INIT, PDO::PARAM_INT);

			array_walk($result, function ($row) use (&$stmt) {
				$stmt->bindValue(':hash',  md5($row['link']), PDO::PARAM_STR);
				$stmt->bindValue(':title', $row['title'],     PDO::PARAM_STR);
				$stmt->bindValue(':desc',  $row['desc'],      PDO::PARAM_STR);
				$stmt->bindValue(':date',  $row['date'],      PDO::PARAM_STR);
				$stmt->bindValue(':link',  $row['link'],      PDO::PARAM_STR);
	
				$stmt->execute();
			});
		}
		curl_multi_remove_handle($parent, $children[$key]);
	});
	$children = null;
});

curl_multi_close($parent);

