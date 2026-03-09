<?php
/**
 * TSILIZY LLC — Invoice Email Template
 */
$inv = $invoice;
?>
<h1>Nouvelle facture</h1>

<p>Bonjour <?= htmlspecialchars($name) ?>,</p>

<p>Veuillez trouver ci-dessous les détails de votre facture :</p>

<div style="background: #0F172A; padding: 24px; margin: 24px 0; border-radius: 8px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #64748B;">Référence</td>
            <td style="padding: 8px 0; text-align: right; color: #C9A227; font-weight: bold; font-family: monospace;"><?= htmlspecialchars($inv['reference']) ?></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #64748B;">Date d'émission</td>
            <td style="padding: 8px 0; text-align: right; color: #FFFFFF;"><?= date('d/m/Y', strtotime($inv['created_at'])) ?></td>
        </tr>
        <?php if ($inv['due_date']): ?>
        <tr>
            <td style="padding: 8px 0; color: #64748B;">Date d'échéance</td>
            <td style="padding: 8px 0; text-align: right; color: #FFFFFF;"><?= date('d/m/Y', strtotime($inv['due_date'])) ?></td>
        </tr>
        <?php endif; ?>
        <tr style="border-top: 1px solid #334155;">
            <td style="padding: 16px 0 8px 0; color: #64748B;">Sous-total</td>
            <td style="padding: 16px 0 8px 0; text-align: right; color: #FFFFFF;"><?= number_format($inv['subtotal'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php if ($inv['tax'] > 0): ?>
        <tr>
            <td style="padding: 8px 0; color: #64748B;">TVA</td>
            <td style="padding: 8px 0; text-align: right; color: #FFFFFF;"><?= number_format($inv['tax'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php endif; ?>
        <?php if ($inv['discount'] > 0): ?>
        <tr>
            <td style="padding: 8px 0; color: #64748B;">Remise</td>
            <td style="padding: 8px 0; text-align: right; color: #22C55E;">-<?= number_format($inv['discount'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php endif; ?>
        <tr style="border-top: 2px solid #C9A227;">
            <td style="padding: 16px 0 0 0; font-size: 18px; font-weight: bold; color: #FFFFFF;">Total</td>
            <td style="padding: 16px 0 0 0; text-align: right; font-size: 24px; font-weight: bold; color: #C9A227;"><?= number_format($inv['total'], 2, ',', ' ') ?> €</td>
        </tr>
    </table>
</div>

<p style="text-align: center;">
    <a href="<?= SITE_URL ?>/mon-compte/factures/<?= $inv['id'] ?>" class="button">Voir la facture</a>
</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #64748B;">
    Pour toute question concernant cette facture, n'hésitez pas à nous contacter.
</p>
