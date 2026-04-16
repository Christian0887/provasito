<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- LOGICA DI LOGOUT INTEGRATA ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// --- 1. CONNESSIONE AL DATABASE ---
$host = "localhost";
$user = "root"; 
$pass = "";     
$db   = "miosito";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
}

// --- 2. CONFIGURAZIONE & PRODOTTI ---
$paypal_email = "cariellochristian@gmail.com"; 
$paypal_url = "https://www.paypal.com/cgi-bin/webscr";

$prodotti = [
    ["id" => "1", "n" => "SEIYA", "cat" => "COMPUTER, PC GAMING", "pv" => "1.249,00", "pn" => "1199.00", "sconto" => "-4%", "img" => "img/pc.jpg", "cpu" => "Intel Core i5-13600K", "gpu" => "NVIDIA RTX 4060 Ti", "ram" => "16GB DDR5 5200MHz", "ssd" => "1TB NVMe M.2 Gen4"],
    ["id" => "2", "n" => "NEZUKO", "cat" => "COMPUTER, PC GAMING", "pv" => "1.878,00", "pn" => "1449.00", "sconto" => "-23%", "img" => "img/pc2.jpg", "cpu" => "Intel Core i7-13700K", "gpu" => "NVIDIA RTX 4070", "ram" => "32GB DDR5 6000MHz", "ssd" => "1TB NVMe M.2 Gen4"],
    ["id" => "3", "n" => "SAKURA", "cat" => "COMPUTER, PC GAMING", "pv" => "1.759,00", "pn" => "1539.00", "sconto" => "-13%", "img" => "img/pc3.jpg", "cpu" => "AMD Ryzen 7 7800X3D", "gpu" => "NVIDIA RTX 4070 Super", "ram" => "32GB DDR5 6000MHz", "ssd" => "2TB NVMe M.2 Gen4"],
    ["id" => "4", "n" => "LUFFY", "cat" => "COMPUTER, PC GAMING", "pv" => "2.284,00", "pn" => "1799.00", "sconto" => "-21%", "img" => "img/pc4.jpg", "cpu" => "Intel Core i9-14900K", "gpu" => "NVIDIA RTX 4080 Super", "ram" => "64GB DDR5 6400MHz", "ssd" => "2TB NVMe M.2 Gen4"],
    ["id" => "5", "n" => "ASUS TUF Gaming VG247Q1A", "cat" => "MONITOR PC", "pv" => "3.100,00", "pn" => "199.00", "sconto" => "-8%", "img" => "img/monitor5.jpg", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"],
    ["id" => "6", "n" => "ASUS ROG Strix XG27AQ-W", "cat" => "MONITOR PC", "pv" => "1.450,00", "pn" => "529.00", "sconto" => "-7%", "img" => "img/monitor6.jpg", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"],
    ["id" => "7", "n" => "COOLER MASTER GM238-FFS", "cat" => "MONITOR PC", "pv" => "2.600,00", "pn" => "189.00", "sconto" => "-15%", "img" => "img/monitor7.jpg", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"],
    ["id" => "8", "n" => "ASUS ROG Strix XG49VQ", "cat" => "MONITOR PC", "pv" => "4.500,00", "pn" => "949.00", "sconto" => "-11%", "img" => "img/monitor8.jpg", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"],
    ["id" => "9", "n" => "MSI G2722", "cat" => "MONITOR PC", "pv" => "1.150,00", "pn" => "179.00", "sconto" => "-13%", "img" => "img/monitor9.jpg", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"],
    ["id" => "10", "n" => "Samsung Odyssey G3", "cat" => "MONITOR PC", "pv" => "1.650,00", "pn" => "129.00", "sconto" => "-9%", "img" => "img/monitor10.webp", "cpu" => "N/A", "gpu" => "N/A", "ram" => "N/A", "ssd" => "N/A"]
];

// --- 3. LOGICA UTENTE ---
$nome_utente = "Ospite"; 
$cart_key = "cart_ospite";

if (isset($_SESSION['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $res = mysqli_query($conn, "SELECT nome FROM utenti WHERE id = '$user_id'");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $nome_utente = $row['nome'];
        $cart_key = "cart_user_" . $user_id; 
    }
}
$iniziale = strtoupper(substr($nome_utente, 0, 1));

// --- 4. LOGICA CARRELLO ---
if (!isset($_SESSION[$cart_key])) { 
    $_SESSION[$cart_key] = []; 
}

if (isset($_GET['add_to_cart'])) {
    $id_p = $_GET['add_to_cart'];
    foreach ($prodotti as $p) {
        if ($p['id'] == $id_p) {
            $_SESSION[$cart_key][] = $p;
            break;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['remove_item'])) {
    $index = (int)$_GET['remove_item'];
    if (isset($_SESSION[$cart_key][$index])) {
        unset($_SESSION[$cart_key][$index]);
        $_SESSION[$cart_key] = array_values($_SESSION[$cart_key]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['empty_cart'])) {
    $_SESSION[$cart_key] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$carrello_attuale = $_SESSION[$cart_key];
$cart_count = count($carrello_attuale);
$cart_total = 0;
foreach($carrello_attuale as $c) { 
    $cart_total += floatval($c['pn']); 
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC HUNTERS | DASHBOARD</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Orbitron:wght@700&display=swap');
        
        :root { 
            --purple: #bc13fe; 
            --bg-dark: #050507; 
            --card-bg: #0c0c0f;
            --drawer-bg: #08080a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-dark); color: #fff; font-family: 'Inter', sans-serif; display: flex; overflow-x: hidden; }

        /* --- ANIMAZIONI --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { right: -400px; }
            to { right: 0; }
        }

        .sidebar { width: 260px; height: 100vh; background: #08080a; border-right: 1px solid rgba(188, 19, 254, 0.1); position: fixed; padding: 30px 0; z-index: 100; overflow-y: auto; }
        .sidebar::-webkit-scrollbar { width: 3px; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--purple); }

        .nav-home-btn { background: rgba(188, 19, 254, 0.15); margin: 0 20px 25px; padding: 15px; border-radius: 8px; border: 1px solid var(--purple); text-decoration: none; color: #fff; display: flex; align-items: center; justify-content: center; gap: 10px; font-family: 'Orbitron'; font-size: 10px; transition: 0.3s; cursor: pointer; }
        .nav-home-btn:hover { background: var(--purple); box-shadow: 0 0 20px var(--purple); transform: scale(1.02); }

        .nav-group { margin-bottom: 5px; }
        .nav-label { display: flex; align-items: center; justify-content: space-between; padding: 12px 25px; color: #eee; font-size: 11px; font-family: 'Orbitron'; cursor: pointer; transition: 0.3s; border-left: 3px solid transparent; }
        .nav-label:hover { background: rgba(188, 19, 254, 0.05); color: var(--purple); padding-left: 30px; }
        .nav-label i.chevron { font-size: 10px; transition: 0.3s; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1); background: rgba(255, 255, 255, 0.02); }
        .nav-group.active .submenu { max-height: 500px; }
        .nav-group.active .chevron { transform: rotate(180deg); }
        .nav-group.active .nav-label { border-left-color: var(--purple); color: var(--purple); }

        .sub-item { display: block; padding: 10px 25px 10px 55px; color: #666; text-decoration: none; font-size: 10px; font-family: 'Orbitron'; transition: 0.2s; cursor: pointer; }
        .sub-item:hover { color: #fff; padding-left: 60px; }

        .main-wrapper { margin-left: 260px; width: 100%; display: flex; flex-direction: column; min-height: 100vh; animation: fadeIn 0.6s ease-out; }
        
        .header-top { padding: 40px 60px; display: grid; grid-template-columns: 1fr 1.5fr 1fr; align-items: center; }
        .logo-header { width: 280px; height: 70px; background: url('img/piovra.png') no-repeat left center; background-size: contain; filter: drop-shadow(0 0 10px var(--purple)); cursor: pointer; transition: 0.3s; }
        .logo-header:hover { filter: drop-shadow(0 0 20px var(--purple)); }

        .search-container { position: relative; width: 100%; max-width: 500px; justify-self: center; }
        .search-bar input { width: 100%; background: #0c0c0f; border: 1px solid #1a1a1a; padding: 14px 45px 14px 20px; border-radius: 12px; color: #fff; outline: none; transition: 0.3s; }
        .search-bar input:focus { border-color: var(--purple); box-shadow: 0 0 15px rgba(188, 19, 254, 0.15); }
        .search-container i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: var(--purple); }
        
        .header-controls { display: flex; align-items: center; gap: 20px; justify-self: end; }
        .cart-trigger { position: relative; cursor: pointer; font-size: 18px; color: #fff; transition: 0.3s; background: rgba(255,255,255,0.03); padding: 10px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.05); }
        .cart-trigger:hover { color: var(--purple); border-color: var(--purple); transform: scale(1.1); }
        
        .cart-badge { position: absolute; top: -5px; right: -5px; background: var(--purple); color: #fff; font-family: 'Orbitron'; font-size: 9px; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; box-shadow: 0 0 10px var(--purple); }
        .user-account { position: relative; }
        .user-info-wrapper { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 5px 15px; border-radius: 50px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(188, 19, 254, 0.2); transition: 0.3s; }
        .user-info-wrapper:hover { border-color: var(--purple); background: rgba(188, 19, 254, 0.1); }
        .user-avatar { width: 32px; height: 32px; background: linear-gradient(135deg, var(--purple), #6a0dad); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Orbitron'; font-weight: bold; color: #fff; font-size: 12px; }
        
        .account-dropdown { position: absolute; top: 120%; right: 0; width: 220px; background: #0c0c0f; border: 1px solid var(--purple); border-radius: 12px; display: none; z-index: 1000; box-shadow: 0 10px 40px rgba(0,0,0,0.8); overflow: hidden; }
        .account-dropdown.active { display: block; animation: fadeIn 0.2s ease-out; }
        .dropdown-link { display: flex; align-items: center; gap: 10px; padding: 12px 15px; color: #ccc; text-decoration: none; font-size: 11px; font-family: 'Orbitron'; transition: 0.2s; }
        .dropdown-link:hover { background: rgba(255,255,255,0.03); color: var(--purple); }

        .cart-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 2000; display: none; transition: 0.3s; }
        .cart-overlay.active { display: block; }
        .cart-drawer { position: fixed; top: 0; right: -400px; width: 400px; height: 100vh; background: var(--drawer-bg); border-left: 1px solid var(--purple); z-index: 2001; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); padding: 40px 30px; display: flex; flex-direction: column; }
        .cart-drawer.active { right: 0; animation: slideInRight 0.4s ease; }
        
        .cart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .cart-header h2 { font-family: 'Orbitron'; font-size: 18px; color: var(--purple); }
        .cart-items-list { flex: 1; overflow-y: auto; margin-bottom: 20px; }
        .cart-item-row { display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05); align-items: center; transition: 0.3s; }
        .cart-item-row:hover { background: rgba(255,255,255,0.02); }
        .cart-item-row img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .cart-item-info h4 { font-family: 'Orbitron'; font-size: 11px; }
        .cart-item-info p { color: var(--purple); font-weight: bold; }
        
        .cart-footer { border-top: 1px solid rgba(188, 19, 254, 0.2); padding-top: 20px; }
        .total-box { display: flex; justify-content: space-between; font-family: 'Orbitron'; font-size: 14px; margin-bottom: 20px; }
        .btn-checkout { width: 100%; padding: 15px; background: var(--purple); border: none; color: #fff; font-family: 'Orbitron'; cursor: pointer; border-radius: 8px; transition: 0.3s; }
        .btn-checkout:hover { background: #d015ff; box-shadow: 0 0 20px var(--purple); }
        
        .content { padding: 20px 60px 80px; flex: 1; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        
        .card { background: var(--card-bg); border: 1px solid #1a1a1a; padding-bottom: 25px; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer; text-align: center; position: relative; border-radius: 4px; overflow: hidden; }
        .card:hover { border-color: var(--purple); transform: translateY(-8px); box-shadow: 0 10px 30px rgba(188, 19, 254, 0.2); }
        .card img { width: 100%; height: 250px; object-fit: cover; transition: 0.5s; }
        .card:hover img { transform: scale(1.05); }
        
        .badge-sconto { position: absolute; top: 12px; left: 12px; background: rgba(0, 0, 0, 0.6); color: #fff; padding: 5px 12px; font-size: 12px; border-radius: 6px; font-family: 'Orbitron'; z-index: 2; }
        .product-name { color: #fff; font-size: 15px; font-weight: 700; margin-top: 15px; font-family: 'Orbitron'; }
        .price-new { color: var(--purple); font-weight: 800; font-size: 16px; margin-top: 10px; }

        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); backdrop-filter: blur(10px); }
        .modal-content { background: #0c0c0f; margin: 5% auto; padding: 40px; border: 1px solid var(--purple); width: 80%; max-width: 900px; border-radius: 12px; display: flex; gap: 40px; position: relative; animation: fadeIn 0.4s ease; }
        .modal-left img { width: 350px; height: 350px; object-fit: cover; border-radius: 8px; }
        .specs-list { list-style: none; margin: 20px 0; }
        .specs-list li { padding: 10px 0; border-bottom: 1px solid #1a1a1a; font-size: 13px; color: #ccc; display: flex; justify-content: space-between; }
        .specs-list b { color: var(--purple); font-size: 10px; font-family: 'Orbitron'; }
        .btn-add-cart { background: #fff; color: #000; border: none; padding: 15px; border-radius: 6px; font-family: 'Orbitron'; cursor: pointer; width: 100%; display: block; text-align: center; text-decoration: none; transition: 0.3s; }
        .btn-add-cart:hover { background: var(--purple); color: #fff; }
        
        .main-footer { background: #030305; padding: 60px 10%; border-top: 1px solid #111; margin-top: auto; }
        .footer-logo { font-family: 'Orbitron'; font-size: 20px; font-weight: bold; }
        .footer-logo span { color: var(--purple); }
    </style>
</head>
<body>

    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h2>CARRELLO</h2>
            <i class="fa-solid fa-xmark close-cart" onclick="toggleCart()" style="cursor:pointer"></i>
        </div>
        <div class="cart-items-list">
            <?php if($cart_count > 0): ?>
                <?php foreach($carrello_attuale as $index => $item): ?>
                    <div class="cart-item-row">
                        <img src="<?php echo $item['img']; ?>" alt="">
                        <div class="cart-item-info">
                            <h4><?php echo $item['n']; ?></h4>
                            <p>€<?php echo number_format($item['pn'], 2, ',', '.'); ?></p>
                        </div>
                        <a href="?remove_item=<?php echo $index; ?>" style="color: #444; margin-left: 10px;"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; margin-top:50px; color:#333;">
                    <i class="fa-solid fa-cart-shopping" style="font-size:40px; margin-bottom:15px; opacity:0.2;"></i>
                    <p style="font-family:'Orbitron'; font-size:10px;">IL CARRELLO È VUOTO</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if($cart_count > 0): ?>
            <div class="cart-footer">
                <div class="total-box"><span>SUBTOTALE</span><span style="color:var(--purple)">€<?php echo number_format($cart_total, 2, ',', '.'); ?></span></div>
                <form action="<?php echo $paypal_url; ?>" method="post">
                    <input type="hidden" name="cmd" value="_cart"><input type="hidden" name="upload" value="1"><input type="hidden" name="business" value="<?php echo $paypal_email; ?>"><input type="hidden" name="currency_code" value="EUR">
                    <?php $i=1; foreach($carrello_attuale as $item): ?>
                        <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $item['n']; ?>"><input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $item['pn']; ?>">
                    <?php $i++; endforeach; ?>
                    <button type="submit" class="btn-checkout">PROCEDI AL PAGAMENTO</button>
                </form>
                <a href="?empty_cart=1" style="display:block; text-align:center; color:#555; font-size:10px; margin-top:15px; text-decoration:none; font-family:'Orbitron'">SVUOTA CARRELLO</a>
            </div>
        <?php endif; ?>
    </div>

    <aside class="sidebar">
        <a onclick="filterByCategory('all')" class="nav-home-btn"><i class="fa-solid fa-house"></i> MOSTRA TUTTO</a>

        <div class="nav-group">
            <div class="nav-label" onclick="this.parentElement.classList.toggle('active')">
                <span><i class="fa-solid fa-desktop" style="width:20px"></i> PC GAMING</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <div class="submenu">
                <a onclick="filterByCategory('COMPUTER')" class="sub-item">ENTRY LEVEL</a>
            </div>
        </div>

        <div class="nav-group">
            <div class="nav-label" onclick="this.parentElement.classList.toggle('active')">
                <span><i class="fa-solid fa-keyboard" style="width:20px"></i> PERIFERICHE</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <div class="submenu">
                <a onclick="filterByCategory('MONITOR')" class="sub-item">MONITOR</a>
                <a class="sub-item">TASTIERE</a>
                <a class="sub-item">MOUSE</a>
            </div>
        </div>

        <hr style="border:0; border-top:1px solid rgba(255,255,255,0.05); margin:15px 20px;">
        <a href="#" class="nav-label" style="text-decoration:none;"><i class="fa-solid fa-envelope"></i> CONTATTI</a>
    </aside>

    <div class="main-wrapper">
        <header class="header-top">
            <div class="logo-header" onclick="window.location.href='index.php'"></div>
            <div class="search-container">
                <div class="search-bar">
                    <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="CERCA HARDWARE...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
            <div class="header-controls">
                <div class="cart-trigger" onclick="toggleCart()">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if($cart_count > 0): ?><span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?>
                </div>
                <div class="user-account">
                    <div class="user-info-wrapper" onclick="toggleDropdown()">
                        <span class="user-name" style="font-family:'Orbitron'; font-size:10px;"><?php echo htmlspecialchars($nome_utente); ?></span>
                        <div class="user-avatar"><?php echo $iniziale; ?></div>
                    </div>
                    <div class="account-dropdown" id="accountDropdown">
                        <a href="profilo.php" class="dropdown-link"><i class="fa-solid fa-circle-user"></i> PROFILO</a>
                        <a href="ordini.php" class="dropdown-link"><i class="fa-solid fa-box"></i> ORDINI</a>
                        <!-- MODIFICA QUI: LOGOUT INTEGRATO -->
                        <a href="?logout=1" class="dropdown-link" style="color:#ff4444; border-top:1px solid rgba(255,255,255,0.05);">
                            <i class="fa-solid fa-power-off"></i> LOGOUT
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="grid" id="productGrid">
                <div id="no-results" style="display:none; text-align:center; grid-column:1/-1; padding:50px; font-family:'Orbitron'; color:#333;">NO_DATA_FOUND</div>
                
                <?php foreach ($prodotti as $item): ?>
                <div class="product-wrapper" 
                     data-name="<?php echo strtolower($item['n']); ?>" 
                     data-category="<?php echo strtoupper($item['cat']); ?>">
                    <div class="card" onclick="openModal('<?php echo $item['n']; ?>', '<?php echo $item['img']; ?>', '<?php echo $item['cpu']; ?>', '<?php echo $item['gpu']; ?>', '<?php echo $item['ram']; ?>', '<?php echo $item['ssd']; ?>', '<?php echo $item['pn']; ?>', '<?php echo $item['id']; ?>')">
                        <div class="badge-sconto"><?php echo $item['sconto']; ?></div>
                        <img src="<?php echo $item['img']; ?>">
                        <div class="product-name"><?php echo $item['n']; ?></div>
                        <div class="price-new">€<?php echo number_format($item['pn'], 2, ',', '.'); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>

        <footer class="main-footer">
            <div class="footer-logo">PC <span>HUNTERS</span></div>
            <p style="color:#444; font-size:11px; margin-top:10px;">&copy; 2026 CYBER_PCH_RETAIL. All rights reserved.</p>
        </footer>
    </div>

    <!-- Modale -->
    <div id="pcModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()" style="position:absolute; right:20px; top:10px; cursor:pointer; font-size:25px;">&times;</span>
            <div class="modal-left"><img id="modalImg" src=""></div>
            <div class="modal-right">
                <h2 id="modalTitle" style="font-family:'Orbitron'; color:var(--purple);"></h2>
                <ul class="specs-list">
                    <li><b>CPU</b> <span id="specCpu"></span></li>
                    <li><b>GPU</b> <span id="specGpu"></span></li>
                    <li><b>RAM</b> <span id="specRam"></span></li>
                    <li><b>SSD</b> <span id="specSsd"></span></li>
                    <li><b>PRICE</b> <span id="specPrice" style="color:var(--purple); font-weight:bold;"></span></li>
                </ul>
                <a id="addToCartBtn" href="#" class="btn-add-cart">AGGIUNGI AL CARRELLO</a>
            </div>
        </div>
    </div>

    <script>
    function toggleCart() {
        document.getElementById('cartDrawer').classList.toggle('active');
        document.getElementById('cartOverlay').classList.toggle('active');
    }
    function toggleDropdown() {
        document.getElementById('accountDropdown').classList.toggle('active');
    }
    window.onclick = function(e) {
        if (!e.target.closest('.user-account')) { document.getElementById('accountDropdown').classList.remove('active'); }
        if (e.target.className === "modal") { closeModal(); }
    }

    function filterProducts() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let products = document.getElementsByClassName('product-wrapper');
        let found = false;
        for (let p of products) {
            let name = p.getAttribute('data-name');
            if (name.includes(input)) { p.style.display = "block"; found = true; }
            else { p.style.display = "none"; }
        }
        document.getElementById('no-results').style.display = found ? "none" : "block";
    }

    function filterByCategory(category) {
        let products = document.getElementsByClassName('product-wrapper');
        let found = false;
        category = category.toUpperCase();

        for (let p of products) {
            let pCat = p.getAttribute('data-category');
            if (category === 'ALL' || pCat.includes(category)) {
                p.style.display = "block";
                found = true;
            } else {
                p.style.display = "none";
            }
        }
        document.getElementById('no-results').style.display = found ? "none" : "block";
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function openModal(name, img, cpu, gpu, ram, ssd, price, id) {
        document.getElementById('modalTitle').innerText = name;
        document.getElementById('modalImg').src = img;
        document.getElementById('specCpu').innerText = cpu;
        document.getElementById('specGpu').innerText = gpu;
        document.getElementById('specRam').innerText = ram;
        document.getElementById('specSsd').innerText = ssd;
        document.getElementById('specPrice').innerText = "€" + parseFloat(price).toLocaleString('it-IT', {minimumFractionDigits: 2});
        document.getElementById('addToCartBtn').href = "?add_to_cart=" + id;
        document.getElementById('pcModal').style.display = "block";
    }
    function closeModal() { document.getElementById('pcModal').style.display = "none"; }
    </script>
</body>
</html>