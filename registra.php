<?php
session_start();
$conn = new mysqli("localhost", "root", "", "miosito");
if($conn->connect_error) die("Errore DB: " . $conn->connect_error);

$errore = ""; 
$successo = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM utenti WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $errore = " Email già registrata!";
    } else {
        $hash = md5($password); 
        $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $hash);
        
        if($stmt->execute()) {
            // Testo modificato come richiesto
            $successo = " Registrazione effettuata con successo!";
        } else {
            $errore = " Errore durante la registrazione!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Hunters | Registrazione</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap');

        :root {
            --primary-purple: #8c1aff; 
            --light-purple: #c284e1;    
            --bg-dark: #050507;
            --glass: rgba(15, 15, 20, 0.95);
            --neon-red: #ff4444;
            --neon-green: #00ff88;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Rajdhani', sans-serif; }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(140, 26, 255, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(194, 132, 225, 0.1) 0%, transparent 35%);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header { padding: 40px 20px 10px 20px; text-align: center; width: 100%; }

        .logo-image {
            width: 300px; 
            max-width: 80%;
            height: auto;
            animation: purpleGlow 3s infinite alternate ease-in-out;
            display: block;
            margin: 0 auto;
            cursor: pointer;
        }

        @keyframes purpleGlow {
            from { filter: drop-shadow(0 0 10px rgba(140, 26, 255, 0.4)); transform: scale(1); }
            to { filter: drop-shadow(0 0 25px rgba(140, 26, 255, 0.8)); transform: scale(1.02); }
        }

        .hero-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 40px;
            width: 90%;
            max-width: 420px; 
            border-radius: 15px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.8);
            text-align: center;
            position: relative;
            margin-bottom: 50px;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-purple), transparent);
            animation: scanLine 3s linear infinite;
        }

        @keyframes scanLine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem;
            margin-bottom: 25px;
            color: #ccc;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        form { display: flex; flex-direction: column; gap: 15px; }

        input {
            width: 100%;
            padding: 14px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(140, 26, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 10px rgba(140, 26, 255, 0.2);
        }

        .password-wrapper { position: relative; }
        
        .eye-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light-purple);
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .btn-submit {
            position: relative;
            width: 100%;
            padding: 16px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.90rem;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 8px;
            letter-spacing: 2px;
            cursor: pointer;
            margin-top: 10px;
            background: rgba(140, 26, 255, 0.05);
            border: 2px solid var(--primary-purple);
            color: white;
            transition: all 0.4s ease;
        }

        .btn-submit:hover {
            background: var(--primary-purple);
            box-shadow: 0 0 30px rgba(140, 26, 255, 0.6);
            transform: translateY(-3px);
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 6px;
            letter-spacing: 1px;
            color: #666;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            border-color: var(--neon-red);
            color: white;
            background: rgba(255, 68, 68, 0.1);
            box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
            transform: translateY(-1px);
        }

        .error-msg { color: var(--neon-red); margin-top: 15px; font-size: 0.85rem; font-weight: bold; }
        .success-msg { color: var(--neon-green); margin-top: 15px; font-size: 0.85rem; font-weight: bold; }

        .encrypted-text {
            margin-top: 25px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.65rem;
            color: var(--light-purple);
            letter-spacing: 2px;
            opacity: 0.5;
        }
    </style>
</head>
<body>

    <header>
        <img src="img/piovra.png" alt="PC Hunters Logo" class="logo-image" onclick="window.location.href='index.php'">
    </header>

    <div class="hero-card">
        <h2>Nuovo Utente</h2>
        
        <form method="POST">
            <input type="text" name="nome" placeholder="NOME COMPLETO" required autofocus>
            <input type="email" name="email" placeholder="INDIRIZZO EMAIL" required>
            
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="CREA PASSWORD" required>
                <button type="button" class="eye-btn" id="togglePassword">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn-submit">REGISTRA ACCOUNT</button>
        </form>

        <a href="index.php" class="btn-back">← TORNA ALLA HOME</a>

        <?php if($errore): ?>
            <div class="error-msg"><?php echo htmlspecialchars($errore); ?></div>
        <?php endif; ?>
        
        <?php if($successo): ?>
            <div class="success-msg"><?php echo htmlspecialchars($successo); ?></div>
        <?php endif; ?>

        <p class="encrypted-text">● SESSIONE CRIPTATA ●</p>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            this.style.color = isPassword ? '#ffffff' : '#c284e1';
        });
    </script>

</body>
</html>