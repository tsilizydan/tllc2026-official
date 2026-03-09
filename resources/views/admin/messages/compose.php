<?php
/**
 * TSILIZY LLC — Admin Compose Message View
 */

Core\View::layout('admin', ['page_title' => 'Nouveau message']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/messages" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2">Nouveau message</h2>
    </div>
</div>

<form action="<?= SITE_URL ?>/admin/messages/envoyer" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="max-w-2xl space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <!-- Broadcast option -->
            <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg" x-data="{ broadcast: false }">
                <div>
                    <p class="text-white font-medium">Diffuser à tous</p>
                    <p class="text-dark-400 text-sm">Envoyer ce message à tous les utilisateurs</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="broadcast" value="1" class="sr-only peer" x-model="broadcast">
                    <div class="w-11 h-6 bg-dark-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-gold-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </label>
            </div>
            
            <!-- Recipient -->
            <div x-data="{ broadcast: false }" x-show="!$el.closest('form').querySelector('[name=broadcast]').checked">
                <label class="block text-sm font-medium text-dark-300 mb-2">Destinataire <span class="text-red-400">*</span></label>
                <select name="recipient_id" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="">Sélectionner un utilisateur...</option>
                    <?php foreach ($users ?? [] as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= Core\View::e($u['first_name'] . ' ' . $u['last_name']) ?> (<?= $u['email'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Sujet <span class="text-red-400">*</span></label>
                <input type="text" name="subject" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" placeholder="Objet du message...">
            </div>
            
            <!-- Content -->
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Message <span class="text-red-400">*</span></label>
                <textarea name="content" rows="8" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" placeholder="Votre message..."></textarea>
            </div>
            
            <div class="pt-4 border-t border-dark-600">
                <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors"><i class="fas fa-paper-plane mr-2"></i> Envoyer</button>
            </div>
        </div>
    </div>
</form>
