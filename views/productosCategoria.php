<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/navbar y footer.css">
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                margin: 0;
                padding: 0;
            }
            #servicios, .productos{
                padding: 20px;
                text-align: center;
            }

            .listaProductos{
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .itemProducto img{
                width: 100%;
                height: 100px;
                margin: 5px;
            }

            .itemProducto {
                border: 1px solid #ccc;
                padding: 20px;
                margin: 5px;
                width: 150px;
                text-align: center;
                border-radius: 10px;
            }
            .verProducto {
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <header>

        </header>
        <main>
            <section class="productos">
                <h2>Categoria x</h2>
                <div class="listaProductos">
                    <?php include '../database/get_categories.php';?> 
                </div>
            </section>
        </main>
        <script>
            function redirigir(url){
                window.location.href = url; 
            }
        </script>
    </body>
</html>

