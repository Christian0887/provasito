<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Hunters | Gaming Hub</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap');

        :root {
            --primary-purple: #8c1aff; 
            --light-purple: #c284e1;    
            --bg-dark: #050507;
            --glass: rgba(15, 15, 20, 0.95);
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

        header {
            padding: 60px 20px 40px 20px;
            text-align: center;
            width: 100%;
        }

        .logo-image {
            width: 450px; 
            max-width: 95%;
            height: auto;
            animation: purpleGlow 3s infinite alternate ease-in-out;
            display: block;
            margin: 0 auto;
        }

        @keyframes purpleGlow {
            from { filter: drop-shadow(0 0 10px rgba(140, 26, 255, 0.4)); transform: scale(1); }
            to { filter: drop-shadow(0 0 30px rgba(140, 26, 255, 0.9)); transform: scale(1.02); }
        }

        .hero-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 50px;
            width: 90%;
            max-width: 420px; 
            border-radius: 15px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.8);
            text-align: center;
            position: relative;
            margin-bottom: 80px;
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
            font-size: 0.9rem;
            margin-bottom: 35px;
            color: #aaa;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .nav-buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .btn {
            position: relative;
            display: block;
            text-decoration: none;
            padding: 16px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 8px;
            letter-spacing: 3px;
            overflow: hidden;
            transition: all 0.4s ease;
            background: rgba(140, 26, 255, 0.05);
            border: 2px solid var(--primary-purple);
            color: white;
            box-shadow: inset 0 0 10px rgba(140, 26, 255, 0.2);
        }

        .btn:hover {
            background: var(--primary-purple);
            color: white;
            box-shadow: 0 0 35px rgba(140, 26, 255, 0.7);
            transform: translateY(-3px);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: 0.6s;
            pointer-events: none;
        }
        
        .btn:hover::after { left: 100%; }

        /* --- NUOVA GRAFICA SESSIONE CRIPTATA --- */
        .encrypted-text {
            margin-top: 35px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            font-weight: bold;
            color: var(--light-purple);
            letter-spacing: 3px;
            text-transform: uppercase;
            text-shadow: 0 0 8px rgba(194, 132, 225, 0.5);
            opacity: 0.8;
            animation: textFlicker 4s infinite;
        }

        @keyframes textFlicker {
            0%, 100% { opacity: 0.8; }
            92% { opacity: 0.8; }
            93% { opacity: 0.5; }
            94% { opacity: 0.8; }
            95% { opacity: 0.2; }
            96% { opacity: 0.8; }
        }

        footer {
            margin-top: auto;
            width: 100%;
            background: #050507;
            padding: 50px 20px;
            border-top: 1px solid #1a1a1a;
        }

        .footer-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .contact-item h4 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-purple); 
            font-size: 0.8rem;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .contact-item p { color: #888; font-size: 0.9rem; line-height: 1.6; }

        .social-link {
            color: var(--light-purple); 
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
            font-size: 0.8rem;
            transition: 0.3s;
        }

        .social-link:hover { text-shadow: 0 0 10px #00f2ff; color: white; }

        .status-dot {
            height: 8px; width: 8px;
            background-color: #00ff88;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.6);
        }
    </style>
</head>
<body>

    <header>
        <img src="img/piovra.png" alt="PC Hunters Logo" class="logo-image">
    </header>

    <div class="hero-card">
        <h2>Accesso al Sistema</h2>
        
        <div class="nav-buttons">
            <a href="login.php" class="btn">ACCEDI</a>
            <a href="registra.php" class="btn">REGISTRATI</a>
        </div>

        <p class="encrypted-text">● SESSIONE CRIPTATA ●</p>
    </div>

    <footer>
        <div class="footer-grid">
            <div class="contact-item">
                <h4>Supporto Tecnico</h4>
                <p>E-mail: support@pchunters.it</p>
                <p>WhatsApp: +39 345 678 9012</p>
            </div>
            <div class="contact-item">
                <h4>Sede Operativa</h4>
                <p>Via dell'Hardware, 128 - Milano (MI)</p>
            </div>
            <div class="contact-item">
                <h4>Social</h4>
                <div style="margin-top: 10px;">
                    <a href="#" class="social-link">INSTAGRAM</a>
                    <a href="#" class="social-link">DISCORD</a>
                </div>
            </div>
            <div class="contact-item">
                <h4>Logistica</h4>
                <p><span class="status-dot"></span> Componenti in Stock</p>
            </div>
        </div>
        <p style="text-align: center; margin-top: 40px; color: #222; font-size: 0.7rem;">&copy; 2026 PC HUNTERS. EXTREME PERFORMANCE GEAR.</p>
    </footer>

</body>
</html>