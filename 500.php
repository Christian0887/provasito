<?php
// Impostiamo l'header per confermare il codice di stato
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Debug Errore 500</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; color: #333; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; border: 1px solid #ccc; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; border-bottom: 2px solid #d9534f; padding-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; color: #555; display: block; margin-bottom: 5px; }
        pre { background: #272822; color: #f8f8f2; padding: 15px; overflow-x: auto; border-radius: 4px; font-size: 13px; }
        .footer { font-size: 0.8em; color: #888; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1>⚠️ Errore Interno del Server (500)</h1>
    <p>Dettagli tecnici per il debug:</p>

    <div class="section">
        <span class="label">Ultimo Errore Rilevato:</span>
        <pre><?php 
            $error = error_get_last();
            if ($error) {
                print_r($error);
            } else {
                echo "Nessun dettaglio specifico catturato da error_get_last().\n";
                echo "Controlla i log di sistema (error_log).";
            }
        ?></pre>
    </div>

    <div class="section">
        <span class="label">Stack Trace (Se disponibile):</span>
        <pre><?php 
            try {
                throw new Exception("Generazione Stack Trace per Debug");
            } catch (Exception $e) {
                echo $e->getTraceAsString();
            }
        ?></pre>
    </div>

    <div class="section">
        <span class="label">Variabili di Sessione ($_SESSION):</span>
        <pre><?php session_start(); print_r($_SESSION); ?></pre>
    </div>

    <div class="section">
        <span class="label">Dati Server ($_SERVER):</span>
        <pre><?php print_r($_SERVER); ?></pre>
    </div>
</div>

<div class="footer">
    Generato automaticamente dal sistema di Debug | <?php echo date('Y-m-d H:i:s'); ?>
</div>

</body>
</html>