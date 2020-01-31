<?php


class App {

    const DB_NAME = 'blogbdd';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_HOST = 'localhost';

    private static $database;

    public function getDb(){
        if(self::database === null){
            self::database = new src/model/mysqlDatabase(self::DB_NAME, self::DB_USER, self::DB_PASS, self::DB_HOST,);
        }
        return self::database;
    }

}