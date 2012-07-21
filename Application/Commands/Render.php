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
$sql  = 'SELECT ent.`id`, ent.`ref_site`, ent.`hash`, ent.`link`, sit.`crop` '.
        'FROM `entry` ent LEFT JOIN `site` sit ON ent.`ref_site` = sit.`id` '.
        'WHERE ent.`status` = :status';
$stmt = Database::run()->prepare($sql);
$stmt->bindValue(':status', Config::INIT, PDO::PARAM_INT);
$stmt->execute();

$list = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$sql  = 'UPDATE `entry` SET `status` = :status WHERE id = :id';
$update = Database::run()->prepare($sql);
$update->bindValue(':status', Config::RASTERIZE, PDO::PARAM_INT);

/**
 * キャプチャの生成
 */
array_walk($list, function ($row) use (&$update) {
	$command = '/usr/bin/phantomjs '. Config::dir('cmd').'render.js';
	$archive = Config::dir('www').'archives/'.$row['ref_site'].'/';
	if (!file_exists($archive)) {
		mkdir($archive, 0700, true);
	}
	exec($command.' "'.$row['link'].'" "'.$archive.$row['hash'].'.png" '.$row['crop']);


	$command = 'gm convert -resize 480x -flatten ';
	exec($command.' "'.$archive.$row['hash'].'.png" "'.$archive.$row['hash'].'.jpg"');
	unlink($archive.$row['hash'].'.png');

	$update->bindValue(':id', $row['id'], PDO::PARAM_INT);
	$update->execute();
});
