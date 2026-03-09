<?php
/**
 * TSILIZY LLC — Ticket Created Email Template
 */
?>
<h1>Ticket créé</h1>

<p>Bonjour <?= htmlspecialchars($name) ?>,</p>

<p>Votre ticket de support a été créé avec succès.</p>

<div style="background: #0F172A; border-left: 4px solid #C9A227; padding: 16px; margin: 24px 0; border-radius: 4px;">
    <p style="margin: 0; font-size: 14px; color: #64748B;">Référence du ticket</p>
    <p style="margin: 4px 0 0 0; font-size: 20px; font-weight: bold; color: #C9A227; font-family: monospace;">#<?= htmlspecialchars($reference) ?></p>
</div>

<p>Notre équipe de support examinera votre demande et vous répondra dans les meilleurs délais.</p>

<p style="text-align: center;">
    <a href="<?= SITE_URL ?>/mon-compte/tickets" class="button">Suivre mon ticket</a>
</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #64748B;">
    Vous recevrez une notification par email à chaque nouvelle réponse sur votre ticket.
</p>
