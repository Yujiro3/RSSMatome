CREATE TABLE `entry` (
  `id` integer NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ref_site` integer NOT NULL,
  `status` integer NOT NULL DEFAULT '0',
  `hash` varchar(48) UNIQUE,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `link` varchar(255) NOT NULL,
  CONSTRAINT entry_pky PRIMARY KEY (id)
);

-- --------------------------------------------------------

CREATE TABLE `increment` (
  `number` integer NOT NULL
);

INSERT INTO `increment` (`number`) VALUES (1);

-- --------------------------------------------------------

CREATE TABLE `site` (
  `id` integer NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `rss` varchar(128) NOT NULL,
  `type` varchar(8) NOT NULL,
  `category` varchar(8) NOT NULL DEFAULT 'vip',
  `crop` varchar(24) NOT NULL,
  CONSTRAINT site_pky PRIMARY KEY (id)
);
CREATE INDEX category_idx ON site (category);


INSERT INTO `site` VALUES (1, '2012-07-01 00:00:00', '痛いニュース(ﾉ∀`) - ライブドアブログ', 'http://blog.livedoor.jp/dqnplus/', 'http://blog.livedoor.jp/dqnplus/atom.xml', 'atom', 'vip', '238x320x640x640');
INSERT INTO `site` VALUES (2, '2012-07-01 00:00:00', 'アルファルファモザイク', 'http://alfalfalfa.com/', 'http://alfalfalfa.com/atom.xml', 'atom', 'vip', '308x1774x640x640');
INSERT INTO `site` VALUES (3, '2012-07-01 00:00:00', 'ニュー速クオリティ', 'http://news4vip.livedoor.biz/', 'http://news4vip.livedoor.biz/atom.xml', 'atom', 'vip', '238x2170x640x640');
INSERT INTO `site` VALUES (4, '2012-07-01 00:00:00', 'ハムスター速報', 'http://hamusoku.com/', 'http://hamusoku.com/atom.xml', 'atom', 'vip', '232x630x640x640');
INSERT INTO `site` VALUES (5, '2012-07-01 00:00:00', '暇人＼(^o^)／速報', 'http://himasoku.com/', 'http://himasoku.com/atom.xml', 'atom', 'vip', '294x903x640x640');
INSERT INTO `site` VALUES (6, '2012-07-01 00:00:00', 'きゃっつあいニュース', 'http://rastaneko.blog.fc2.com/', 'http://rastaneko.blog.fc2.com/?xml', 'rss', 'vip', '60x1320x640x640');
INSERT INTO `site` VALUES (7, '2012-07-01 00:00:00', 'ねたAtoZ', 'http://netaatoz.jp/', 'http://netaatoz.jp/atom.xml', 'atom', 'vip', '240x730x640x640');
INSERT INTO `site` VALUES (8, '2012-07-01 00:00:00', '人生VIP職人ブログwww', 'http://workingnews.blog117.fc2.com/', 'http://workingnews.blog117.fc2.com/?xml', 'rss', 'vip', '122x677x640x640');
INSERT INTO `site` VALUES (9, '2012-07-01 00:00:00', 'もみあげチャ～シュ～', 'http://michaelsan.livedoor.biz/', 'http://michaelsan.livedoor.biz/atom.xml', 'atom', 'vip', '320x355x640x640');
INSERT INTO `site` VALUES (10, '2012-07-01 00:00:00', 'ゴールデンタイムズ', 'http://blog.livedoor.jp/goldennews/', 'http://blog.livedoor.jp/goldennews/atom.xml', 'atom', 'vip', '310x1624x640x640');
INSERT INTO `site` VALUES (11, '2012-07-01 00:00:00', 'VIPPERな俺', 'http://blog.livedoor.jp/news23vip/', 'http://blog.livedoor.jp/news23vip/atom.xml', 'atom', 'vip', '340x2034x640x640');
INSERT INTO `site` VALUES (12, '2012-07-01 00:00:00', 'BIPブログ', 'http://bipblog.com/', 'http://bipblog.com/atom.xml', 'atom', 'vip', '120x560x640x640');
INSERT INTO `site` VALUES (13, '2012-07-01 00:00:00', '２のまとめＲ', 'http://2r.ldblog.jp/', 'http://2r.ldblog.jp/atom.xml', 'atom', 'vip', '210x400x640x640');
INSERT INTO `site` VALUES (14, '2012-07-01 00:00:00', 'あじゃじゃしたー', 'http://blog.livedoor.jp/chihhylove/', 'http://blog.livedoor.jp/chihhylove/atom.xml', 'atom', 'vip', '230x680x640x640');
INSERT INTO `site` VALUES (15, '2012-07-01 00:00:00', 'ワロタニッキ', 'http://blog.livedoor.jp/hisabisaniwarota/', 'http://blog.livedoor.jp/hisabisaniwarota/atom.xml', 'atom', 'vip', '287x206x640x640');
INSERT INTO `site` VALUES (16, '2012-07-01 00:00:00', 'コピペ情報局', 'http://news.2chblog.jp/', 'http://news.2chblog.jp/atom.xml', 'atom', 'vip', '103x734x640x640');
INSERT INTO `site` VALUES (17, '2012-07-01 00:00:00', 'ニュース２ちゃんねる', 'http://news020.blog13.fc2.com/', 'http://news020.blog13.fc2.com/?xml', 'rss', 'vip', '130x610x640x640');
INSERT INTO `site` VALUES (18, '2012-07-01 00:00:00', '哲学ニュースnwk', 'http://blog.livedoor.jp/nwknews/', 'http://blog.livedoor.jp/nwknews/atom.xml', 'atom', 'vip', '230x780x640x640');
INSERT INTO `site` VALUES (19, '2012-07-01 00:00:00', 'キニ速', 'http://blog.livedoor.jp/kinisoku/', 'http://blog.livedoor.jp/kinisoku/atom.xml', 'atom', 'vip', '360x796x640x640');
INSERT INTO `site` VALUES (20, '2012-07-01 00:00:00', 'オタコム', 'http://0taku.livedoor.biz/', 'http://0taku.livedoor.biz/atom.xml', 'atom', 'vip', '360x546x640x640');
