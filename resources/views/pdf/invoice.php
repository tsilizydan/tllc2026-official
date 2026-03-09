<?php
/**
 * TSILIZY LLC — Invoice PDF Template
 */

$inv = $invoice;
$siteName = SITE_NAME ?? 'TSILIZY LLC';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: white; color: #1E293B; line-height: 1.5; font-size: 14px; }
        .invoice { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 3px solid #C9A227; padding-bottom: 30px; }
        .logo { font-size: 32px; font-weight: bold; color: #C9A227; letter-spacing: 2px; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 32px; color: #0F172A; margin-bottom: 8px; }
        .invoice-title .ref { font-size: 18px; color: #64748B; font-family: monospace; }
        .parties { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .party { width: 48%; }
        .party h3 { color: #C9A227; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .party p { margin-bottom: 4px; color: #475569; }
        .party .name { font-size: 18px; font-weight: bold; color: #0F172A; margin-bottom: 8px; }
        .meta { background: #F8FAFC; border-radius: 8px; padding: 20px; margin-bottom: 40px; display: flex; justify-content: space-around; }
        .meta-item { text-align: center; }
        .meta-item label { display: block; font-size: 12px; color: #64748B; text-transform: uppercase; margin-bottom: 4px; }
        .meta-item span { font-size: 16px; font-weight: bold; color: #0F172A; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead th { background: #0F172A; color: white; padding: 12px 16px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 16px; border-bottom: 1px solid #E2E8F0; }
        tbody td:last-child { text-align: right; }
        .totals { width: 300px; margin-left: auto; }
        .totals table { margin-bottom: 0; }
        .totals td { padding: 8px 0; border: none; }
        .totals .total-row td { font-size: 20px; font-weight: bold; color: #0F172A; border-top: 2px solid #C9A227; padding-top: 16px; }
        .totals .total-row td:last-child { color: #C9A227; }
        .notes { background: #FFFBEB; border-left: 4px solid #C9A227; padding: 16px; margin-top: 40px; font-size: 13px; color: #78716C; }
        .footer { margin-top: 60px; text-align: center; padding-top: 20px; border-top: 1px solid #E2E8F0; color: #94A3B8; font-size: 12px; }
        @media print { body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="logo"><?= htmlspecialchars($siteName) ?></div>
            <div class="invoice-title">
                <h1>FACTURE</h1>
                <div class="ref"><?= htmlspecialchars($inv['reference']) ?></div>
            </div>
        </div>
        
        <div class="parties">
            <div class="party">
                <h3>De</h3>
                <p class="name"><?= htmlspecialchars($siteName) ?></p>
                <p><?= SITE_ADDRESS ?? '123 Rue Example' ?></p>
                <p><?= SITE_PHONE ?? '+33 1 23 45 67 89' ?></p>
                <p><?= SITE_EMAIL ?? 'contact@tsilizy.com' ?></p>
            </div>
            <div class="party">
                <h3>À</h3>
                <p class="name"><?= htmlspecialchars($inv['user_name'] ?? 'Client') ?></p>
                <p><?= htmlspecialchars($inv['user_email'] ?? '') ?></p>
                <?php if ($inv['user_phone'] ?? null): ?>
                <p><?= htmlspecialchars($inv['user_phone']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="meta">
            <div class="meta-item">
                <label>Date d'émission</label>
                <span><?= date('d/m/Y', strtotime($inv['created_at'])) ?></span>
            </div>
            <div class="meta-item">
                <label>Échéance</label>
                <span><?= $inv['due_date'] ? date('d/m/Y', strtotime($inv['due_date'])) : '-' ?></span>
            </div>
            <div class="meta-item">
                <label>Statut</label>
                <span style="color: <?= $inv['status'] === 'paid' ? '#22C55E' : '#EAB308' ?>">
                    <?php echo match($inv['status']) { 'paid' => 'PAYÉE', 'sent' => 'ENVOYÉE', default => 'EN ATTENTE' }; ?>
                </span>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Prestation de services</td>
                    <td><?= number_format($inv['subtotal'], 2, ',', ' ') ?> €</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <td>Sous-total</td>
                    <td><?= number_format($inv['subtotal'], 2, ',', ' ') ?> €</td>
                </tr>
                <?php if ($inv['tax'] > 0): ?>
                <tr>
                    <td>TVA (20%)</td>
                    <td><?= number_format($inv['tax'], 2, ',', ' ') ?> €</td>
                </tr>
                <?php endif; ?>
                <?php if ($inv['discount'] > 0): ?>
                <tr>
                    <td>Remise</td>
                    <td style="color: #22C55E;">-<?= number_format($inv['discount'], 2, ',', ' ') ?> €</td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>Total TTC</td>
                    <td><?= number_format($inv['total'], 2, ',', ' ') ?> €</td>
                </tr>
            </table>
        </div>
        
        <?php if ($inv['notes']): ?>
        <div class="notes">
            <strong>Notes :</strong><br>
            <?= nl2br(htmlspecialchars($inv['notes'])) ?>
        </div>
        <?php endif; ?>
        
        <div class="footer">
            <p><?= htmlspecialchars($siteName) ?> — Facture générée le <?= date('d/m/Y à H:i') ?></p>
        </div>
    </div>
</body>
</html>
