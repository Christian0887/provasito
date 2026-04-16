<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

// Lista PC da gaming (stessa lista dashboard)
$pcList = [
    1=>["nome"=>"CyberX Pro","descrizione"=>"Intel i7, RTX 3080, 16GB RAM, 1TB SSD","prezzo"=>"1499","img"=>"https://via.placeholder.com/400x220?text=CyberX+Pro"],
    2=>["nome"=>"GamerMax 5000","descrizione"=>"AMD Ryzen 9, RTX 3090, 32GB RAM, 2TB SSD","prezzo"=>"1899","img"=>"https://via.placeholder.com/400x220?text=GamerMax+5000"],
    3=>["nome"=>"UltraGame Elite","descrizione"=>"Intel i9, RTX 4090, 64GB RAM, 2TB NVMe SSD","prezzo"=>"2299","img"=>"https://via.placeholder.com/400x220?text=UltraGame+Elite"],
    4=>["nome"=>"Vortex Gaming","descrizione"=>"AMD Ryzen 7, RTX 4070, 16GB RAM, 1TB SSD","prezzo"=>"1999","img"=>"https://via.placeholder.com/400x220?text=Vortex+Gaming"]
];

if(!isset($_GET['id']) || !isset($pcList[$_GET['id']])){
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$pc = $pcList[$id];
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pc['nome']); ?></title>
<style>
body{background:#0a0a0a;color:#00ffcc;font-family:Arial,sans-serif;margin:0;padding:0;}
.container{max-width:600px;margin:50px auto;background:rgba(20,20,20,0.95);padding:20px;border-radius:15px;box-shadow:0 10px 50px rgba(0,255,204,0.5);text-align:center;}
.container img{width:100%;border-radius:10px;margin-bottom:15px;}
.container h2{margin-bottom:10px;}
.container p{margin-bottom:10px;font-weight:bold;color:#00ffcc;}
a.back{display:inline-block;padding:8px 15px;background:#00ffcc;color:#000;font-weight:bold;border-radius:8px;text-decoration:none;margin-bottom:15px;transition:0.3s;}
a.back:hover{background:#00ffa8;}
</style>
</head>
<body>
<div class="container">
<a href="dashboard.php" class="back">← Torna alla Dashboard</a>
<img src="<?php echo $pc['img']; ?>" alt="<?php echo htmlspecialchars($pc['nome']); ?>">
<h2><?php echo htmlspecialchars($pc['nome']); ?></h2>
<p><?php echo htmlspecialchars($pc['descrizione']); ?></p>
<p>Prezzo: €<?php echo $pc['prezzo']; ?></p>
<div id="paypal-button-container"></div>
</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_SANDBOX_CLIENT_ID&currency=EUR"></script>
<script>
paypal.Buttons({
    style: { shape:'rect', color:'gold', layout:'vertical', label:'paypal' },
    createOrder: function(data, actions){
        return actions.order.create({
            purchase_units:[{
                description: '<?php echo htmlspecialchars($pc['nome']); ?>',
                amount:{ value: '<?php echo $pc['prezzo']; ?>' }
            }]
        });
    },
    onApprove: function(data, actions){
        return actions.order.capture().then(function(details){
            alert('Pagamento completato! Grazie '+details.payer.name.given_name);
            window.location.href="success.php";
        });
    },
    onCancel: function(data){
        alert('Pagamento annullato.');
        window.location.href="cancel.php";
    },
    onError: function(err){
        console.error(err);
        alert('Errore nel pagamento.');
    }
}).render('#paypal-button-container');
</script>
</body>
</html>