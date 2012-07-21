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
 * Databaseクラス
 *
 * @package         hitSuji
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gmail.com>
 */
class Database {
	/**
	 * 接続待機終了までの時間（秒）
	 * @const integer
	 */
	const TIMEOUT = 4;

	/**
	 * マスターサーバ
	 * @var object
	 */
	public $db;

	/**
	 * Singleton用インスタンス
	 * @var object
	 */
	private static $_instance;

	/**
	 * コンストラクタ
	 *
	 * @access private
	 * @return void
	 */
	private function __construct() {
		//$this->db = new \PDO('sqlite:'.Config::dir('db'). 'sqlite.db'); 

		$this->db = new \PDO(
			Config::DSN,
			Config::USER,
			Config::PASSWORD,
			array(
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8, time_zone = '+9:00'",
			)
		); 
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(\PDO::ATTR_TIMEOUT, self::TIMEOUT);
	}

	/**
	 * Singleton メソッド
	 *
	 * Example:
	 * <code>
	 * Database::run()->prepare($sql);
	 * </code>
	 *
	 * @access public
	 * @return self object
	 */
	public static function run() {
		return self::$_instance;
	}

	/**
	 * 文を実行する準備を行い、PDOStatementオブジェクトを返す
	 *
	 * @access public
	 * @param string $sql SQLクエリ
	 * @return PDOStatement object
	 */
	public function prepare($sql) {
		return $this->db->prepare($sql);
	}

	/**
	 * SQL ステートメントを実行し、結果セットを PDOStatement オブジェクトとして返す
	 *
	 * @access public
	 * @param string $sql SQLクエリ
	 * @return PDOStatement object
	 */
	public function query($sql) {
		return $this->db->query($sql);
	}

	/**
	 * 最後に挿入された行の ID あるいはシーケンスの値を返す
	 *
	 * @access public
	 * @param string $name オートインクリメントカラム名
	 * @return PDOStatement object
	 */
	public function lastInsertId($name = NULL) {
		return $this->db->lastInsertId($name);
	}

	/**
	 * SQL ステートメントの実行
	 *
	 * @access public
	 * @param string $sql SQLクエリ
	 * @return mixed
	 */
	public function exec($sql) {
		return $this->db->exec($sql);
	}

	/**
	 * トランザクションの開始
	 *
	 * @access public
	 * @return boolean
	 */
	public function beginTransaction() {
		return $this->db->beginTransaction();
	}

	/**
	 * トランザクションのコミット
	 *
	 * @access public
	 * @return boolean
	 */
	public function commit() {
		return $this->db->commit();
	}

	/**
	 * トランザクションの取り消し
	 *
	 * @access public
	 * @return boolean
	 */
	public function rollBack() {
		return $this->db->rollBack();
	}

	/**
	 * インスタンスの作成 アクセス禁止
	 *
	 * @access public
	 * @return void
	 */
	public static function setInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
	}
} // class Database

/* インスタンスの設定 */
Database::setInstance();
