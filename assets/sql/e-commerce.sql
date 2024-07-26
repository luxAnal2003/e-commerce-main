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
    ubicacion VARCHAR(255) NOT NULL,
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
    id INT PRIMARY KEY CHECK (id = 1),
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    ubicacion VARCHAR(255) NOT NULL
);

-- Crear tabla de Datos Bancarios
CREATE TABLE IF NOT EXISTS DatosBancarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT DEFAULT NULL,
    id_cliente_no_registrado INT DEFAULT NULL,
    numero_tarjeta VARCHAR(16) NOT NULL,
    fecha_expiracion DATE NOT NULL,
    cvv VARCHAR(3) NOT NULL,
    saldo DECIMAL(10, 2) NOT NULL DEFAULT 100000.00, -- Saldo predeterminado de 100,000 dólares
    FOREIGN KEY (id_cliente) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente_no_registrado) REFERENCES ClienteNoRegistrado(id) ON DELETE CASCADE
);

-- Crear tabla de Productos
CREATE TABLE IF NOT EXISTS Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    imagen_url TEXT
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
    INDEX idx_producto_categoria (id_categoria, id_producto)
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
    INDEX idx_carrito_id_producto (id_producto),
    UNIQUE KEY unique_cart (id_cliente, id_producto),
    UNIQUE KEY unique_cart_guest (id_cliente_no_registrado, id_producto)
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
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    FOREIGN KEY (id_usuario) REFERENCES ClienteRegistrado(id) ON DELETE CASCADE,
    FOREIGN KEY (id_encargado) REFERENCES EncargadoInventarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_respuesta_a) REFERENCES MensajesForo(id) ON DELETE CASCADE,
    INDEX idx_mensajesforo_id_producto (id_producto),
    INDEX idx_mensajesforo_id_usuario (id_usuario),
    INDEX idx_mensajesforo_id_encargado (id_encargado),
    INDEX idx_mensajesforo_id_respuesta_a (id_respuesta_a)
);

CREATE TABLE IF NOT EXISTS EstadoMensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mensaje INT,
    estado BOOLEAN DEFAULT FALSE,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

CREATE TRIGGER after_insert_MensajesForo
AFTER INSERT ON MensajesForo
FOR EACH ROW
BEGIN
    -- Si el mensaje es una respuesta de un encargado de inventarios
    IF NEW.id_encargado IS NOT NULL AND NEW.id_respuesta_a IS NOT NULL THEN
        -- Insertar en la tabla de estado
        INSERT INTO EstadoMensajes (id_mensaje, estado) 
        VALUES (NEW.id_respuesta_a, TRUE);
    END IF;
END //

DELIMITER ;


-- Insertar datos de ClienteRegistrado
INSERT INTO ClienteRegistrado (nombre, apellido, edad, sexo, fecha_nacimiento, documento_identidad, contrasena, correo_electronico, ubicacion)
VALUES
('Juan', 'Perez',30, 'M', '1993-01-15', '1234567890', 'password1', 'juan.perez@example.com', 'Guayaquil'),
('Maria', 'Gomez',25, 'F', '1998-04-22', '0987654321', 'password2', 'maria.gomez@example.com', 'Quito'),
('Luis', 'Martinez', 40, 'M', '1983-08-12', '1029384756', 'password3', 'luis.martinez@example.com', 'Cuenca'),
('Ana', 'Lopez', 28, 'F', '1995-02-18', '5647382910', 'password4', 'ana.lopez@example.com', 'Manta');

-- Insertar datos de EncargadoInventarios
INSERT INTO EncargadoInventarios (nombre, apellido, telefono, correo_personal, contrasena)
VALUES
('Carlos', 'Ruiz', '0987654321', 'carlos.ruiz@example.com', 'password3'),
('Lucia', 'Fernandez', '1234567890', 'lucia.fernandez@example.com', 'password4');

-- Insertar datos de ClienteNoRegistrado
INSERT INTO ClienteNoRegistrado (id, nombre, cedula, ubicacion)
VALUES
(1, 'Cliente No Registrado', '1234567890', 'Ambato');

-- Insertar datos bancarios
INSERT INTO DatosBancarios (id_cliente, numero_tarjeta, fecha_expiracion, cvv, saldo)
VALUES
(1, '4111111111111111', '2025-12-31', '123', 100000.00),
(2, '4222222222222222', '2026-11-30', '456', 100000.00),
(3, '4333333333333333', '2024-10-15', '789', 100000.00),
(4, '4444444444444444', '2023-08-20', '012', 100000.00);

-- Insertar datos de Categorias
INSERT INTO Categorias (nombre)
VALUES
('Ofertas'),
('Audio y Video'),
('Televisores'),
('Tendencia'),
('Hogar'),
('Computadoras'),
('Salud'),
('Oficina'),
('Ocio'),
('Celulares'),
('Consolas'),
('Liquidación'),
('Tablets'),
('Smartwatchs y Smartbands'),
('Lo más popular'),
('Nuevos productos'),
('Lo más vendido');

-- Insertar datos de Productos
INSERT INTO Productos (nombre, descripcion, precio, stock, imagen_url)
VALUES
-- Ofertas
('Laptop Dell Inspiron 15', 'Laptop Dell Inspiron 15 con procesador Intel i5, 8GB RAM, 256GB SSD', 700.00, 50, 'imagen_1.jpg'),
('Smartphone Samsung Galaxy S21', 'Smartphone Samsung Galaxy S21 con 128GB de almacenamiento', 800.00, 100, 'imagen_2.jpg'),
('Audífonos Bose QuietComfort 35 II', 'Audífonos inalámbricos Bose QuietComfort 35 II con cancelación de ruido', 300.00, 75, 'imagen_3.jpg'),
('Smart TV LG 55"', 'Smart TV LG de 55 pulgadas 4K UHD', 500.00, 60, 'imagen_4.jpg'),
('Cámara Canon EOS Rebel T7', 'Cámara Canon EOS Rebel T7 con lente 18-55mm', 400.00, 30, 'imagen_5.jpg'),
('Tablet Apple iPad Air', 'Apple iPad Air de 10.9 pulgadas con 256GB', 600.00, 40, 'imagen_6.jpg'),
('Consola PlayStation 5', 'Consola PlayStation 5 con lector de discos', 500.00, 20, 'imagen_7.jpg'),
('Smartwatch Garmin Forerunner 245', 'Smartwatch Garmin Forerunner 245 con GPS', 250.00, 70, 'imagen_8.jpg'),
('Impresora HP DeskJet 3755', 'Impresora multifuncional HP DeskJet 3755', 90.00, 45, 'imagen_9.jpg'),
('Disco Duro Externo Seagate 2TB', 'Disco duro externo Seagate de 2TB', 70.00, 80, 'imagen_10.jpg'),

-- Audio y Video
('Barra de Sonido Sony HT-S350', 'Barra de sonido Sony HT-S350 con subwoofer inalámbrico', 200.00, 50, 'imagen_11.jpg'),
('Reproductor Blu-ray Sony BDP-S6700', 'Reproductor Blu-ray Sony BDP-S6700 con Wi-Fi y 4K', 120.00, 30, 'imagen_12.jpg'),
('Parlantes JBL Charge 4', 'Parlantes inalámbricos JBL Charge 4 con batería de larga duración', 150.00, 40, 'imagen_13.jpg'),
('Audífonos Sony WH-1000XM4', 'Audífonos inalámbricos Sony WH-1000XM4 con cancelación de ruido', 300.00, 60, 'imagen_14.jpg'),
('Proyector Epson Home Cinema 2150', 'Proyector Epson Home Cinema 2150 con resolución Full HD', 700.00, 20, 'imagen_15.jpg'),
('Receptor AV Denon AVR-S750H', 'Receptor AV Denon AVR-S750H con soporte para 4K', 500.00, 25, 'imagen_16.jpg'),
('Subwoofer Klipsch R-120SW', 'Subwoofer Klipsch R-120SW de 12 pulgadas', 300.00, 30, 'imagen_17.jpg'),
('Sistema de Teatro en Casa Bose Lifestyle 650', 'Sistema de teatro en casa Bose Lifestyle 650', 3500.00, 10, 'imagen_18.jpg'),
('Tocadiscos Audio-Technica AT-LP120XUSB', 'Tocadiscos Audio-Technica AT-LP120XUSB con salida USB', 250.00, 35, 'imagen_19.jpg'),
('Altavoces Sonos One', 'Altavoces inteligentes Sonos One con control de voz', 200.00, 40, 'imagen_20.jpg'),

-- Televisores
('Smart TV Samsung QLED 65"', 'Smart TV Samsung QLED de 65 pulgadas 4K UHD', 1000.00, 15, 'imagen_21.jpg'),
('Smart TV LG OLED 55"', 'Smart TV LG OLED de 55 pulgadas 4K UHD', 1200.00, 20, 'imagen_22.jpg'),
('Smart TV Sony BRAVIA 75"', 'Smart TV Sony BRAVIA de 75 pulgadas 4K UHD', 1500.00, 10, 'imagen_23.jpg'),
('Smart TV Vizio M-Series Quantum 50"', 'Smart TV Vizio M-Series Quantum de 50 pulgadas', 700.00, 25, 'imagen_24.jpg'),
('Smart TV TCL 6-Series 65"', 'Smart TV TCL 6-Series de 65 pulgadas 4K UHD', 900.00, 18, 'imagen_25.jpg'),
('Smart TV Hisense H8G 55"', 'Smart TV Hisense H8G de 55 pulgadas 4K UHD', 600.00, 22, 'imagen_26.jpg'),
('Smart TV Panasonic 4K 60"', 'Smart TV Panasonic de 60 pulgadas 4K UHD', 800.00, 20, 'imagen_27.jpg'),
('Smart TV Philips Ambilight 70"', 'Smart TV Philips Ambilight de 70 pulgadas 4K UHD', 1300.00, 12, 'imagen_28.jpg'),
('Smart TV Sharp AQUOS 80"', 'Smart TV Sharp AQUOS de 80 pulgadas 4K UHD', 2000.00, 8, 'imagen_29.jpg'),
('Smart TV Toshiba Fire TV 55"', 'Smart TV Toshiba Fire TV de 55 pulgadas 4K UHD', 700.00, 25, 'imagen_30.jpg'),

-- Tendencia
('Auriculares Inalámbricos Apple AirPods Pro', 'Auriculares inalámbricos Apple AirPods Pro con cancelación de ruido', 250.00, 50, 'imagen_31.jpg'),
('Smartwatch Apple Watch Series 6', 'Smartwatch Apple Watch Series 6 con GPS', 400.00, 30, 'imagen_32.jpg'),
('Altavoz Inteligente Amazon Echo Dot (4ª generación)', 'Altavoz inteligente Amazon Echo Dot de cuarta generación', 50.00, 100, 'imagen_33.jpg'),
('Drone DJI Mavic Air 2', 'Drone DJI Mavic Air 2 con cámara 4K', 800.00, 20, 'imagen_34.jpg'),
('Gafas de Realidad Virtual Oculus Quest 2', 'Gafas de realidad virtual Oculus Quest 2', 300.00, 40, 'imagen_35.jpg'),
('Cargador Inalámbrico Belkin Boost Up', 'Cargador inalámbrico Belkin Boost Up para dispositivos Qi', 40.00, 80, 'imagen_36.jpg'),
('Cámara de Seguridad Ring Stick Up Cam', 'Cámara de seguridad Ring Stick Up Cam con batería', 100.00, 60, 'imagen_37.jpg'),
('Termostato Inteligente Google Nest', 'Termostato inteligente Google Nest', 250.00, 30, 'imagen_38.jpg'),
('Laptop Microsoft Surface Pro 7', 'Laptop Microsoft Surface Pro 7 con procesador Intel i5', 900.00, 25, 'imagen_39.jpg'),
('Cámara Deportiva GoPro HERO9', 'Cámara deportiva GoPro HERO9 con estabilización', 400.00, 35, 'imagen_40.jpg'),

-- Hogar
('Robot Aspirador iRobot Roomba 960', 'Robot aspirador iRobot Roomba 960 con Wi-Fi', 500.00, 40, 'imagen_41.jpg'),
('Cafetera Nespresso Vertuo', 'Cafetera Nespresso Vertuo con cápsulas incluidas', 150.00, 50, 'imagen_42.jpg'),
('Microondas Panasonic NN-SN966S', 'Microondas Panasonic NN-SN966S de 2.2 pies cúbicos', 200.00, 30, 'imagen_43.jpg'),
('Refrigerador Samsung Family Hub', 'Refrigerador Samsung Family Hub con pantalla táctil', 2500.00, 10, 'imagen_44.jpg'),
('Lavadora LG TurboWash', 'Lavadora LG TurboWash de carga frontal', 1000.00, 20, 'imagen_45.jpg'),
('Aire Acondicionado Portátil Honeywell', 'Aire acondicionado portátil Honeywell de 14000 BTU', 300.00, 25, 'imagen_46.jpg'),
('Horno Eléctrico Breville Smart Oven', 'Horno eléctrico Breville Smart Oven con convección', 250.00, 35, 'imagen_47.jpg'),
('Purificador de Aire Dyson Pure Cool', 'Purificador de aire Dyson Pure Cool con ventilador', 400.00, 30, 'imagen_48.jpg'),
('Calefactor Lasko Ceramic', 'Calefactor Lasko Ceramic con termostato ajustable', 50.00, 60, 'imagen_49.jpg'),
('Deshumidificador Frigidaire', 'Deshumidificador Frigidaire de 50 pintas', 200.00, 40, 'imagen_50.jpg'),

-- Computadoras
('MacBook Air M1', 'MacBook Air con chip M1 de Apple', 1000.00, 30, 'imagen_51.jpg'),
('Dell XPS 13', 'Laptop Dell XPS 13 con pantalla InfinityEdge', 1100.00, 25, 'imagen_52.jpg'),
('HP Spectre x360', 'Laptop convertible HP Spectre x360 con pantalla táctil', 1200.00, 20, 'imagen_53.jpg'),
('Lenovo ThinkPad X1 Carbon', 'Laptop Lenovo ThinkPad X1 Carbon de 14 pulgadas', 1300.00, 15, 'imagen_54.jpg'),
('ASUS ROG Zephyrus G14', 'Laptop para juegos ASUS ROG Zephyrus G14 con Ryzen 9', 1400.00, 18, 'imagen_55.jpg'),
('Microsoft Surface Laptop 4', 'Laptop Microsoft Surface Laptop 4 con pantalla táctil', 1000.00, 25, 'imagen_56.jpg'),
('Acer Swift 3', 'Laptop Acer Swift 3 con procesador Intel i7', 700.00, 40, 'imagen_57.jpg'),
('Razer Blade 15', 'Laptop para juegos Razer Blade 15 con RTX 3070', 1500.00, 12, 'imagen_58.jpg'),
('Chromebook Google Pixelbook Go', 'Chromebook Google Pixelbook Go con pantalla táctil', 650.00, 30, 'imagen_59.jpg'),
('Alienware m15 R4', 'Laptop para juegos Alienware m15 R4 con RTX 3080', 2000.00, 10, 'imagen_60.jpg'),

-- Salud
('Termómetro Digital Braun', 'Termómetro digital Braun con lectura rápida', 40.00, 50, 'imagen_61.jpg'),
('Oxímetro de Pulso iHealth', 'Oxímetro de pulso iHealth para monitoreo de oxígeno', 30.00, 60, 'imagen_62.jpg'),
('Tensiometro Omron', 'Tensiometro digital Omron para brazo', 70.00, 40, 'imagen_63.jpg'),
('Balanza Inteligente Withings Body+', 'Balanza inteligente Withings Body+ con conexión Wi-Fi', 100.00, 30, 'imagen_64.jpg'),
('Lámpara de Fototerapia Verilux HappyLight', 'Lámpara de fototerapia Verilux HappyLight para SAD', 80.00, 35, 'imagen_65.jpg'),
('Purificador de Agua Brita', 'Purificador de agua Brita con filtro incluido', 30.00, 70, 'imagen_66.jpg'),
('Monitor de Sueño Fitbit Charge 4', 'Monitor de sueño Fitbit Charge 4 con GPS', 150.00, 45, 'imagen_67.jpg'),
('Masajeador de Cuello Naipo', 'Masajeador de cuello Naipo con calor', 60.00, 50, 'imagen_68.jpg'),
('Desinfectante UV PhoneSoap', 'Desinfectante UV PhoneSoap para teléfonos', 100.00, 40, 'imagen_69.jpg'),
('Cepillo de Dientes Eléctrico Oral-B Pro 1000', 'Cepillo de dientes eléctrico Oral-B Pro 1000', 50.00, 60, 'imagen_70.jpg'),

-- Oficina
('Silla de Oficina Herman Miller Aeron', 'Silla de oficina Herman Miller Aeron ergonómica', 1200.00, 15, 'imagen_71.jpg'),
('Escritorio Ajustable VARIDESK Pro Plus', 'Escritorio ajustable VARIDESK Pro Plus para trabajar de pie', 400.00, 20, 'imagen_72.jpg'),
('Impresora Multifuncional Brother MFC-L3770CDW', 'Impresora multifuncional Brother MFC-L3770CDW a color', 300.00, 25, 'imagen_73.jpg'),
('Monitor UltraWide LG 34WK95U-W', 'Monitor UltraWide LG de 34 pulgadas con resolución 5K', 1000.00, 18, 'imagen_74.jpg'),
('Teclado Mecánico Logitech MX Keys', 'Teclado mecánico Logitech MX Keys con retroiluminación', 100.00, 50, 'imagen_75.jpg'),
('Mouse Ergonómico Anker', 'Mouse ergonómico Anker vertical', 30.00, 70, 'imagen_76.jpg'),
('Lámpara de Escritorio LED TaoTronics', 'Lámpara de escritorio LED TaoTronics con puerto USB', 40.00, 60, 'imagen_77.jpg'),
('Auriculares Jabra Evolve2 65', 'Auriculares Jabra Evolve2 65 con cancelación de ruido', 200.00, 30, 'imagen_78.jpg'),
('Webcam Logitech C920', 'Webcam Logitech C920 con resolución Full HD', 70.00, 50, 'imagen_79.jpg'),
('Organizador de Cables Baskiss', 'Organizador de cables Baskiss para escritorio', 20.00, 80, 'imagen_80.jpg'),

-- Ocio
('Consola Nintendo Switch', 'Consola Nintendo Switch con Joy-Con', 300.00, 50, 'imagen_81.jpg'),
('Bicicleta Eléctrica ANCHEER', 'Bicicleta eléctrica ANCHEER con motor de 350W', 600.00, 20, 'imagen_82.jpg'),
('Set de Dardos WIN.MAX', 'Set de dardos WIN.MAX con tablero de dardos', 50.00, 70, 'imagen_83.jpg'),
('Patinete Eléctrico Segway Ninebot', 'Patinete eléctrico Segway Ninebot con velocidad máxima de 15 mph', 500.00, 25, 'imagen_84.jpg'),
('Proyector Portátil Anker Nebula Capsule', 'Proyector portátil Anker Nebula Capsule con batería recargable', 250.00, 30, 'imagen_85.jpg'),
('Cámara Instantánea Fujifilm Instax Mini 11', 'Cámara instantánea Fujifilm Instax Mini 11 con película incluida', 70.00, 60, 'imagen_86.jpg'),
('Set de LEGO Creator Expert', 'Set de LEGO Creator Expert para construir', 150.00, 40, 'imagen_87.jpg'),
('Tablero de ajedrez de madera Wegiel', 'Tablero de ajedrez de madera Wegiel con piezas hechas a mano', 100.00, 50, 'imagen_88.jpg'),
('Juego de mesa Catan', 'Juego de mesa Catan para 3-4 jugadores', 40.00, 80, 'imagen_89.jpg'),
('Guitarra Eléctrica Fender Stratocaster', 'Guitarra eléctrica Fender Stratocaster con amplificador', 600.00, 20, 'imagen_90.jpg'),

-- Celulares
('Apple iPhone 13 Pro', 'Apple iPhone 13 Pro con 256GB de almacenamiento', 1000.00, 30, 'imagen_91.jpg'),
('Samsung Galaxy S21 Ultra', 'Samsung Galaxy S21 Ultra con 512GB de almacenamiento', 1200.00, 25, 'imagen_92.jpg'),
('Google Pixel 6', 'Google Pixel 6 con 128GB de almacenamiento', 700.00, 40, 'imagen_93.jpg'),
('OnePlus 9 Pro', 'OnePlus 9 Pro con 256GB de almacenamiento', 800.00, 30, 'imagen_94.jpg'),
('Xiaomi Mi 11', 'Xiaomi Mi 11 con 256GB de almacenamiento', 600.00, 35, 'imagen_95.jpg'),
('Sony Xperia 5 II', 'Sony Xperia 5 II con 128GB de almacenamiento', 900.00, 20, 'imagen_96.jpg'),
('Oppo Find X3 Pro', 'Oppo Find X3 Pro con 256GB de almacenamiento', 1000.00, 25, 'imagen_97.jpg'),
('Motorola Edge Plus', 'Motorola Edge Plus con 256GB de almacenamiento', 700.00, 30, 'imagen_98.jpg'),
('Huawei P40 Pro', 'Huawei P40 Pro con 256GB de almacenamiento', 900.00, 20, 'imagen_99.jpg'),
('LG V60 ThinQ', 'LG V60 ThinQ con 128GB de almacenamiento', 800.00, 25, 'imagen_100.jpg'),

-- Consolas
('PlayStation 5', 'Consola PlayStation 5 con lector de discos', 500.00, 20, 'imagen_101.jpg'),
('Xbox Series X', 'Consola Xbox Series X con 1TB de almacenamiento', 500.00, 25, 'imagen_102.jpg'),
('Nintendo Switch OLED', 'Consola Nintendo Switch OLED con pantalla de 7 pulgadas', 350.00, 30, 'imagen_103.jpg'),
('PlayStation 4 Pro', 'Consola PlayStation 4 Pro con 1TB de almacenamiento', 400.00, 20, 'imagen_104.jpg'),
('Xbox One X', 'Consola Xbox One X con 1TB de almacenamiento', 400.00, 25, 'imagen_105.jpg'),
('Nintendo Switch Lite', 'Consola Nintendo Switch Lite con pantalla de 5.5 pulgadas', 200.00, 35, 'imagen_106.jpg'),
('Sega Genesis Mini', 'Consola Sega Genesis Mini con 42 juegos incluidos', 80.00, 40, 'imagen_107.jpg'),
('Atari Flashback 9', 'Consola Atari Flashback 9 con 110 juegos incluidos', 70.00, 50, 'imagen_108.jpg'),
('Neo Geo Mini', 'Consola Neo Geo Mini con 40 juegos incluidos', 100.00, 30, 'imagen_109.jpg'),
('SNES Classic Edition', 'Consola SNES Classic Edition con 21 juegos incluidos', 80.00, 40, 'imagen_110.jpg'),

-- Liquidación
('Tablet Samsung Galaxy Tab A7', 'Tablet Samsung Galaxy Tab A7 con pantalla de 10.4 pulgadas', 200.00, 50, 'imagen_111.jpg'),
('Laptop Acer Aspire 5', 'Laptop Acer Aspire 5 con procesador Intel i5', 500.00, 40, 'imagen_112.jpg'),
('Audífonos Sony WH-CH510', 'Audífonos inalámbricos Sony WH-CH510 con Bluetooth', 50.00, 70, 'imagen_113.jpg'),
('Smart TV TCL 4-Series 50"', 'Smart TV TCL 4-Series de 50 pulgadas 4K UHD', 300.00, 30, 'imagen_114.jpg'),
('Cámara Nikon D3500', 'Cámara Nikon D3500 con lente 18-55mm', 400.00, 25, 'imagen_115.jpg'),
('Consola Nintendo 3DS XL', 'Consola Nintendo 3DS XL con pantalla de 4.88 pulgadas', 200.00, 35, 'imagen_116.jpg'),
('Smartwatch Amazfit Bip U Pro', 'Smartwatch Amazfit Bip U Pro con GPS', 60.00, 50, 'imagen_117.jpg'),
('Disco Duro Externo WD Elements 1TB', 'Disco duro externo WD Elements de 1TB', 50.00, 60, 'imagen_118.jpg'),
('Monitor HP 24mh', 'Monitor HP de 24 pulgadas con resolución Full HD', 150.00, 40, 'imagen_119.jpg'),
('Impresora Canon PIXMA TR4520', 'Impresora multifuncional Canon PIXMA TR4520', 80.00, 50, 'imagen_120.jpg'),

-- Tablets
('Apple iPad Pro 12.9"', 'Apple iPad Pro de 12.9 pulgadas con 512GB', 1100.00, 30, 'imagen_121.jpg'),
('Samsung Galaxy Tab S7+', 'Samsung Galaxy Tab S7+ con pantalla de 12.4 pulgadas', 900.00, 25, 'imagen_122.jpg'),
('Microsoft Surface Go 2', 'Microsoft Surface Go 2 con pantalla de 10.5 pulgadas', 500.00, 40, 'imagen_123.jpg'),
('Amazon Fire HD 10', 'Tablet Amazon Fire HD 10 con pantalla de 10.1 pulgadas', 150.00, 60, 'imagen_124.jpg'),
('Lenovo Tab P11 Pro', 'Tablet Lenovo Tab P11 Pro con pantalla OLED', 400.00, 35, 'imagen_125.jpg'),
('Huawei MatePad Pro', 'Tablet Huawei MatePad Pro con pantalla de 10.8 pulgadas', 500.00, 30, 'imagen_126.jpg'),
('Google Pixel Slate', 'Google Pixel Slate con pantalla de 12.3 pulgadas', 600.00, 25, 'imagen_127.jpg'),
('ASUS ZenPad 3S 10', 'Tablet ASUS ZenPad 3S 10 con pantalla de 9.7 pulgadas', 300.00, 40, 'imagen_128.jpg'),
('Acer Chromebook Tab 10', 'Tablet Acer Chromebook Tab 10 con Chrome OS', 200.00, 50, 'imagen_129.jpg'),
('Sony Xperia Z4 Tablet', 'Tablet Sony Xperia Z4 Tablet con pantalla de 10.1 pulgadas', 500.00, 35, 'imagen_130.jpg'),

-- Smartwatchs y Smartbands
('Apple Watch Series 6', 'Apple Watch Series 6 con GPS y Cellular', 400.00, 30, 'imagen_131.jpg'),
('Samsung Galaxy Watch 3', 'Samsung Galaxy Watch 3 con pantalla AMOLED', 350.00, 40, 'imagen_132.jpg'),
('Fitbit Versa 3', 'Fitbit Versa 3 con GPS y seguimiento de salud', 250.00, 50, 'imagen_133.jpg'),
('Garmin Fenix 6', 'Garmin Fenix 6 con mapas topográficos', 600.00, 20, 'imagen_134.jpg'),
('Huawei Watch GT 2 Pro', 'Huawei Watch GT 2 Pro con carga inalámbrica', 300.00, 35, 'imagen_135.jpg'),
('Fossil Gen 5', 'Fossil Gen 5 con Wear OS de Google', 300.00, 40, 'imagen_136.jpg'),
('Amazfit Bip U Pro', 'Amazfit Bip U Pro con GPS y SpO2', 60.00, 70, 'imagen_137.jpg'),
('Suunto 7', 'Suunto 7 con mapas sin conexión', 400.00, 25, 'imagen_138.jpg'),
('Xiaomi Mi Band 6', 'Xiaomi Mi Band 6 con seguimiento de actividad', 50.00, 80, 'imagen_139.jpg'),
('Garmin Vivosmart 4', 'Garmin Vivosmart 4 con monitoreo de energía', 100.00, 60, 'imagen_140.jpg');

-- Insertar datos en ProductoCategoria
INSERT INTO ProductoCategoria (id_producto, id_categoria)
VALUES
-- Ofertas
(1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1), (9, 1), (10, 1),
-- Audio y Video
(11, 2), (12, 2), (13, 2), (14, 2), (15, 2), (16, 2), (17, 2), (18, 2), (19, 2), (20, 2),
-- Televisores
(21, 3), (22, 3), (23, 3), (24, 3), (25, 3), (26, 3), (27, 3), (28, 3), (29, 3), (30, 3),
-- Tendencia
(31, 4), (32, 4), (33, 4), (34, 4), (35, 4), (36, 4), (37, 4), (38, 4), (39, 4), (40, 4),
-- Hogar
(41, 5), (42, 5), (43, 5), (44, 5), (45, 5), (46, 5), (47, 5), (48, 5), (49, 5), (50, 5),
-- Computadoras
(51, 6), (52, 6), (53, 6), (54, 6), (55, 6), (56, 6), (57, 6), (58, 6), (59, 6), (60, 6),
-- Salud
(61, 7), (62, 7), (63, 7), (64, 7), (65, 7), (66, 7), (67, 7), (68, 7), (69, 7), (70, 7),
-- Oficina
(71, 8), (72, 8), (73, 8), (74, 8), (75, 8), (76, 8), (77, 8), (78, 8), (79, 8), (80, 8),
-- Ocio
(81, 9), (82, 9), (83, 9), (84, 9), (85, 9), (86, 9), (87, 9), (88, 9), (89, 9), (90, 9),
-- Celulares
(91, 10), (92, 10), (93, 10), (94, 10), (95, 10), (96, 10), (97, 10), (98, 10), (99, 10), (100, 10),
-- Consolas
(101, 11), (102, 11), (103, 11), (104, 11), (105, 11), (106, 11), (107, 11), (108, 11), (109, 11), (110, 11),
-- Liquidación
(111, 12), (112, 12), (113, 12), (114, 12), (115, 12), (116, 12), (117, 12), (118, 12), (119, 12), (120, 12),
-- Tablets
(121, 13), (122, 13), (123, 13), (124, 13), (125, 13), (126, 13), (127, 13), (128, 13), (129, 13), (130, 13),
-- Smartwatchs y Smartbands
(131, 14), (132, 14), (133, 14), (134, 14), (135, 14), (136, 14), (137, 14), (138, 14), (139, 14), (140, 14),
-- Lo más popular
(1, 15), (2, 15), (3, 15), (4, 15), (5, 15),
-- Nuevos productos
(6, 16), (7, 16), (8, 16), (9, 16), (10, 16),
-- Lo más vendido
(11, 17), (12, 17), (13, 17), (14, 17), (15, 17);

-- Insertar datos en mensajes de foro
INSERT INTO MensajesForo (id_producto, id_usuario, id_encargado,  id_respuesta_a, mensaje, fecha, estado) 
VALUES
('3', '4', '2', NULL, 'Compre estos auriculares la semana pasada y la calidad del sonido es increible. La cancelacion de ruido es excelente y el micrófono funciona a la perfeccion.', current_timestamp(), '0');

-- Ver contenido de la tabla Rol
SELECT * FROM Rol;

-- Ver contenido de la tabla ClienteRegistrado
SELECT * FROM ClienteRegistrado;

-- Ver contenido de la tabla DatosEmpresa
SELECT * FROM DatosEmpresa;

-- Ver contenido de la tabla EncargadoInventarios
SELECT * FROM EncargadoInventarios;

-- Ver contenido de la tabla ClienteNoRegistrado
SELECT * FROM ClienteNoRegistrado;

-- Ver contenido de la tabla DatosBancarios
SELECT * FROM DatosBancarios;

-- Ver contenido de la tabla Productos
SELECT * FROM Productos;

-- Ver contenido de la tabla Categorias;

SELECT * FROM Categorias;

-- Ver contenido de la tabla ProductoCategoria
SELECT * FROM ProductoCategoria;

-- Ver contenido de la tabla CarritoCompra
SELECT * FROM CarritoCompra;

-- Ver contenido de la tabla Compras
SELECT * FROM Compras;

-- Ver contenido de la tabla MensajesForo
SELECT * FROM MensajesForo;
