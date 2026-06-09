USE museo_logs;

-- Insertar datos en facturacion_resumen
INSERT INTO facturacion_resumen (anio_mes, fecha_emision, id_factura, cliente_id, monto_total, detalles)
VALUES ('2023-10', '2023-10-15 10:30:00', uuid(), uuid(), 1500.50, 'Factura de donación anual');

INSERT INTO facturacion_resumen (anio_mes, fecha_emision, id_factura, cliente_id, monto_total, detalles)
VALUES ('2023-10', '2023-10-20 14:45:00', uuid(), uuid(), 300.00, 'Compra en tienda de regalos');

INSERT INTO facturacion_resumen (anio_mes, fecha_emision, id_factura, cliente_id, monto_total, detalles)
VALUES ('2023-11', '2023-11-05 09:15:00', uuid(), uuid(), 450.00, 'Membresía anual');

-- Insertar datos en bitacora_seguridad
INSERT INTO bitacora_seguridad (fecha_dia, fecha_hora, id_evento, tipo_evento, usuario, ip_origen)
VALUES ('2023-10-15', '2023-10-15 08:00:00', uuid(), 'login_exitoso', 'admin_maria', '192.168.1.10');

INSERT INTO bitacora_seguridad (fecha_dia, fecha_hora, id_evento, tipo_evento, usuario, ip_origen)
VALUES ('2023-10-15', '2023-10-15 08:05:00', uuid(), 'envio_codigo_recuperacion', 'usuario_anonimo', '203.0.113.5');

INSERT INTO bitacora_seguridad (fecha_dia, fecha_hora, id_evento, tipo_evento, usuario, ip_origen)
VALUES ('2023-10-16', '2023-10-16 11:20:00', uuid(), 'cambio_password', 'admin_maria', '192.168.1.10');

-- Insertar datos en historial_obras
-- Usamos un UUID constante para poder consultarlo fácilmente
INSERT INTO historial_obras (obra_id, fecha_cambio, estatus_anterior, estatus_nuevo, usuario_responsable, comentarios)
VALUES (123e4567-e89b-12d3-a456-426614174000, '2023-01-10 10:00:00', 'en_bodega', 'en_restauracion', 'restaurador_juan', 'Inicio de limpieza de lienzo');

INSERT INTO historial_obras (obra_id, fecha_cambio, estatus_anterior, estatus_nuevo, usuario_responsable, comentarios)
VALUES (123e4567-e89b-12d3-a456-426614174000, '2023-03-15 14:00:00', 'en_restauracion', 'en_exhibicion', 'curador_ana', 'Traslado a la sala principal');

INSERT INTO historial_obras (obra_id, fecha_cambio, estatus_anterior, estatus_nuevo, usuario_responsable, comentarios)
VALUES (123e4567-e89b-12d3-a456-426614174000, '2023-10-20 09:30:00', 'en_exhibicion', 'prestamo_externo', 'director_carlos', 'Préstamo temporal a galería internacional');
