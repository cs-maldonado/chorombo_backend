<?php

class Documento
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos(): array
    {
        $sql = "
            SELECT
                d.id,
                d.titulo,
                d.tipo_documento_id,
                td.nombre AS tipo_documento,
                d.fecha,
                d.descripcion,
                d.archivo,
                d.created_at,
                d.updated_at
            FROM documentos d
            INNER JOIN tipos_documento td
                ON d.tipo_documento_id = td.id
            ORDER BY d.id DESC
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                d.id,
                d.titulo,
                d.tipo_documento_id,
                td.nombre AS tipo_documento,
                d.fecha,
                d.descripcion,
                d.archivo,
                d.created_at,
                d.updated_at
            FROM documentos d
            INNER JOIN tipos_documento td
                ON d.tipo_documento_id = td.id
            WHERE d.id = :id
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetch();
    }
    public function tipoDocumentoExiste(int $id): bool
{
    $sql = "
        SELECT COUNT(*)
        FROM tipos_documento
        WHERE id = :id
    ";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindValue(
        ":id",
        $id,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}


    public function crear(array $datos): int
    {
        $sql = "
            INSERT INTO documentos (
                titulo,
                tipo_documento_id,
                fecha,
                descripcion,
                archivo
            )
            VALUES (
                :titulo,
                :tipo_documento_id,
                :fecha,
                :descripcion,
                :archivo
            )
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":titulo",
            $datos["titulo"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":tipo_documento_id",
            $datos["tipo_documento_id"],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":fecha",
            $datos["fecha"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":descripcion",
            $datos["descripcion"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":archivo",
            $datos["archivo"],
            PDO::PARAM_STR
        );

        $stmt->execute();

        return (int) $this->conexion->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "
            UPDATE documentos
            SET
                titulo = :titulo,
                tipo_documento_id = :tipo_documento_id,
                fecha = :fecha,
                descripcion = :descripcion,
                archivo = :archivo
            WHERE id = :id
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":titulo",
            $datos["titulo"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":tipo_documento_id",
            $datos["tipo_documento_id"],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":fecha",
            $datos["fecha"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":descripcion",
            $datos["descripcion"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":archivo",
            $datos["archivo"],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function eliminar(int $id): bool
    {
        $sql = "
            DELETE FROM documentos
            WHERE id = :id
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }
}