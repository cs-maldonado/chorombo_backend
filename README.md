## URL base

La URL base de la API es:

`http://localhost/chorombo_backend/api/v1`

## Endpoints

### Tipos de documento

GET `/tipos-documento`

Retorna los tipos documentales disponibles.

### Documentos

GET `/documentos`

Retorna todos los documentos registrados.

GET `/documentos/{id}`

Retorna un documento específico.

POST `/documentos`

Crea un nuevo documento.

PUT `/documentos/{id}`

Actualiza completamente un documento existente.

DELETE `/documentos/{id}`

Elimina un documento y su archivo físico asociado.

### Archivos

POST `/archivos`

Permite subir un archivo asociado a un documento.

El archivo debe enviarse utilizando:

`multipart/form-data`

La clave utilizada es:

`archivo`

## Tipos documentales

El sistema considera los siguientes tipos:

- Memo.
- Oficio.
- Citación de apoderado.
- Acuerdo de apoderados.
- Reunión comunal.
- Permiso administrativo.

## Archivos permitidos

Se permiten los siguientes formatos:

- PDF
- DOC
- DOCX
- XLS
- XLSX

El tamaño máximo permitido por archivo es de 10 MB.

Los archivos son almacenados en:

`uploads/documentos/`

El sistema genera automáticamente un nombre único para evitar sobrescrituras y problemas con nombres de archivos.

## Flujo para crear un documento con archivo

Primero se debe subir el archivo mediante:

POST `/archivos`

La API retorna una referencia similar a:

`uploads/documentos/abc123.pdf`

Luego esa referencia debe enviarse en el campo `archivo` del:

POST `/documentos`

Ejemplo:

{
"titulo": "Memo reunión de profesores",  
"tipo_documento_id": 1,  
"fecha": "2026-09-02",  
"descripcion": "Documento correspondiente a una reunión de profesores.",  
"archivo": "uploads/documentos/abc123.pdf"  
}
