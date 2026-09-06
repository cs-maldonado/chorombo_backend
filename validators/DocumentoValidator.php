<?php

class DocumentoValidator
{
    public static function validar(array $datos): array
    {
        $errores = [];

        $camposObligatorios = [
            "titulo",
            "tipo_documento_id",
            "fecha",
            "descripcion",
            "archivo"
        ];

        foreach ($camposObligatorios as $campo) {

            if (
                !isset($datos[$campo]) ||
                (is_string($datos[$campo]) && trim($datos[$campo]) === "")
            ) {
                $errores[] = "El campo '$campo' es obligatorio";
            }
        }

        if (!empty($errores)) {
            return $errores;
        }


        if (!is_string($datos["titulo"])) {
            $errores[] = "El título debe ser texto";
        } elseif (mb_strlen(trim($datos["titulo"])) > 255) {
            $errores[] = "El título no puede superar los 255 caracteres";
        }


        if (
            filter_var(
                $datos["tipo_documento_id"],
                FILTER_VALIDATE_INT
            ) === false
            ||
            (int) $datos["tipo_documento_id"] <= 0
        ) {
            $errores[] = "El tipo_documento_id debe ser un identificador válido";
        }


        if (!is_string($datos["fecha"])) {

            $errores[] = "La fecha debe ser texto con formato YYYY-MM-DD";

        } else {

            $fecha = DateTime::createFromFormat(
                "Y-m-d",
                $datos["fecha"]
            );

            if (
                !$fecha ||
                $fecha->format("Y-m-d") !== $datos["fecha"]
            ) {
                $errores[] = "La fecha debe tener el formato YYYY-MM-DD";
            }
        }


        if (!is_string($datos["descripcion"])) {
            $errores[] = "La descripción debe ser texto";
        }


        if (!is_string($datos["archivo"])) {

            $errores[] = "La referencia del archivo debe ser texto";

        } elseif (mb_strlen(trim($datos["archivo"])) > 255) {

            $errores[] = "La referencia del archivo no puede superar los 255 caracteres";
        }


        return $errores;
    }
}