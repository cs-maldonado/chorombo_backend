<?php

require_once __DIR__ . "/../models/TipoDocumento.php";

class TipoDocumentoController
{
    private TipoDocumento $modelo;

    public function __construct(PDO $conexion)
    {
        $this->modelo = new TipoDocumento($conexion);
    }

    public function index(): void
    {
        try {
            $tipos = $this->modelo->obtenerTodos();

            http_response_code(200);

            echo json_encode([
                "status" => "ok",
                "datos" => $tipos
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al consultar los tipos de documento"
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}