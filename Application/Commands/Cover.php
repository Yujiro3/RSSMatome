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

ob_start();
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>まとめサイトのまとめ</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!-- Le styles -->
		<link href="/assets/css/bootstrap.css" rel="stylesheet">
		<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		.sidebar-nav {
			padding: 9px 0;
		}
		</style>
		<link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/">まとめサイトのまとめ</a>

					<div class="nav-collapse">
						<ul class="nav">
							<li class="active"><a href="/">ホーム</a></li>
							<p class="navbar-text pull-right">
							</p>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<section id="responsive">
				<div id="item-list"></div>
				<script id="item-template" type="text/x-jquery-tmpl">
				<div class="row span12">
					<h2>${title}</h2>
					<a href="${link}"><img src="${src}" width="480" heigit="480"></a><br>
					<span>${date}</span>
					<hr>
				</div>
				</script>
			</section>
		</div><!-- /container -->
	 <!-- Footer
		================================================== -->
		<footer class="footer">
			<p>sheeps.me</p>
		</footer>
<script src="/assets/js/jquery-1.7.2.min.js"></script>
<script src="/assets/js/jquery.tmpl.min.js"></script>
<script type="text/javascript">
(function($) {
	/**
	 * jqSummary
	 *
	 * @access public
	 * @return jQuery
	 */
	$.fn.jqSummary = function () {
		/**
		 * ページ番号
		 */
		var page = <?=$number - 1?>;

		/**
		 * スコープ設定
		 */
		var $self = $(this);

		/**
		 * コンテンツの取得
		 */
		function getContents() {

			$.getJSON (
				'/ajax/' + page + '.json', 
				function (data) {
					$.each(data, function(i, row) {
						addItem(row);
					});
					page -= 1;
					if (0 < page) {
						setTimeout(function () {
							$(document).on('scroll', cScroll);
						}, 10);
					}
				}
			);
		};

		/**
		 * アイテムの追加
		 */
		function addItem(row) {
			var $item = $('#item-template').tmpl([row]);

			$item.on('click', function() {
				var screen = $(this).attr('id').replace('screen-','');
				window.location.href = '/message/'+ screen +'/';
			});
			$self.append($item);
		};

		/**
		 * 縦スクロール処理
		 */
		function cScroll(event) {
			var height = $('body').height() - $(window).height() + 20;

			if (height <= $(document).scrollTop()) {
				/* 連続読み込み防止 */
				$(document).off('scroll');
				setTimeout(getContents, 10);
			}
		};

		getContents();

		return this;
	};
})(jQuery);

//$(document).bind('mobileinit', function(){
	$('#item-list').jqSummary();
//});
navigator.userAgent = 'msie';
</script>
<!--script src="/assets/js/jquery.mobile-1.1.0.min.js"></script-->
	</body>
</html>
<?
$body = ob_get_contents();
ob_end_clean();

$file = 'index.html';
file_put_contents(Config::dir('cmd').$file, $body);


$curl = curl_init();
$fp   = fopen(Config::dir('cmd').$file, 'r');

curl_setopt($curl, CURLOPT_URL,                     Config::FTPURI . $file);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,          Config::TIMEOUT);
curl_setopt($curl, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
curl_setopt($curl, CURLOPT_UPLOAD,                  true);
curl_setopt($curl, CURLOPT_INFILE,                  $fp);
curl_setopt($curl, CURLOPT_INFILESIZE,              filesize(Config::dir('cmd').$file));
curl_exec($curl);
curl_close($curl);
fclose($fp);

