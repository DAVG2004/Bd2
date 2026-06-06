USE museo_logs;

-- 1. Consultar el resumen de facturación de un mes específico (ej. Octubre 2023)
-- Esta consulta es super rápida porque va directo a la partición '2023-10'
-- y los resultados ya vienen ordenados del más reciente al más antiguo.
SELECT * FROM facturacion_resumen WHERE anio_mes = '2023-10';

-- 2. Consultar la bitácora de seguridad para un día concreto
-- Va directo a la partición de la fecha y retorna los eventos más recientes primero.
SELECT * FROM bitacora_seguridad WHERE fecha_dia = '2023-10-15';

-- Si queremos ver solo los últimos 10 eventos de ese día:
SELECT * FROM bitacora_seguridad WHERE fecha_dia = '2023-10-15' LIMIT 10;

-- 3. Consultar el historial de cambios de una obra de arte
-- Va directo a la partición de la obra y vemos cronológicamente (desde el actual hacia atrás) sus cambios.
SELECT * FROM historial_obras WHERE obra_id = 123e4567-e89b-12d3-a456-426614174000;
