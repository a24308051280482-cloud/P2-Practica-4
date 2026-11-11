<?php
session_start();
// Contar los artículos actualmente almacenados en la sesión del carrito
$total_articulos = isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coppel - Catálogo de Productos</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background-color: #f4f6f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #2b3e50;
      padding: 12px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    .brand {
      font-size: 24px;
      font-weight: bold;
      color: #ffffff;
      text-decoration: none;
    }

    nav {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    nav a {
      color: #e0e6ed;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    nav a:hover {
      color: #3197e8;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .search-box {
      display: flex;
      align-items: center;
    }

    .search-box input {
      padding: 8px 12px;
      border: none;
      border-radius: 4px 0 0 4px;
      outline: none;
      font-size: 13px;
      width: 180px;
    }

    .search-box button {
      padding: 8px 12px;
      border: none;
      background-color: #3197e8;
      color: white;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
      font-size: 13px;
    }

    .auth-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .btn-header {
      padding: 7px 14px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: bold;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-cart {
      background-color: #28a745;
      color: #ffffff;
    }

    .btn-cart:hover {
      background-color: #218838;
    }

    .btn-login {
      background-color: #3197e8;
      color: #ffffff;
    }

    .btn-login:hover {
      background-color: #257bc4;
    }

    .btn-register {
      background-color: transparent;
      color: #ffffff;
      border: 1px solid #3197e8;
    }

    .btn-register:hover {
      background-color: #3197e8;
    }

    main {
      flex: 1;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .section-block {
      margin-bottom: 45px;
      scroll-margin-top: 80px;
    }

    .section-title {
      font-size: 22px;
      color: #2b3e50;
      margin-bottom: 20px;
      font-weight: bold;
      border-bottom: 2px solid #3197e8;
      padding-bottom: 8px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }

    .product-card {
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }

    .product-img {
      width: 100%;
      height: 160px;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 14px;
      font-weight: bold;
      text-align: center;
      padding: 10px;
    }

    .product-info {
      padding: 15px;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .product-category {
      font-size: 11px;
      text-transform: uppercase;
      color: #3197e8;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .product-name {
      font-size: 14px;
      color: #2b3e50;
      font-weight: bold;
      margin-bottom: 10px;
      min-height: 36px;
      line-height: 1.3;
    }

    .product-price {
      font-size: 17px;
      color: #d9534f;
      font-weight: bold;
      margin-top: auto;
      margin-bottom: 12px;
    }

    .btn-buy {
      width: 100%;
      padding: 9px 0;
      background-color: #3197e8;
      color: white;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      font-size: 13px;
      cursor: pointer;
      text-align: center;
      transition: background-color 0.2s ease;
    }

    .btn-buy:hover {
      background-color: #257bc4;
    }
  </style>
</head>
<body>

  <header>
    <div class="header-left">
      <a href="Productos.php" class="brand">Coppel</a>
      <nav>
        <a href="#celulares">Celulares</a>
        <a href="#ropa">Ropa y Calzado</a>
        <a href="#hogar">Hogar y Muebles</a>
        <a href="#ofertas">Ofertas</a>
      </nav>
    </div>

    <div class="header-right">
      <div class="search-box">
        <input type="text" placeholder="Buscar productos...">
        <button type="button">Buscar</button>
      </div>

      <div class="auth-buttons">
        <!-- Botón del Carrito con contador -->
        <a href="carrito.php" class="btn-header btn-cart">Carrito (<?php echo $total_articulos; ?>)</a>
        <a href="login.php" class="btn-header btn-login">Iniciar sesión</a>
        <a href="registro.php" class="btn-header btn-register">Registrar</a>
      </div>
    </div>
  </header>

  <main>
    <!-- SECCIÓN CELULARES (ID 1 - 10) -->
    <section id="celulares" class="section-block">
      <h2 class="section-title">Celulares</h2>
      <div class="product-grid">
        <div class="product-card">
          <div class="product-img"> 
            <img src="galaxy s23.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Samsung Galaxy S23 Ultra 256GB</h3>
            <div class="product-price">$19,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="1">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="iphone 15.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">iPhone 15 Pro Max 256GB</h3>
            <div class="product-price">$23,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="2">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="redmi note 13.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Xiaomi Redmi Note 13 128GB</h3>
            <div class="product-price">$4,299 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="3">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="moto g84.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Motorola Moto G84 5G 256GB</h3>
            <div class="product-price">$3,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="4">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="magic6 lite.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Honor Magic 6 Lite 256GB</h3>
            <div class="product-price">$6,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="5">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="realme12 pro.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Realme 12 Pro+ 5G 512GB</h3>
            <div class="product-price">$7,899 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="6">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="blade v50.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">ZTE Blade V50 Smart 128GB</h3>
            <div class="product-price">$2,599 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="7">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="reno 10.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">OPPO Reno 10 5G 256GB</h3>
            <div class="product-price">$8,199 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="8">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="poco x6.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">POCO X6 Pro 5G 512GB</h3>
            <div class="product-price">$5,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="9">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="pixel 8.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Celulares</span>
            <h3 class="product-name">Google Pixel 8 128GB</h3>
            <div class="product-price">$12,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="10">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN ROPA Y CALZADO (ID 11 - 20) -->
    <section id="ropa" class="section-block">
      <h2 class="section-title">Ropa y Calzado</h2>
      <div class="product-grid">
        <div class="product-card">
          <div class="product-img"> 
            <img src="nike airmax.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Tenis Nike Air Max System</h3>
            <div class="product-price">$1,899 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="11">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="essentials logo.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Playera Adidas Essentials Logo</h3>
            <div class="product-price">$499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="12">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="chamarra trucker.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Chamarra Levi's de Mezclilla</h3>
            <div class="product-price">$1,699 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="13">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="carina street.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Tenis Puma Carina Street</h3>
            <div class="product-price">$1,299 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="14">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="under armour fleece.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Sudadera Under Armour Fleece</h3>
            <div class="product-price">$1,199 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="15">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="dockers straightfit.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Pantalón Dockers Straight Fit</h3>
            <div class="product-price">$1,099 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="16">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="vestido jlo.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Vestido Casual Jennifer Lopez</h3>
            <div class="product-price">$699 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="17">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="caterpillar2nd shift.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Bota Industrial Caterpillar Second Shift</h3>
            <div class="product-price">$2,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="18">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="chuck taylor allstar.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Tenis Converse Chuck Taylor All Star</h3>
            <div class="product-price">$1,349 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="19">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="puma essentials.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ropa y Calzado</span>
            <h3 class="product-name">Playera Polo Puma Essentials</h3>
            <div class="product-price">$599 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="20">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN HOGAR Y MUEBLES (ID 21 - 30) -->
    <section id="hogar" class="section-block">
      <h2 class="section-title">Hogar y Muebles</h2>
      <div class="product-grid">
        <div class="product-card">
          <div class="product-img"> 
            <img src="lg 65.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Pantalla LG 65 pulgadas 4K UHD</h3>
            <div class="product-price">$10,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="21">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="whirpool 14.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Refrigerador Whirlpool 14 Pies</h3>
            <div class="product-price">$12,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="22">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="mabe 19.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Lavadora Mabe 19 kg Carga Frontal</h3>
            <div class="product-price">$9,899 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="23">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="modular reclinable.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Sala Modular Reclinable Confort</h3>
            <div class="product-price">$14,990 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="24">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="restonic moon.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Colchón Matrimonial Restonic Moon</h3>
            <div class="product-price">$4,599 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="25">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="mabe 6.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Estufa Mabe 6 Quemadores Acero</h3>
            <div class="product-price">$7,299 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="26">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="panasonic 1.2.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Microondas Panasonic 1.2 Cu.ft</h3>
            <div class="product-price">$2,399 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="27">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="comedor 6sillas.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Comedor 6 Sillas Madera Roble</h3>
            <div class="product-price">$8,799 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="28">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="mirage 1ton.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Minisplit Mirage 1 Tonelada Solo Frío</h3>
            <div class="product-price">$5,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="29">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="ninja 1000w.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Hogar y Muebles</span>
            <h3 class="product-name">Licuadora Ninja Professional 1000W</h3>
            <div class="product-price">$1,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="30">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN OFERTAS (ID 31 - 40) -->
    <section id="ofertas" class="section-block">
      <h2 class="section-title">Ofertas</h2>
      <div class="product-grid">
        <div class="product-card">
          <div class="product-img"> 
            <img src="play5 slim.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Consola PlayStation 5 Slim 1TB</h3>
            <div class="product-price">$8,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="31">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="hisense 50.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Pantalla Hisense 50 pulgadas 4K</h3>
            <div class="product-price">$5,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="32">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="lenovo m10.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Tablet Lenovo Tab M10 64GB</h3>
            <div class="product-price">$2,199 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="33">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="sony wh-ch520.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Audífonos Inalámbricos Sony WH-CH520</h3>
            <div class="product-price">$899 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="34">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="hp 15.6.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Laptop HP 15.6 Core i3 8GB RAM</h3>
            <div class="product-price">$6,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="35">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="tefal 4.2.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Freidora de Aire Tefal 4.2L</h3>
            <div class="product-price">$1,199 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="36">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="huawei fit2.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Reloj Inteligente Huawei Watch Fit 2</h3>
            <div class="product-price">$1,499 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="37">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="jbl flip 6.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Bocina Portátil JBL Flip 6</h3>
            <div class="product-price">$1,799 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="38">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="nespresso essenza.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Cafetera Nespresso Essenza Mini</h3>
            <div class="product-price">$1,099 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="39">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>

        <div class="product-card">
          <div class="product-img"> 
            <img src="xiaomi robot.jpg" height="160" width="160"> 
          </div>
          <div class="product-info">
            <span class="product-category">Ofertas</span>
            <h3 class="product-name">Aspiradora Robot Xiaomi Robot Vacuum E10</h3>
            <div class="product-price">$2,999 MXN</div>
            <form action="agregar_carrito.php" method="POST">
              <input type="hidden" name="producto_id" value="40">
              <button type="submit" class="btn-buy">Agregar al carrito</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

</body>
</html>