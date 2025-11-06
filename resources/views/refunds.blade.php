<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reembolsos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        h1 { color: #2d3748; font-size: 28px; margin-bottom: 10px; text-align: center; }
        .subtitle { color: #718096; text-align: center; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
        }
        input:focus, select:focus { outline: none; border-color: #f5576c; }
        .help-text { color: #718096; font-size: 14px; margin-top: 5px; }

        .button {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .button:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(245, 87, 108, 0.4); }
        .button:disabled { opacity: 0.6; cursor: not-allowed; }

        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        .result.success { background: #f0fdf4; border: 2px solid #86efac; color: #166534; }
        .result.error { background: #fef2f2; border: 2px solid #fca5a5; color: #991b1b; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #f5576c;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💸 Gestionar Reembolsos</h1>
        <p class="subtitle">Procesa reembolsos completos o parciales</p>

        <form id="refundForm">
            <div class="form-group">
                <label for="payment_intent">Payment Intent ID *</label>
                <input type="text" id="payment_intent" placeholder="pi_3xxxxxxxxxxxxxxxxxxxxxx" required>
                <div class="help-text">El ID comienza con "pi_"</div>
            </div>

            <div class="form-group">
                <label for="amount">Monto (opcional)</label>
                <input type="number" id="amount" placeholder="Deja vacío para reembolso completo" min="0" step="0.01">
                <div class="help-text">En pesos (MXN). Si está vacío, se reembolsará el monto completo.</div>
            </div>

            <div class="form-group">
                <label for="reason">Motivo del Reembolso</label>
                <select id="reason">
                    <option value="requested_by_customer">Solicitado por el cliente</option>
                    <option value="duplicate">Pago duplicado</option>
                    <option value="fraudulent">Fraudulento</option>
                </select>
            </div>

            <button type="submit" class="button" id="submitBtn">Procesar Reembolso</button>
        </form>

        <div class="result" id="result"></div>

        <a href="/index2" class="back-link">← Volver a la Panadería</a>
    </div>

    <script>
        document.getElementById('refundForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const resultDiv = document.getElementById('result');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Procesando...';
            resultDiv.style.display = 'none';

            const paymentIntent = document.getElementById('payment_intent').value;
            const amount = document.getElementById('amount').value;
            const reason = document.getElementById('reason').value;

            let url = `/refund?payment_intent=${paymentIntent}&reason=${reason}`;
            if (amount) {
                url += `&amount=${Math.round(parseFloat(amount) * 100)}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (response.ok && data.success) {
                    resultDiv.className = 'result success';
                    resultDiv.innerHTML = `
                        <div style="font-weight: 600; font-size: 18px; margin-bottom: 10px;">✅ Reembolso Procesado Exitosamente</div>
                        <div><strong>ID de Reembolso:</strong> ${data.refund_id}</div>
                        <div><strong>Estado:</strong> ${data.status}</div>
                        <div><strong>Monto:</strong> $${data.amount.toFixed(2)} MXN</div>
                    `;
                    e.target.reset();
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.innerHTML = `
                        <div style="font-weight: 600; font-size: 18px; margin-bottom: 10px;">❌ Error al Procesar Reembolso</div>
                        <div>${data.error || 'Ocurrió un error desconocido'}</div>
                    `;
                }

                resultDiv.style.display = 'block';
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.innerHTML = `<div>❌ Error de Conexión: ${error.message}</div>`;
                resultDiv.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Procesar Reembolso';
            }
        });
    </script>
</body>
</html>
