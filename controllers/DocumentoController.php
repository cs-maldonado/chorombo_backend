<?php

require_once __DIR__ . "/../models/Documento.php";
require_once __DIR__ . "/../validators/DocumentoValidator.php";
require_once __DIR__ . "/../services/ArchivoService.php";

class DocumentoController
{
    private Documento $modelo;
    private ArchivoService $archivos;

    public function __construct(PDO $conexion)
    {
        $this->modelo = new Documento($conexion);
        $this->archivos = new ArchivoService();
    }

    public function index(): void
    {
        try {

            $documentos = $this->modelo->obtenerTodos();

            http_response_code(200);

            echo json_encode([
                "status" => "ok",
                "datos" => $documentos
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al consultar los documentos"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function show(int $id): void
    {
        try {

            $documento = $this->modelo->obtenerPorId($id);

            if (!$documento) {

                http_response_code(404);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Documento no encontrado"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            http_response_code(200);

            echo json_encode([
                "status" => "ok",
                "datos" => $documento
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al consultar el documento"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function store(): void
    {
    try {

        $contenido = file_get_contents("php://input");
        $datos = json_decode($contenido, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            http_response_code(400);

            echo json_encode([
                "status" => "error",
                "mensaje" => "JSON inválido"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        if (!is_array($datos)) {

            http_response_code(400);

            echo json_encode([
                "status" => "error",
                "mensaje" => "El cuerpo de la petición debe ser un objeto JSON"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $errores = DocumentoValidator::validar($datos);

        if (!empty($errores)) {

            http_response_code(422);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Los datos enviados no son válidos",
                "errores" => $errores
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $tipoDocumentoId = (int) $datos["tipo_documento_id"];

        if (!$this->modelo->tipoDocumentoExiste($tipoDocumentoId)) {

            http_response_code(422);

            echo json_encode([
                "status" => "error",
                "mensaje" => "El tipo de documento indicado no existe"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        if (!$this->archivos->existe($datos["archivo"])) {

        http_response_code(422);

        echo json_encode([
            "status" => "error",
            "mensaje" => "El archivo indicado no existe o su ruta no es válida"
        ], JSON_UNESCAPED_UNICODE);

        return;
    }

        $datos["titulo"] = trim($datos["titulo"]);
        $datos["descripcion"] = trim($datos["descripcion"]);
        $datos["archivo"] = trim($datos["archivo"]);
        $datos["tipo_documento_id"] = $tipoDocumentoId;

        $id = $this->modelo->crear($datos);

        $documento = $this->modelo->obtenerPorId($id);

        http_response_code(201);

        echo json_encode([
            "status" => "ok",
            "mensaje" => "Documento creado correctamente",
            "datos" => $documento
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al crear el documento"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function update(int $id): void
    {
        try {

            $documentoExistente = $this->modelo->obtenerPorId($id);

            if (!$documentoExistente) {

                http_response_code(404);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Documento no encontrado"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            $contenido = file_get_contents("php://input");
            $datos = json_decode($contenido, true);

            if (json_last_error() !== JSON_ERROR_NONE) {

                http_response_code(400);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "JSON inválido"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            if (!is_array($datos)) {

                http_response_code(400);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "El cuerpo de la petición debe ser un objeto JSON"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            $errores = DocumentoValidator::validar($datos);

            if (!empty($errores)) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Los datos enviados no son válidos",
                    "errores" => $errores
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            $tipoDocumentoId = (int) $datos["tipo_documento_id"];

            if (!$this->modelo->tipoDocumentoExiste($tipoDocumentoId)) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "El tipo de documento indicado no existe"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            if (!$this->archivos->existe($datos["archivo"])) {

                http_response_code(422);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "El archivo indicado no existe o su ruta no es válida"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            $datos["titulo"] = trim($datos["titulo"]);
            $datos["descripcion"] = trim($datos["descripcion"]);
            $datos["archivo"] = trim($datos["archivo"]);
            $datos["tipo_documento_id"] = $tipoDocumentoId;

            $archivoAnterior = $documentoExistente["archivo"];

            $this->modelo->actualizar(
                $id,
                $datos
            );

            if ($archivoAnterior !== $datos["archivo"]) {

                $archivoAnteriorEliminado =
                    $this->archivos->eliminar($archivoAnterior);

                if (!$archivoAnteriorEliminado) {

                    error_log(
                        "No fue posible eliminar el archivo anterior: "
                        . $archivoAnterior
                    );
                }
            }

            $documentoActualizado =
                $this->modelo->obtenerPorId($id);

            http_response_code(200);

            echo json_encode([
                "status" => "ok",
                "mensaje" => "Documento actualizado correctamente",
                "datos" => $documentoActualizado
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al actualizar el documento"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function destroy(int $id): void
    {
        try {

            $documento = $this->modelo->obtenerPorId($id);

            if (!$documento) {

                http_response_code(404);

                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Documento no encontrado"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            $this->modelo->eliminar($id);


            $archivoEliminado =
                $this->archivos->eliminar(
                    $documento["archivo"]
                );


            http_response_code(200);


            if (!$archivoEliminado) {

                error_log(
                    "No fue posible eliminar el archivo asociado: "
                    . $documento["archivo"]
                );

                echo json_encode([
                    "status" => "ok",
                    "mensaje" => "Documento eliminado correctamente",
                    "advertencia" => "No fue posible eliminar el archivo físico asociado"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }


            echo json_encode([
                "status" => "ok",
                "mensaje" => "Documento y archivo asociados eliminados correctamente"
            ], JSON_UNESCAPED_UNICODE);


        } catch (PDOException $e) {

            error_log($e->getMessage());

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "mensaje" => "Error al eliminar el documento"
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}