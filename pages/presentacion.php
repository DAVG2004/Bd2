<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería Eclipse - ¿Quiénes Somos?</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #eaeaea;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: #1a1a1a;
            border-bottom: 2px solid #00d4ff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: white;
        }

        .navbar-brand img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            border: 2px solid #00d4ff;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .navbar-brand h1 {
            margin: 0;
            font-size: 1.5em;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .btn-back {
            background: transparent;
            color: #00ff88;
            border: 1px solid #00ff88;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #00ff88;
            color: black;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.4);
        }

        .hero-section {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(180deg, #1a1a1a 0%, #121212 100%);
            border-bottom: 1px solid #333;
        }

        .hero-section h2 {
            font-size: 3em;
            color: #00d4ff;
            margin-bottom: 15px;
            letter-spacing: 3px;
        }

        .hero-section p {
            font-size: 1.2em;
            max-width: 700px;
            margin: 0 auto;
            color: #bbb;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .section-title {
            color: #00ff88;
            font-size: 2em;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .content-block {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            border-left: 4px solid #00d4ff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .content-block h3 {
            margin-top: 0;
            color: #fff;
            font-size: 1.5em;
        }

        .artists-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .artist-card {
            background: #222;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #333;
            transition: 0.3s;
        }

        .artist-card:hover {
            transform: translateY(-5px);
            border-color: #00d4ff;
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.1);
        }

        .artist-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }

        .artist-name {
            color: #00d4ff;
            font-size: 1.4em;
            font-weight: bold;
            margin: 10px 0;
        }

        .artist-desc {
            font-size: 0.9em;
            color: #aaa;
        }

        footer {
            text-align: center;
            padding: 30px;
            background: #0d0d0d;
            border-top: 1px solid #222;
            margin-top: 60px;
            color: #666;
        }

    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            <img src="../resources/logo_eclipse.png" alt="Logo">
            <h1>Galería <span style="color: #00d4ff;">Eclipse</span></h1>
        </a>
        <a href="index.php" class="btn-back">Entrar a la Galería ➡</a>
    </nav>

    <header class="hero-section">
        <h2>EL ARTE NO TIENE LÍMITES</h2>
        <p>Bienvenido a Galería Eclipse. Un espacio digital vanguardista diseñado para conectar el talento puro con aquellos que saben apreciar la belleza en todas sus formas.</p>
    </header>

    <div class="container">
        
        <div class="content-block">
            <h3>¿Quiénes Somos?</h3>
            <p>Galería Eclipse nació de una pasión compartida por la expresión artística. Somos un equipo de curadores, tecnólogos y soñadores que creen firmemente que el arte moderno merece un escaparate a su altura. No somos solo una plataforma de venta; somos un ecosistema donde la creatividad fluye, las historias se cuentan a través del lienzo, el metal y la luz, y donde cada pieza encuentra su lugar ideal en el mundo.</p>
        </div>

        <div class="content-block" style="border-left-color: #00ff88;">
            <h3 style="color: #00ff88;">Nuestro Propósito</h3>
            <p>Nuestro objetivo principal en <strong>Galería Eclipse</strong> es democratizar el acceso al arte de élite. Buscamos eliminar las barreras entre el creador y el espectador, ofreciendo una galería inmersiva donde el talento emergente y consolidado pueda brillar. Creemos que cada obra tiene un "eclipse" único: ese momento mágico donde la visión del artista se alinea perfectamente con la emoción del comprador.</p>
        </div>

        <h2 class="section-title">Artistas Destacados</h2>
        <p style="text-align: center; color: #bbb; margin-bottom: 40px;">Conoce a las mentes brillantes que dan vida a nuestras exposiciones exclusivas.</p>

        <div class="artists-grid">
            <div class="artist-card">
                <div class="artist-icon">🎨</div>
                <div class="artist-name">Elena Rostova</div>
                <div class="artist-desc">Maestra del óleo y el impresionismo. Sus lienzos surrealistas y abstractos te transportan a paisajes oníricos llenos de color y textura.</div>
            </div>

            <div class="artist-card">
                <div class="artist-icon">🗿</div>
                <div class="artist-name">Marcus Vane</div>
                <div class="artist-desc">Escultor visionario británico. Transforma el bronce, el mármol y la piedra cruda en expresiones modernas y atemporales de la figura y el espacio.</div>
            </div>

            <div class="artist-card">
                <div class="artist-icon">📸</div>
                <div class="artist-name">Sofia Chen</div>
                <div class="artist-desc">El ojo detrás del lente. Fotógrafa apasionada por capturar la crudeza urbana y la serenidad de la naturaleza en cada uno de sus disparos.</div>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Galería Eclipse. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
