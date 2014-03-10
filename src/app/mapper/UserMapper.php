<?php
/**
 * User DataMapper
 */
// TODO 永続化機構をBaseDataMapperクラスに実装する
// TODO 複数のDataMapperにまたがるトランザクションを実装できるようにする -> Contextに実装？
// http://www.sitepoint.com/forums/showthread.php?593227-DataMapper-Pattern-and-Transactions
// For the first method, you could handle transactions inside each mapper.
// Each mapper checks if a transaction is already started, and if not, starts one itself.
// If a transaction is already started, then it just goes about its business. 
// DBALのnestedTransactionを利用できるかも
use Doctrine\DBAL\Connection;
use framework\DataMapper;
class UserMapper extends DataMapper{
	const MODEL_CLASS = 'User';

	// PDO/DBALに依存している。(SQL直書きなので)
	// TODO MongoやRedisにも対応できるようにする

	/**
	 * 新しい行を追加する
	 * @param Model $model;
	 * @return int ;
	 **/
	static public function create($model){
		$statement = self::getConnection()->prepare('
			INSERT INTO user_tbl(name, password, email_address, create_dt, update_dt)
			VALUES (:name,:password, :email_address, :create_dt, :update_dt) 
		');
		$dt = self::getDateTime();
		$statement->bindParam("name", $model->name, PDO::PARAM_STR);
		$statement->bindParam("password", $model->password, PDO::PARAM_STR);
		$statement->bindParam("email_address", $model->email_address, PDO::PARAM_STR);
		$statement->bindParam("create_dt", $dt, PDO::PARAM_STR);
		$statement->bindParam("update_dt", $dt, PDO::PARAM_STR);
		$statement->execute();
		$model->id = self::getConnection()->lastInsertId();
		return $model->id;
	}

	/**
	 * 指定した行を更新する
	 * @param Model $model;
	 * @return Model;
	 **/
	static public function update($model)
	{
		//TODO 指定した行だけUPDATEするようにする
		//TODO 指定したプロパティだけUPDATEするようにする
		$statement = self::getConnection()->prepare('
			UPDATE user_tbl 
			SET name = :name
			,	password = :password
			WHERE id = :id
		');
		$statement->bindValue("name", $model->name, PDO::PARAM_STR);
		$statement->bindValue("password" ,$model->password, PDO::PARAM_STR);
		$statement->bindValue("id" ,$model->id, PDO::PARAM_INT);
		$statement->execute();
	}

	/**
	 * 指定したIDの行をDBから削除する
	 * @param int $id;
	 * @return Model;
	 **/
	static public function destroy($ids){
		$sql = '
			DELETE FROM user_tbl 
			WHERE id in (?)
		';
		$statement = self::getConnection()->executeQuery($sql, [$ids], [Connection::PARAM_INT_ARRAY]); //Connectionへの参照が残ってる -> DataMapperに移したい
		return $statement->execute();
	}

	/**
	 * 指定したIDの行を返す
	 * @param int $id;
	 * @return Model;
	 **/
	static public function find($id){
		$statement = self::getConnection()->prepare('
			SELECT *
			FROM user_tbl
			WHERE id = :id
		');
		$statement->bindValue("id", $id, PDO::PARAM_INT);
		$statement->execute();

		return self::decorate($statement)->fetch();
	}

	/**
	 * 指定したメールアドレスの行を返す
	 * @param int $id;
	 * @return Model;
	 **/
	static public function findByEmailAddress($email_address){
		$statement = self::getConnection()->prepare('
			SELECT *
			FROM user_tbl
			WHERE email_address = :email_address
		');
		$statement->bindValue("email_address", $email_address, PDO::PARAM_INT);
		$statement->execute();

		return self::decorate($statement)->fetch();
	}
	/**
	 * すべての行を返す
	 * @param int $id;
	 * @return Model $model;
	 **/
	static public function all(){
		$statement = self::getConnection()->query('
			SELECT *
			FROM user_tbl;
		');
		return self::decorate($statement)->fetchAll();
	}
}
