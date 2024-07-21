-- Eliminar triggers
DROP TRIGGER IF EXISTS before_insert_CarritoCompra;
DROP TRIGGER IF EXISTS before_update_CarritoCompra;
DROP TRIGGER IF EXISTS before_insert_MensajesForo;

-- Eliminar procedimientos almacenados
DROP PROCEDURE IF EXISTS calcularFechaEntrega;
DROP PROCEDURE IF EXISTS realizarCompra;
DROP PROCEDURE IF EXISTS actualizarEstadoMensaje;

-- Eliminar tablas en orden de dependencias
DROP TABLE IF EXISTS MensajesForo;
DROP TABLE IF EXISTS Compras;
DROP TABLE IF EXISTS CarritoCompra;
DROP TABLE IF EXISTS ProductoCategoria;
DROP TABLE IF EXISTS Categorias;
DROP TABLE IF EXISTS Productos;
DROP TABLE IF EXISTS DatosBancarios;
DROP TABLE IF EXISTS ClienteNoRegistrado;
DROP TABLE IF EXISTS EncargadoInventarios;
DROP TABLE IF EXISTS DatosEmpresa;
DROP TABLE IF EXISTS ClienteRegistrado;
DROP TABLE IF EXISTS Rol;

-- Eliminar base de datos
DROP DATABASE IF EXISTS ecommerce;
