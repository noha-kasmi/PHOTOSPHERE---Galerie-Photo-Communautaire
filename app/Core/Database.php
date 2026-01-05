<?php
// connexion de database 
final class Database
{
    private static ?PDO $connection = null;
    
    private function __construct() {}
    
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Configuration à remplir
            self::$connection = new PDO(
                "mysql:host=localhost;dbname=photosphere;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$connection;
    }

}
?>