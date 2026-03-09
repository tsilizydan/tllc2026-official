<?php
/**
 * TSILIZY LLC — Admin Notification Form View
 */

Core\View::layout('admin', ['page_title' => 'Envoyer une notification']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/notifications" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2">Envoyer une notification</h2>
    </div>
</div>

<form action="<?= SITE_URL ?>/admin/notifications" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="max-w-2xl space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Destinataire</label>
                <select name="user_id" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="">Tous les utilisateurs (broadcast)</option>
                    <?php foreach ($users ?? [] as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= Core\View::e($user['first_name'] . ' ' . $user['last_name']) ?> (<?= $user['email'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="info">Information</option>
                    <option value="success">Succès</option>
                    <option value="warning">Avertissement</option>
                    <option value="error">Erreur</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                <input type="text" name="title" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" placeholder="Titre de la notification...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Message</label>
                <textarea name="message" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" placeholder="Contenu (optionnel)..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Lien (optionnel)</label>
                <input type="url" name="link" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" placeholder="https://...">
            </div>
            
            <div class="pt-4 border-t border-dark-600">
                <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors"><i class="fas fa-paper-plane mr-2"></i> Envoyer</button>
            </div>
        </div>
    </div>
</form>
