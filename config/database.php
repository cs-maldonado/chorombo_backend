<?php

class Database
{
    private string $host = "localhost";
    private string $dbName = "chorombo_documental";
    private string $username = "root";
    private string $password = "";

    private ?PDO $conexion = null;

    public function conectar(): PDO
    {
        try {
            $this->conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $this->conexion;

        } catch (PDOException $e) {
            http_response_code(500);

            echo json_encode([
                "error" => "Error de conexión a la base de datos",
                "detalle" => $e->getMessage()
            ]);

            exit;
        }
    }
}