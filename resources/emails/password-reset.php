<?php
/**
 * TSILIZY LLC — Password Reset Email Template
 */
?>
<h1>Réinitialisation de mot de passe</h1>

<p>Bonjour <?= htmlspecialchars($name) ?>,</p>

<p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>

<p style="text-align: center;">
    <a href="<?= htmlspecialchars($reset_url) ?>" class="button">Réinitialiser mon mot de passe</a>
</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #64748B;">
    Ce lien expire dans <span class="highlight">1 heure</span>.
</p>

<p style="font-size: 14px; color: #64748B;">
    Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email. Votre mot de passe restera inchangé.
</p>

<p style="font-size: 13px; color: #475569; margin-top: 24px;">
    Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
    <a href="<?= htmlspecialchars($reset_url) ?>" style="color: #C9A227; word-break: break-all;"><?= htmlspecialchars($reset_url) ?></a>
</p>
