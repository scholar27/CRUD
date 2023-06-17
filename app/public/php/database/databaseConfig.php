<?php

namespace Zsamme\DatabaseConfig;

use PDO;
use PDOException;

class databaseConfig
{
    private const DB_SERVER = 'mysql';
    private const DB_USERNAME = 'root';
    private const DB_PASSWORD = 'secret';
    private const DB_NAME = 'tutorial';
    private PDO $pdo;
    
    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
    
    public function __construct()
    {
        $this->connectToDatabase();
    }
    
    public function connectToDatabase()
    {
        /* Attempt to connect to MySQL database */
        try {
            $this->pdo = new PDO('mysql:host=' . self::DB_SERVER . ';dbname=' . self::DB_NAME, self::DB_USERNAME, self::DB_PASSWORD);
            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: Could not connect. ' . $e->getMessage());
        }
    }
    
}