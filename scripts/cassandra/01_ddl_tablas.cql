-- Creación del Keyspace
CREATE KEYSPACE IF NOT EXISTS museo_logs
WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1};

USE museo_logs;

-- 1. Familia de Columnas: Resúmenes de facturación masiva
-- Partition Key: anio_mes (agrupa las facturas por mes)
-- Clustering Column: fecha_emision DESC (las más recientes primero)
CREATE TABLE IF NOT EXISTS facturacion_resumen (
    anio_mes text,
    fecha_emision timestamp,
    id_factura uuid,
    cliente_id uuid,
    monto_total decimal,
    detalles text,
    PRIMARY KEY ((anio_mes), fecha_emision, id_factura)
) WITH CLUSTERING ORDER BY (fecha_emision DESC);

-- 2. Familia de Columnas: Bitácora inmutable de seguridad
-- Partition Key: fecha_dia (agrupa los logs de seguridad por día)
-- Clustering Column: fecha_hora DESC (eventos más recientes del día primero)
CREATE TABLE IF NOT EXISTS bitacora_seguridad (
    fecha_dia date,
    fecha_hora timestamp,
    id_evento uuid,
    tipo_evento text,
    usuario text,
    ip_origen text,
    PRIMARY KEY ((fecha_dia), fecha_hora, id_evento)
) WITH CLUSTERING ORDER BY (fecha_hora DESC);

-- 3. Familia de Columnas: Historial de cambios de estatus de obras
-- Partition Key: obra_id (agrupa todo el historial de una obra)
-- Clustering Column: fecha_cambio DESC (el estatus más reciente primero)
CREATE TABLE IF NOT EXISTS historial_obras (
    obra_id uuid,
    fecha_cambio timestamp,
    estatus_anterior text,
    estatus_nuevo text,
    usuario_responsable text,
    comentarios text,
    PRIMARY KEY ((obra_id), fecha_cambio)
) WITH CLUSTERING ORDER BY (fecha_cambio DESC);
