<?php

namespace App;

class DB{

    private static $pdo;

    public function __construct(){
        if (!self::$pdo) {
            try {
                self::$pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS , [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
                ]);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function insert($query){
        if (self::$pdo->query($query)){
            return self::$pdo->lastInsertId();
        }else{
            return false;
        }
    }

    public function select($query){
        $this->query = $query;
        return $this;
    }

    public function update($query){
        return self::$pdo->exec($query)->rowCount();
    }

    public function delete($query){
        return self::$pdo->exec($query)->rowCount();
    }

    public function query($sql,$params){
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId(){
        return self::$pdo->lastInsertId();
    }

    #get first line of query
    public function first(){
        $this->query->setFetchMode(\PDO::FETCH_OBJ);
        return $this->query->fetch();
    }

}