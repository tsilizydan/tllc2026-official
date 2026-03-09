<?php
/**
 * TSILIZY LLC — Welcome Email Template
 */
?>
<h1>Bienvenue, <?= htmlspecialchars($name) ?> !</h1>

<p>Nous sommes ravis de vous compter parmi nous.</p>

<p>Votre compte a été créé avec succès. Vous pouvez désormais accéder à tous nos services et gérer votre espace personnel.</p>

<div class="divider"></div>

<p><strong>Que pouvez-vous faire maintenant ?</strong></p>
<ul style="margin: 16px 0; padding-left: 20px;">
    <li style="margin-bottom: 8px;">Consulter nos services</li>
    <li style="margin-bottom: 8px;">Parcourir notre portfolio</li>
    <li style="margin-bottom: 8px;">Créer des tickets de support</li>
    <li style="margin-bottom: 8px;">Gérer votre profil</li>
</ul>

<p style="text-align: center;">
    <a href="<?= SITE_URL ?>/mon-compte" class="button">Accéder à mon compte</a>
</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #64748B;">
    Si vous n'êtes pas à l'origine de cette inscription, veuillez ignorer cet email.
</p>
