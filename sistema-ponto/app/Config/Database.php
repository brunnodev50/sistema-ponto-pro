<?php
class Database {
    private static $pdo;

    public static function getConnection() {
        if (!self::$pdo) {
            try {
                // Ajuste usuário e senha conforme seu ambiente
                self::$pdo = new PDO("mysql:host=localhost;dbname=sistema_ponto", "root", "");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Erro de Conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}