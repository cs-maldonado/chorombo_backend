<?php

class ArchivoService
{
    private string $directorioBase;

    public function __construct()
    {
        $this->directorioBase =
            dirname(__DIR__) . "/uploads/documentos/";
    }


    public function rutaValida(string $rutaRelativa): bool
    {
        $prefijo = "uploads/documentos/";

        if (!str_starts_with($rutaRelativa, $prefijo)) {
            return false;
        }

        $nombreArchivo = basename($rutaRelativa);

        return $rutaRelativa === $prefijo . $nombreArchivo;
    }


    public function existe(string $rutaRelativa): bool
    {
        if (!$this->rutaValida($rutaRelativa)) {
            return false;
        }

        $rutaCompleta =
            dirname(__DIR__) . "/" . $rutaRelativa;

        return is_file($rutaCompleta);
    }


    public function eliminar(string $rutaRelativa): bool
    {
        if (!$this->rutaValida($rutaRelativa)) {
            return false;
        }

        $rutaCompleta =
            dirname(__DIR__) . "/" . $rutaRelativa;

        if (!is_file($rutaCompleta)) {
            return true;
        }

        return unlink($rutaCompleta);
    }
}