<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gaming Store - Listino</title>
    <style>
        /* Reset base */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #0e0e0e;
            color: #fff;
        }

        header {
            background: #111;
            padding: 25px 50px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #00ffcc;
            text-shadow: 1px 1px 5px #000;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 35px;
            padding: 40px 50px;
        }

        .card {
            background: #1a1a1a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.6);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.8);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 20px;
            text-align: center;
        }

        .card-content h3 {
            font-size: 20px;
            color: #00ffcc;
            margin-bottom: 10px;
        }

        .card-content p.price {
            font-size: 18px;
            font-weight: bold;
            color: #ff4d4d;
            margin-bottom: 15px;
        }

        .card-content a {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            color: #0e0e0e;
            background: #00ffcc;
            transition: 0.3s;
        }

        .card-content a:hover {
            background: #00cc99;
            color: #fff;
        }

        footer {
            text-align: center;
            padding: 25px;
            background: #111;
            margin-top: 50px;
            color: #888;
            font-size: 14px;
        }

        @media(max-width: 768px) {
            .card img { height: 180px; }
            header { font-size: 24px; }
        }
    </style>
</head>
<body>

<header>Gaming Store - PC & Laptop</header>

<div class="products">
<?php
// Array prodotti
$prodotti = [
    1 => ["PC Gamer Ultra", "PC desktop gaming RTX 4090, 32GB RAM, SSD 2TB", 2199, "img/pc1.jpg"],
    2 => ["Laptop Gaming RTX", "Laptop gaming RTX 3080, 16GB RAM, SSD 1TB", 1899, "img/laptop1.jpg"],
    3 => ["PC Gamer RGB", "PC desktop RGB, RTX 4070, 32GB RAM, SSD 1TB", 2499, "img/pc2.jpg"],
    4 => ["Laptop High-End", "Laptop gaming top RTX 4080, 32GB RAM, SSD 2TB", 2099, "img/laptop2.jpg"]
];

// Ciclo per stampare le card
foreach($prodotti as $id => $prodotto){
    echo '<div class="card">
            <img src="'.$prodotto[3].'" alt="'.$prodotto[0].'">
            <div class="card-content">
                <h3>'.$prodotto[0].'</h3>
                <p class="price">€'.$prodotto[2].'</p>
                <a href="prodotto.php?id='.$id.'">Visualizza</a>
            </div>
          </div>';
}
?>
</div>

<footer>© 2026 Gaming Store | Tutti i diritti riservati</footer>

</body>
</html>
<div style="text-align:center; margin:20px;">
    <a href="dashboard.php" style="
        display:inline-block;
        padding:10px 20px;
        background:#00ffcc;
        color:#0c0c0c;
        font-weight:bold;
        border-radius:6px;
        text-decoration:none;
        transition:0.3s;">
        ⬅ Torna alla Dashboard
    </a>
</div>