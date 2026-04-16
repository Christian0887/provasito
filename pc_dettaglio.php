<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

// Lista PC da gaming finta
$pcList = [
    1=>["nome"=>"CyberX Pro","descrizione"=>"Intel i7, RTX 3080, 16GB RAM, 1TB SSD","prezzo"=>"1499","img"=>"https://via.placeholder.com/500x300?text=CyberX+Pro"],
    2=>["nome"=>"GamerMax 5000","descrizione"=>"AMD Ryzen 9, RTX 3090, 32GB RAM, 2TB SSD","prezzo"=>"1899","img"=>"https://via.placeholder.com/500x300?text=GamerMax+5000"],
    3=>["nome"=>"UltraGame Elite","descrizione"=>"Intel i9, RTX 4090, 64GB RAM, 2TB NVMe SSD","prezzo"=>"2299","img"=>"https://via.placeholder.com/500x300?text=UltraGame+Elite"],
    4=>["nome"=>"Vortex Gaming","descrizione"=>"AMD Ryzen 7, RTX 4070, 16GB RAM, 1TB SSD","prezzo"=>"1999","img"=>"https://via.placeholder.com/500x300?text=Vortex+Gaming"]
];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!isset($pcList[$id])){
    die("PC non trovato");
}
$pc = $pcList[$id];
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pc['nome']); ?> - Dettagli</title>
<style>
body{background:#000;color:#00ffcc;font-family:Arial,sans-serif;text-align:center;padding:20px;}
header{display:flex;justify-content:space-between;align-items:center;padding:20px;background:rgba(0,0,0,0.85);border-bottom:2px solid #00ffcc;}
header h1{text-shadow:0 0 15px #00ffcc;}
header a{padding:10px 20px;border-radius:8px;text-decoration:none;color:#000;font-weight:bold;background:linear-gradient(90deg,#00ffcc,#0066ff);transition:0.3s;}
header a:hover{background:linear-gradient(90deg,#00ffa8,#3399ff);}
.pc-detail{background:rgba(20,20,20,0.95);padding:20px;border-radius:15px;max-width:600px;margin:30px auto;box-shadow:0 10px 30px rgba(0,255,204,0.4);}
.pc-detail img{width:100%;border-radius:10px;margin-bottom:15px;}
.pc-detail h2{margin-bottom:10px;}
.pc-detail p{margin-bottom:10px;font-weight:bold;color:#00ffcc;}
#paypal-button-container{margin-top:15px;}
</style>
</head>
<body>
<header>
<h1>Dettagli PC</h1>
<a href="dashboard.php">Torna Catalogo</a>
</header>

<div class="pc-detail">
<img src="<?php echo $pc['img']; ?>" alt="<?php echo htmlspecialchars($pc['nome']); ?>">
<h2><?php echo htmlspecialchars($pc['nome']); ?></h2>
<p><?php echo htmlspecialchars($pc['descrizione']); ?></p>
<p>€<?php echo $pc['prezzo']; ?></p>

<!-- Contenitore PayPal -->
<div id="paypal-button-container"></div>
</div>

<!-- SDK PayPal Sandbox -->
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_SANDBOX_CLIENT_ID&currency=EUR"></script>
<script>
// Smart Buttons PayPal
paypal.Buttons({
    style: {
        shape: 'rect',
        color: 'gold',
        layout: 'vertical',
        label: 'paypal'
    },
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                description: '<?php echo htmlspecialchars($pc['nome']); ?>',
                amount: { value: '<?php echo $pc['prezzo']; ?>' }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            alert('Pagamento completato! Grazie, ' + details.payer.name.given_name);
            window.location.href = "success.php";
        });
    },
    onCancel: function(data) {
        alert('Pagamento annullato.');
        window.location.href = "cancel.php";
    },
    onError: function(err) {
        console.error(err);
        alert('Errore nel pagamento.');
    }
}).render('#paypal-button-container');
</script>

</body>
</html>