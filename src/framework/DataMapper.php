<?php
namespace framework;
/**
 * DataMapper基底クラス
 * @link http://capsctrl.que.jp/kdmsnr/wiki/PofEAA/?DataMapper
 **/
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use PDO;
use DateTime;
abstract class DataMapper
{
	// TODO MongoやRedisにも対応できるようにする
	// PDO/DBALに依存している。
	
	// @type Connection データソースコネクション
	static protected $connection;

	/*
	 * コネクションを取得
	 */
	static public function getConnection($dsn = null,$config = null){
		if(!isset($dsn)){
			$dsn = [
				"driver"=>"pdo_sqlite",
				"path"=>SYSPATH . "/data/app.db"
			];
		}
		if(!isset($config)){
			$config = new Configuration([ PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]);
		}
		if(!isset(self::$connection)){
			self::$connection = DriverManager::getConnection($dsn, $config);
		}
		return self::$connection;
	}

	/*
	 * Statementが継承したMapperのオブジェクトモデルを返すようにする
	 * @param Statement 
	 * @return Statement
	static protected function decorate($statement)
	{
		$statement->setFetchMode(PDO::FETCH_CLASS, static::MODEL_CLASS);
		return $statement;
	}
	/*
	 * 時刻を取得する
	 * @return DateTime
	 */
	static protected function getDateTime(){
		$dt= new DateTime();
		return $dt->format('c');
	}
}
