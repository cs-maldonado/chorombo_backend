<?php

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/TipoDocumentoController.php";
require_once __DIR__ . "/../../controllers/DocumentoController.php";
require_once __DIR__ . "/../../controllers/ArchivoController.php";

$database = new Database();
$conexion = $database->conectar();

$metodo = $_SERVER["REQUEST_METHOD"];

$uri = parse_url(
    $_SERVER["REQUEST_URI"],
    PHP_URL_PATH
);

$base = rtrim(
    dirname($_SERVER["SCRIPT_NAME"]),
    "/"
);

$ruta = trim(
    substr($uri, strlen($base)),
    "/"
);


if ($metodo === "GET" && $ruta === "tipos-documento") {

    $controller = new TipoDocumentoController($conexion);
    $controller->index();

    exit;
}

if ($metodo === "GET" && $ruta === "documentos") {

    $controller = new DocumentoController($conexion);
    $controller->index();

    exit;
}

if (
    $metodo === "GET"
    && preg_match("#^documentos/(\d+)$#", $ruta, $coincidencias)
) {

    $id = (int) $coincidencias[1];

    $controller = new DocumentoController($conexion);
    $controller->show($id);

    exit;
}

if ($metodo === "POST" && $ruta === "documentos") {

    $controller = new DocumentoController($conexion);
    $controller->store();

    exit;
}

if ($metodo === "POST" && $ruta === "archivos") {

    $controller = new ArchivoController();
    $controller->store();

    exit;
}

if (
    $metodo === "PUT"
    && preg_match("#^documentos/(\d+)$#", $ruta, $coincidencias)
) {

    $id = (int) $coincidencias[1];

    $controller = new DocumentoController($conexion);
    $controller->update($id);

    exit;
}

if (
    $metodo === "DELETE"
    && preg_match("#^documentos/(\d+)$#", $ruta, $coincidencias)
) {

    $id = (int) $coincidencias[1];

    $controller = new DocumentoController($conexion);
    $controller->destroy($id);

    exit;
}


http_response_code(404);

echo json_encode([
    "status" => "error",
    "mensaje" => "Ruta no encontrada"
], JSON_UNESCAPED_UNICODE);