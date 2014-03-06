<?php
namespace framework;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use PDO;
use DateTime;
abstract class DataMapper
{
	// PDO/DBALに依存している。
	// TODO MongoやRedisにも対応できるようにする
	static protected $connection;
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

	static protected function decorate($statement)
	{
		$statement->setFetchMode(PDO::FETCH_CLASS, static::MODEL_CLASS);
		return $statement;
	}
	static protected function getDateTime(){
		$dt= new DateTime();
		return $dt->format('c');
	}
}
