<?php
/**
 * TSILIZY LLC — Contact Confirmation Email Template
 */
?>
<h1>Message bien reçu !</h1>

<p>Bonjour <?= htmlspecialchars($name) ?>,</p>

<p>Nous avons bien reçu votre message et vous remercions de nous avoir contactés.</p>

<p>Notre équipe examinera votre demande et vous répondra dans les plus brefs délais, généralement sous <span class="highlight">24-48 heures ouvrées</span>.</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #64748B;">
    En attendant, n'hésitez pas à consulter notre FAQ ou nos services sur notre site web.
</p>

<p style="text-align: center;">
    <a href="<?= SITE_URL ?>/services" class="button">Découvrir nos services</a>
</p>

<div class="divider"></div>

<p style="font-size: 13px; color: #475569;">
    <strong>Besoin d'une réponse urgente ?</strong><br>
    Contactez-nous directement par téléphone.
</p>
