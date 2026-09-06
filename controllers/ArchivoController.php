<?php

class ArchivoController
{
    private string $directorio;

    private int $tamanoMaximo = 10485760;

    private array $tiposPermitidos = [
        "pdf" => "application/pdf",
        "doc" => "application/msword",
        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "xls" => "application/vnd.ms-excel",
        "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    ];

    public function __construct()
    {
        $this->directorio = dirname(__DIR__) . "/uploads/documentos/";
    }

    public function store(): void
    {
        try {

            if (!isset($_FILES["archivo"])) {

                http_response_code(400);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "No se recibió ningún archivo"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            $archivo = $_FILES["archivo"];


            if ($archivo["error"] !== UPLOAD_ERR_OK) {

                http_response_code(400);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Ocurrió un error al subir el archivo"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            if ($archivo["size"] > $this->tamanoMaximo) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "El archivo no puede superar los 10 MB"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            $extension = strtolower(
                pathinfo(
                    $archivo["name"],
                    PATHINFO_EXTENSION
                )
            );


            if (!array_key_exists($extension, $this->tiposPermitidos)) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Tipo de archivo no permitido",
                    "permitidos" => [
                        "pdf",
                        "doc",
                        "docx",
                        "xls",
                        "xlsx"
                    ]
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $mimeReal = $finfo->file(
                $archivo["tmp_name"]
            );


            if ($mimeReal !== $this->tiposPermitidos[$extension]) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "El contenido del archivo no coincide con su extensión"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            if (!is_dir($this->directorio)) {

                if (!mkdir(
                    $this->directorio,
                    0755,
                    true
                )) {

                    throw new Exception(
                        "No fue posible crear el directorio de archivos"
                    );
                }
            }


            $nombreArchivo =
                bin2hex(random_bytes(16))
                . "."
                . $extension;


            $rutaDestino =
                $this->directorio
                . $nombreArchivo;


            if (!move_uploaded_file(
                $archivo["tmp_name"],
                $rutaDestino
            )) {

                throw new Exception(
                    "No fue posible guardar el archivo"
                );
            }


            $rutaRelativa =
                "uploads/documentos/"
                . $nombreArchivo;


            http_response_code(201);

            echo json_encode([
                "status" => "ok",
                "mensaje" => "Archivo subido correctamente",
                "datos" => [
                    "archivo" => $rutaRelativa,
                    "nombre_original" => $archivo["name"],
                    "tipo" => $mimeReal,
                    "tamano" => $archivo["size"]
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


        } catch (Throwable $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error interno al almacenar el archivo"
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}