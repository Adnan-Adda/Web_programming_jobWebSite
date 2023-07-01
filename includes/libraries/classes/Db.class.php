<?php

/**
 * Connect/disconnect to db, executes query, fetch[one or multiple rows]
 */
class Db
{
    private static $connection;
    private static $host;
    private static $db_name;
    private static $password;
    private static $user;
    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    );

    /**
     * Set PDO object to null
     * @return void
     */
    public static function close_connection(){
        self::$connection = null;
    }

    /**
     * Initial database connection. this dose not connects to db
     * @param $host hostname only
     * @param $db_name
     * @param $user
     * @param $password
     * @return void
     */
    public static function initial($host, $db_name, $user, $password){
        self::$host=$host;
        self::$db_name=$db_name;
        self::$user=$user;
        self::$password=$password;
    }

    /**
     * Connect to db. initial must be called first.
     * @return void
     */
    public static function connect(){
        if (!isset(self::$connection)) {
            try{
                self::$connection = new PDO("mysql:host=".self::$host.";dbname=".self::$db_name,
                    self::$user,
                    self::$password,
                    self::$settings);
            }catch(PDOException $ex){
                echo $ex->getMessage();
            }
        }
    }

    /**
     * Prepare and fetch one row from db using fetch(PDO::FETCH_ASSOC)
     * @param $query
     * @param $params
     * @return false on failure otherwise the fetched data
     */
    public static function fetch_one($query, $params = array()){
        try{
            $result = self::$connection->prepare($query);
            $result->execute($params);
            return $result->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $ex){
            echo $ex->getMessage();
        }
        return false;
    }

    /**
     * Prepare and fetch all rows from db using fetch(PDO::FETCH_ASSOC)
     * @param $query
     * @param $params
     * @return false on failure otherwise the fetched data
     */
    public static function fetch_all($query, $params = array()){
        try{
            $result = self::$connection->prepare($query);
            $result->execute($params);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $ex){
            echo $ex->getMessage();
        }
        return false;
    }

    /**
     * Prepare and execute a query
     * @param $query
     * @param $params
     * @return int number of affected rows or false on failure
     */
    public static function execute($query, $params = array()){
        try{
            $stmt = self::$connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        }catch (PDOException $ex){
            echo $ex->getMessage();
        }
        return false;
    }

}
