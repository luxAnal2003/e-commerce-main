-- Crear base de datos ecommerce
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Crear tabla de Roles
CREATE TABLE IF NOT EXISTS Rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Insertar roles
INSERT INTO Rol (nombre) VALUES ('EncargadoInventarios'), ('Cliente');

-- Crear tabla de ClienteRegistrado (renombrada de Cliente)
CREATE TABLE IF NOT EXISTS ClienteRegistrado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    edad INT,
    sexo ENUM('M', 'F'),
    fecha_nacimiento DATE,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    correo_electronico VARCHAR(100) UNIQUE NOT NULL,
    ubicacion VARCHAR(255) NOT NULL, -- Columna adicional para ubicación
    id_rol INT DEFAULT 2,
    FOREIGN KEY (id_rol) REFERENCES Rol(id)
);

CREATE TABLE IF NOT EXISTS DatosEmpresa (
    nombre_empresa VARCHAR(100),
    correo_electronico VARCHAR(100),
    contrasena VARCHAR(255)
);

-- Insertar datos en la tabla DatosEmpresa
INSERT INTO DatosEmpresa (nombre_empresa, correo_electronico, contrasena) 
VALUES ('Envíos Express Guayaquil', 'enviosexpressguayaquil@gmail.com', '@pUpVSuLye9#UsgBuCcdTW2S3fFyb2');

-- Crear tabla de EncargadoInventarios
CREATE TABLE IF NOT EXISTS EncargadoInventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo_personal VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    id_rol INT DEFAULT 1,
    FOREIGN KEY (id_rol) REFERENCES Rol(id)
);

-- Crear tabla de ClienteNoRegistrado
CREATE TABLE IF NOT EXISTS ClienteNoRegistrado (
    id INT PRIMARY KEY CHECK (id = 1), -- Solo puede haber un cliente no registrado
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    ubicacion VARCHAR(255) NOT NULL -- Lugar donde quiere recibir el producto
);

-- Crear tabla de Datos Bancarios
CREATE TABLE IF NOT EXISTS DatosBancarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT DEFAULT NULL,
    id_cliente_no_registrado INT DEFAULT NULL,
    numero_tarjeta VARCHAR(16) NOT NULL,
    fecha_expiracion DATE NOT NULL,
    cvv VARCHAR(3) NOT NULL,
    saldo DECIMAL(10, 2) NOT NULL DEFAULT 1000000.00, -- Saldo predeterminado de un millón de dólares
    FOREIGN KEY (id_cliente) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente_no_registrado) REFERENCES ClienteNoRegistrado(id) ON DELETE CASCADE
);

-- Crear tabla de Productos
CREATE TABLE IF NOT EXISTS Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT, -- Cambiar a TEXT
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    imagen_url TEXT -- Cambiar a TEXT si se espera que las URLs sean largas
);

-- Crear tabla de Categorias
CREATE TABLE IF NOT EXISTS Categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear tabla de ProductoCategoria
CREATE TABLE IF NOT EXISTS ProductoCategoria (
    id_producto INT,
    id_categoria INT,
    PRIMARY KEY (id_producto, id_categoria),
    FOREIGN KEY (id_producto) REFERENCES Productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id) ON DELETE CASCADE,
    INDEX idx_producto_categoria (id_categoria, id_producto) -- Índice combinado para búsquedas rápidas
);

-- Crear tabla de Carrito de Compra
CREATE TABLE IF NOT EXISTS CarritoCompra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT DEFAULT NULL,
    id_cliente_no_registrado INT DEFAULT 1,
    id_producto INT,
    cantidad INT NOT NULL,
    total DECIMAL(10, 2),
    FOREIGN KEY (id_cliente) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente_no_registrado) REFERENCES ClienteNoRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    INDEX idx_carrito_id_cliente (id_cliente),
    INDEX idx_carrito_id_cliente_no_registrado (id_cliente_no_registrado),
    INDEX idx_carrito_id_producto (id_producto)
);

DELIMITER //

-- Trigger para inserciones
CREATE TRIGGER before_insert_CarritoCompra
BEFORE INSERT ON CarritoCompra
FOR EACH ROW
BEGIN
    DECLARE producto_precio DECIMAL(10, 2);
    SELECT precio INTO producto_precio FROM Productos WHERE id = NEW.id_producto;
    SET NEW.total = NEW.cantidad * producto_precio;
END;
//

-- Trigger para actualizaciones
CREATE TRIGGER before_update_CarritoCompra
BEFORE UPDATE ON CarritoCompra
FOR EACH ROW
BEGIN
    DECLARE producto_precio DECIMAL(10, 2);
    SELECT precio INTO producto_precio FROM Productos WHERE id = NEW.id_producto;
    SET NEW.total = NEW.cantidad * producto_precio;
END;
//

DELIMITER ;

-- Crear tabla de Compras
CREATE TABLE IF NOT EXISTS Compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT DEFAULT NULL,
    id_cliente_no_registrado INT DEFAULT 1,
    id_producto INT,
    cantidad INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente_no_registrado) REFERENCES ClienteNoRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    CHECK (total > 0),
    INDEX idx_compras_id_cliente (id_cliente),
    INDEX idx_compras_id_cliente_no_registrado (id_cliente_no_registrado),
    INDEX idx_compras_id_producto (id_producto)
);

-- Crear tabla de Mensajes en el Foro
CREATE TABLE IF NOT EXISTS MensajesForo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_usuario INT DEFAULT NULL,
    id_encargado INT DEFAULT NULL,
    id_respuesta_a INT DEFAULT NULL,
    nombre_usuario VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL, -- Cambiar a TEXT
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado BOOLEAN DEFAULT FALSE, -- Nuevo campo para estado del mensaje (false: pendiente, true: respondido)
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    FOREIGN KEY (id_usuario) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_encargado) REFERENCES EncargadoInventarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_respuesta_a) REFERENCES MensajesForo(id) ON DELETE CASCADE,
    INDEX idx_mensajesforo_id_producto (id_producto),
    INDEX idx_mensajesforo_id_usuario (id_usuario),
    INDEX idx_mensajesforo_id_encargado (id_encargado),
    INDEX idx_mensajesforo_id_respuesta_a (id_respuesta_a)
);

-- Procedimiento almacenado para calcular la fecha de entrega
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS calcularFechaEntrega(IN new_id_compra INT)
BEGIN
    DECLARE fecha_compra DATETIME;
    DECLARE hora_entrega TIME;
    DECLARE fecha_entrega DATETIME;

    -- Obtener la fecha de compra
    SELECT fecha_compra INTO fecha_compra FROM Compras WHERE id = new_id_compra;

    -- Calcular la fecha y hora de entrega
    SET hora_entrega = CASE
        WHEN HOUR(fecha_compra) >= 17 THEN '08:00:00'
        WHEN HOUR(fecha_compra) < 8 THEN '08:00:00'
        ELSE TIME_FORMAT(SEC_TO_TIME(RAND() * 25200), '%H:%i:%s')
    END;

    SET fecha_entrega = DATE_ADD(DATE(fecha_compra), INTERVAL 1 DAY);

    -- Insertar la fecha de entrega calculada en la tabla de Compras
    UPDATE Compras SET fecha_entrega = DATE_ADD(fecha_entrega, INTERVAL TIME_TO_SEC(hora_entrega) SECOND) WHERE id = new_id_compra;
END //
DELIMITER ;

-- Procedimiento almacenado para realizar la compra y deducir saldo
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS realizarCompra(
    IN cliente_id INT,
    IN cliente_no_registrado_id INT,
    IN producto_id INT,
    IN cantidad INT,
    INOUT saldo_suficiente BOOLEAN
)
BEGIN
    DECLARE saldo_actual DECIMAL(10, 2);
    DECLARE precio_producto DECIMAL(10, 2);
    DECLARE total_compra DECIMAL(10, 2);

    -- Obtener el saldo actual del cliente
    IF cliente_id IS NOT NULL THEN
        SELECT saldo INTO saldo_actual
        FROM DatosBancarios
        WHERE id_cliente = cliente_id;
    ELSE
        SELECT saldo INTO saldo_actual
        FROM DatosBancarios
        WHERE id_cliente_no_registrado = cliente_no_registrado_id;
    END IF;

    -- Obtener el precio del producto
    SELECT precio INTO precio_producto
    FROM Productos
    WHERE id = producto_id;

    -- Calcular el total de la compra
    SET total_compra = cantidad * precio_producto;

    -- Verificar si hay suficiente saldo
    IF saldo_actual >= total_compra THEN
        -- Realizar la compra y actualizar el saldo
        INSERT INTO Compras (id_cliente, id_cliente_no_registrado, id_producto, cantidad, total)
        VALUES (cliente_id, cliente_no_registrado_id, producto_id, cantidad, total_compra);

        -- Actualizar el saldo en la tabla correspondiente
        IF cliente_id IS NOT NULL THEN
            UPDATE DatosBancarios
            SET saldo = saldo - total_compra
            WHERE id_cliente = cliente_id;
        ELSE
            UPDATE DatosBancarios
            SET saldo = saldo - total_compra
            WHERE id_cliente_no_registrado = cliente_no_registrado_id;
        END IF;

        SET saldo_suficiente = TRUE;
    ELSE
        SET saldo_suficiente = FALSE;
    END IF;
END //
DELIMITER ;

-- Crear trigger para ejecutar el procedimiento almacenado después de insertar en Compras
DELIMITER //
CREATE TRIGGER tr_realizar_compra
AFTER INSERT ON Compras
FOR EACH ROW
BEGIN
    DECLARE saldo_suficiente BOOLEAN;
    CALL realizarCompra(NEW.id_cliente, NEW.id_cliente_no_registrado, NEW.id_producto, NEW.cantidad, saldo_suficiente);
    IF NOT saldo_suficiente THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo insuficiente para completar la compra.';
    END IF;
END;
//
DELIMITER ;

-- Procedimiento para actualizar el estado del mensaje
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS actualizarEstadoMensaje(
    IN id_mensaje INT,
    IN id_encargado INT
)
BEGIN
    DECLARE es_cliente INT;
    
    -- Verificar si el mensaje es de un cliente registrado
    SELECT id_usuario INTO es_cliente FROM MensajesForo WHERE id = id_mensaje AND id_usuario IS NOT NULL;

    IF es_cliente IS NOT NULL THEN
        -- Actualizar el estado del mensaje a respondido
        UPDATE MensajesForo SET estado = TRUE WHERE id = id_mensaje;
    END IF;
END //
DELIMITER ;

-- Trigger para responder mensajes del foro
DELIMITER //
CREATE TRIGGER before_insert_MensajesForo
BEFORE INSERT ON MensajesForo
FOR EACH ROW
BEGIN
    -- Si el mensaje es una respuesta de un encargado de inventarios
    IF NEW.id_encargado IS NOT NULL AND NEW.id_respuesta_a IS NOT NULL THEN
        -- Llamar al procedimiento para actualizar el estado del mensaje original
        CALL actualizarEstadoMensaje(NEW.id_respuesta_a, NEW.id_encargado);
    END IF;

    -- Asegurarse de que los mensajes de los encargados no necesiten respuesta
    IF NEW.id_encargado IS NOT NULL THEN
        SET NEW.estado = TRUE;
    END IF;
END;
//
DELIMITER ;
