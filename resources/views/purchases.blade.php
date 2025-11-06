<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Historial de Compras - Panadería Dulce</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .header h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .back-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .purchases-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        }

        .purchase-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .purchase-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .purchase-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .purchase-id {
            font-size: 0.9rem;
            color: #999;
            font-weight: 600;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-refunded {
            background: #ffebee;
            color: #c62828;
        }

        .status-partial_refund {
            background: #fff3e0;
            color: #ef6c00;
        }

        .purchase-info {
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            color: #666;
            font-weight: 600;
        }

        .info-value {
            color: #333;
            font-weight: 700;
        }

        .amount {
            font-size: 1.5rem;
            color: #667eea;
        }

        .products-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .products-list h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .product-item {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .refund-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .refund-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .refund-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .refund-info {
            background: #fff3cd;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            border-left: 4px solid #ffc107;
        }

        .refund-info strong {
            color: #856404;
        }

        .refund-info p {
            color: #856404;
            margin-top: 5px;
            font-size: 0.9rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            color: #667eea;
            font-size: 1.8rem;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 2rem;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-button:hover {
            color: #f44336;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s ease;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #666;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .empty-state h2 {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .purchases-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }

            .modal-content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ Historial de Compras</h1>
            <p>Todas tus compras y opciones de reembolso</p>
            <a href="/index2" class="back-button">← Volver a la tienda</a>
        </div>

        @if($purchases->isEmpty())
            <div class="empty-state">
                <h2>📦 No hay compras aún</h2>
                <p>Cuando realices una compra, aparecerá aquí con la opción de reembolso.</p>
                <a href="/index2" class="back-button">Ir de compras</a>
            </div>
        @else
            <div class="purchases-grid">
                @foreach($purchases as $purchase)
                <div class="purchase-card">
                    <div class="purchase-header">
                        <div class="purchase-id">#{{ $purchase->id }}</div>
                        <div class="status-badge status-{{ $purchase->status }}">
                            @if($purchase->status === 'completed') ✅ Completado
                            @elseif($purchase->status === 'refunded') 🔄 Reembolsado
                            @else 🔄 Parcial
                            @endif
                        </div>
                    </div>

                    <div class="purchase-info">
                        <div class="info-row">
                            <span class="info-label">Cliente:</span>
                            <span class="info-value">{{ $purchase->customer_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $purchase->customer_email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Fecha:</span>
                            <span class="info-value">{{ $purchase->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total:</span>
                            <span class="info-value amount">${{ number_format($purchase->amount / 100, 2) }} {{ strtoupper($purchase->currency) }}</span>
                        </div>
                    </div>

                    <div class="products-list">
                        <h4>📦 Productos comprados:</h4>
                        @foreach($purchase->products as $product)
                        <div class="product-item">
                            <span>{{ $product['name'] ?? 'Producto' }} (x{{ $product['quantity'] }})</span>
                            <strong>${{ number_format($product['amount'] / 100, 2) }}</strong>
                        </div>
                        @endforeach
                    </div>

                    @if($purchase->status === 'completed')
                        @if($purchase->payment_intent_id)
                            <button 
                                class="refund-button" 
                                data-purchase-id="{{ $purchase->id }}"
                                data-payment-intent="{{ $purchase->payment_intent_id }}"
                                data-amount="{{ $purchase->amount }}"
                                onclick="openRefundModal(this.dataset.purchaseId, this.dataset.paymentIntent, this.dataset.amount)"
                            >
                                🔄 Solicitar Reembolso
                            </button>
                        @else
                            <div class="refund-info">
                                <strong>⚠️ No disponible</strong>
                                <p>Esta compra no tiene información de pago para reembolsar.</p>
                            </div>
                        @endif
                    @elseif($purchase->status === 'refunded')
                        <div class="refund-info">
                            <strong>✅ Reembolso procesado</strong>
                            @if($purchase->refund_reason)
                                <p><strong>Motivo:</strong> {{ $purchase->refund_reason }}</p>
                            @endif
                            <p><strong>Fecha:</strong> {{ $purchase->refunded_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal de Reembolso -->
    <div class="modal" id="refundModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🔄 Solicitar Reembolso</h2>
                <button class="close-button" onclick="closeRefundModal()">&times;</button>
            </div>

            <form id="refundForm">
                <input type="hidden" id="purchaseId">
                <input type="hidden" id="paymentIntentId">
                <input type="hidden" id="amount">

                <div class="form-group">
                    <label for="refundReason">¿Por qué deseas solicitar un reembolso? *</label>
                    <textarea 
                        id="refundReason" 
                        name="refund_reason" 
                        placeholder="Por favor, explica el motivo de tu solicitud de reembolso..."
                        required
                    ></textarea>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeRefundModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Confirmar Reembolso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRefundModal(purchaseId, paymentIntentId, amount) {
            document.getElementById('purchaseId').value = purchaseId;
            document.getElementById('paymentIntentId').value = paymentIntentId;
            document.getElementById('amount').value = amount;
            document.getElementById('refundModal').classList.add('active');
        }

        function closeRefundModal() {
            document.getElementById('refundModal').classList.remove('active');
            document.getElementById('refundForm').reset();
        }

        document.getElementById('refundForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const purchaseId = document.getElementById('purchaseId').value;
            const paymentIntentId = document.getElementById('paymentIntentId').value;
            const refundReason = document.getElementById('refundReason').value;

            if (!refundReason.trim()) {
                alert('Por favor, proporciona un motivo para el reembolso');
                return;
            }

            const submitButton = e.target.querySelector('.btn-primary');
            submitButton.disabled = true;
            submitButton.textContent = 'Procesando...';

            try {
                const response = await fetch('/refund-purchase', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        purchase_id: purchaseId,
                        payment_intent_id: paymentIntentId,
                        refund_reason: refundReason
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('✅ ' + data.message);
                    closeRefundModal();
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.error || 'No se pudo procesar el reembolso'));
                    submitButton.disabled = false;
                    submitButton.textContent = 'Confirmar Reembolso';
                }
            } catch (error) {
                alert('❌ Error de conexión: ' + error.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Confirmar Reembolso';
            }
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('refundModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRefundModal();
            }
        });
    </script>
</body>
</html>
