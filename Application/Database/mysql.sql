CREATE TABLE IF NOT EXISTS `entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  `ref_site` int(10) unsigned NOT NULL COMMENT 'サイトID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'captchaフラグ',
  `hash` varchar(48) NOT NULL COMMENT 'ハッシュ',
  `title` varchar(255) NOT NULL COMMENT '件名',
  `description` varchar(255) NOT NULL COMMENT '概要',
  `date` datetime NOT NULL COMMENT '日付',
  `link` varchar(255) NOT NULL COMMENT 'リンク',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8 COMMENT='記事';

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `increment` (
  `number` int(1) unsigned NOT NULL COMMENT 'JSONファイル番号'
) ENGINE=MYISAM DEFAULT CHARSET=latin1 COMMENT='インクリメント';

INSERT INTO `increment` (`number`) VALUES (1);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日',
  `title` varchar(128) NOT NULL COMMENT 'タイトル',
  `url` varchar(128) NOT NULL COMMENT 'サイトURL',
  `rss` varchar(128) NOT NULL COMMENT 'RSS',
  `type` varchar(8) NOT NULL COMMENT 'タイプ',
  `category` varchar(8) NOT NULL DEFAULT 'vip' COMMENT 'カテゴリ',
  `crop` varchar(24) NOT NULL COMMENT 'crop値',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8 COMMENT='サイト情報';

INSERT INTO `site` (`id`, `modified`, `title`, `url`, `rss`, `type`, `category`, `crop`) VALUES
(1, '2012-07-01 00:00:00', '痛いニュース(ﾉ∀`) - ライブドアブログ', 'http://blog.livedoor.jp/dqnplus/', 'http://blog.livedoor.jp/dqnplus/atom.xml', 'atom', 'vip', '238x320x640x640'),
(2, '2012-07-01 00:00:00', 'アルファルファモザイク', 'http://alfalfalfa.com/', 'http://alfalfalfa.com/atom.xml', 'atom', 'vip', '308x1774x640x640'),
(3, '2012-07-01 00:00:00', 'ニュー速クオリティ', 'http://news4vip.livedoor.biz/', 'http://news4vip.livedoor.biz/atom.xml', 'atom', 'vip', '238x2170x640x640'),
(4, '2012-07-01 00:00:00', 'ハムスター速報', 'http://hamusoku.com/', 'http://hamusoku.com/atom.xml', 'atom', 'vip', '232x630x640x640'),
(5, '2012-07-01 00:00:00', '暇人＼(^o^)／速報', 'http://himasoku.com/', 'http://himasoku.com/atom.xml', 'atom', 'vip', '294x903x640x640'),
(6, '2012-07-01 00:00:00', 'きゃっつあいニュース', 'http://rastaneko.blog.fc2.com/', 'http://rastaneko.blog.fc2.com/?xml', 'rss', 'vip', '60x1320x640x640'),
(7, '2012-07-01 00:00:00', 'ねたAtoZ', 'http://netaatoz.jp/', 'http://netaatoz.jp/atom.xml', 'atom', 'vip', '240x730x640x640'),
(8, '2012-07-01 00:00:00', '人生VIP職人ブログwww', 'http://workingnews.blog117.fc2.com/', 'http://workingnews.blog117.fc2.com/?xml', 'rss', 'vip', '122x677x640x640'),
(9, '2012-07-01 00:00:00', 'もみあげチャ～シュ～', 'http://michaelsan.livedoor.biz/', 'http://michaelsan.livedoor.biz/atom.xml', 'atom', 'vip', '320x355x640x640'),
(10, '2012-07-01 00:00:00', 'ゴールデンタイムズ', 'http://blog.livedoor.jp/goldennews/', 'http://blog.livedoor.jp/goldennews/atom.xml', 'atom', 'vip', '310x1624x640x640'),
(11, '2012-07-01 00:00:00', 'VIPPERな俺', 'http://blog.livedoor.jp/news23vip/', 'http://blog.livedoor.jp/news23vip/atom.xml', 'atom', 'vip', '340x2034x640x640'),
(12, '2012-07-01 00:00:00', 'BIPブログ', 'http://bipblog.com/', 'http://bipblog.com/atom.xml', 'atom', 'vip', '120x560x640x640'),
(13, '2012-07-01 00:00:00', '２のまとめＲ', 'http://2r.ldblog.jp/', 'http://2r.ldblog.jp/atom.xml', 'atom', 'vip', '210x400x640x640'),
(14, '2012-07-01 00:00:00', 'あじゃじゃしたー', 'http://blog.livedoor.jp/chihhylove/', 'http://blog.livedoor.jp/chihhylove/atom.xml', 'atom', 'vip', '230x680x640x640'),
(15, '2012-07-01 00:00:00', 'ワロタニッキ', 'http://blog.livedoor.jp/hisabisaniwarota/', 'http://blog.livedoor.jp/hisabisaniwarota/atom.xml', 'atom', 'vip', '287x206x640x640'),
(16, '2012-07-01 00:00:00', 'コピペ情報局', 'http://news.2chblog.jp/', 'http://news.2chblog.jp/atom.xml', 'atom', 'vip', '103x734x640x640'),
(17, '2012-07-01 00:00:00', 'ニュース２ちゃんねる', 'http://news020.blog13.fc2.com/', 'http://news020.blog13.fc2.com/?xml', 'rss', 'vip', '130x610x640x640'),
(18, '2012-07-01 00:00:00', '哲学ニュースnwk', 'http://blog.livedoor.jp/nwknews/', 'http://blog.livedoor.jp/nwknews/atom.xml', 'atom', 'vip', '230x780x640x640'),
(19, '2012-07-01 00:00:00', 'キニ速', 'http://blog.livedoor.jp/kinisoku/', 'http://blog.livedoor.jp/kinisoku/atom.xml', 'atom', 'vip', '360x796x640x640'),
(20, '2012-07-01 00:00:00', 'オタコム', 'http://0taku.livedoor.biz/', 'http://0taku.livedoor.biz/atom.xml', 'atom', 'vip', '360x546x640x640');
