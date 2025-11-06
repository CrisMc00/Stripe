<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso! 🎉</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: checkmark 0.8s ease;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(45deg);
            }
            50% {
                transform: scale(1.2) rotate(45deg);
            }
            100% {
                transform: scale(1) rotate(45deg);
            }
        }

        .success-icon::before {
            content: '✓';
            color: white;
            font-size: 50px;
            font-weight: bold;
        }

        h1 {
            color: #2d3748;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .info-box {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
        }

        .loading {
            color: #718096;
            font-style: italic;
        }

        .button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .error {
            color: #e53e3e;
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon"></div>
        <h1>¡Pago Exitoso!</h1>
        <p class="subtitle">Tu transacción se ha completado correctamente</p>

        <div class="info-box" id="paymentInfo">
            <div class="loading">Cargando información del pago...</div>
        </div>

        <a href="/index2" class="button">Volver a la Panadería</a>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const sessionId = urlParams.get('session_id');
        const isPremium = urlParams.get('premium'); // Detectar si es suscripción premium
        const paymentInfoDiv = document.getElementById('paymentInfo');

        // Si es una suscripción premium, activarla
        if (isPremium === 'true') {
            localStorage.setItem('isPremium', 'true');
            document.querySelector('h1').textContent = '⭐ ¡Bienvenido a Premium!';
            document.querySelector('.subtitle').textContent = 'Tu suscripción ha sido activada exitosamente';
        }

        if (sessionId) {
            fetch(`/session?session_id=${sessionId}`)
                .then(response => {
                    if (!response.ok) throw new Error('No se pudo obtener la información del pago');
                    return response.json();
                })
                .then(data => {
                    paymentInfoDiv.innerHTML = `
                        <div class="info-row">
                            <span class="info-label">Estado:</span>
                            <span class="info-value" style="color: #38a169;">✓ ${data.payment_status === 'paid' ? 'Pagado' : data.payment_status}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Monto:</span>
                            <span class="info-value">$${data.amount_total.toFixed(2)} ${data.currency}</span>
                        </div>
                        ${data.customer_email ? `
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">${data.customer_email}</span>
                        </div>
                        ` : ''}
                        ${data.customer_name ? `
                        <div class="info-row">
                            <span class="info-label">Nombre:</span>
                            <span class="info-value">${data.customer_name}</span>
                        </div>
                        ` : ''}
                        <div class="info-row">
                            <span class="info-label">ID de Sesión:</span>
                            <span class="info-value" style="font-size: 12px; word-break: break-all;">${data.session_id}</span>
                        </div>
                    `;
                })
                .catch(error => {
                    paymentInfoDiv.innerHTML = `
                        <div class="error">⚠️ ${error.message}</div>
                        <div class="info-row">
                            <span class="info-label">ID de Sesión:</span>
                            <span class="info-value" style="font-size: 12px; word-break: break-all;">${sessionId}</span>
                        </div>
                    `;
                });
        } else {
            paymentInfoDiv.innerHTML = `
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value" style="color: #38a169;">✓ Pago Completado</span>
                </div>
                <div style="color: #718096; font-size: 14px; margin-top: 10px;">
                    No se encontró información detallada del pago.
                </div>
            `;
        }
    </script>
</body>
</html>