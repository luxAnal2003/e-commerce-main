<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendedor</title>
    <link rel="stylesheet" href="../css/navbar y footer.css">
    <style>
        .contenedor{
            padding: 20px;
            text-align: left;  
        }
        .contenido {
            display: none;
        }

        .activo {
            display: block;
        }

        #titulos {
            display: flex;
            justify-content: center;
            margin-bottom: 20px; 
        }

        .titulo {
            margin-right: 10px;
            cursor: pointer;
        }

        .titulo.activo {
            font-weight: bold;
            color: green;
        }

        .nuevoProd{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .nuevoProd h2{
            flex:1;
            text-align: center;
        }
        
        .listaProductos{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .itemProducto {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 5px;
            width: 150px;
            text-align: center;
            border-radius: 10px;
        }

        .itemProducto img{
            width: 150px;
            height: auto;
        }
        
        .resena a{
            text-decoration: none;
            color: #ee6c4d;
        }

        .resena, .replica {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .resena p, .replica p {
            margin: 5px 0;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
        }
        .verProducto {
            cursor: pointer;
        }

        button {
            background-color: #3d5a80;
            color: #ffffff;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 20px;
        }

        button:hover {
            background-color: #98c1d9;
        }
    </style>
</head>
<body>
    <main>
        <div class="contenedor">
            <div id="titulos">
                <a class="titulo activo" id="uno" onclick="cambiarPestania('productos')">Productos</a>
                <a class="titulo" id="dos" onclick="cambiarPestania('clientesPorResponder')">Clientes por responder</a>
                <a class="titulo" id="tres" onclick="cambiarPestania('clientesRespondidos')">Clientes respondidos</a>
            </div>

            <div id="productos" class="contenido activo">
                <div class="nuevoProd">
                    <h2>Todos los productos ordenados por orden alfabetico</h2>
                    <button>Agregar producto</button>
                </div>
                <div class="listaProductos">
                    <?php include '../components/itemProducto.php';?>
                </div>
            </div>
            <div id="clientesPorResponder" class="contenido">
            <section class="resena">
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                </section>
            </div>
            <div id="clientesRespondidos" class="contenido">
            <section class="resena">
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>

                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>

                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                </section>
            </div>

        </div>
        
    </main>
    <script>
        function cambiarPestania(id) {
            // Ocultar todos los contenidos
            var contenidos = document.getElementsByClassName('contenido');
            for (var i = 0; i < contenidos.length; i++) {
                contenidos[i].classList.remove('activo');
            }

            // Mostrar el contenido seleccionado
            var contenidoSeleccionado = document.getElementById(id);
            contenidoSeleccionado.classList.add('activo');

            // Cambiar estilo de los títulos
            var titulos = document.getElementsByClassName('titulo');
            for (var j = 0; j < titulos.length; j++) {
                titulos[j].classList.remove('activo');
            }
            document.getElementById(event.target.id).classList.add('activo'); 
        }

        function redirigir(url){
            window.location.href = url; 
        }
    </script>
</body>
</html>
