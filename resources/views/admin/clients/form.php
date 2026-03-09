<?php
/**
 * TSILIZY LLC — Admin Client Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau client' : 'Modifier le client']);
$c = $client ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/clients" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau client' : 'Modifier le client' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/clients' : SITE_URL . '/admin/clients/' . ($c['id'] ?? '') ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="max-w-3xl space-y-6">
        <!-- Personal Info -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <h3 class="text-lg font-semibold text-white">Informations personnelles</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Prénom <span class="text-red-400">*</span></label>
                    <input type="text" name="first_name" required value="<?= Core\View::e($c['first_name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Nom</label>
                    <input type="text" name="last_name" value="<?= Core\View::e($c['last_name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required value="<?= Core\View::e($c['email'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Téléphone</label>
                    <input type="tel" name="phone" value="<?= Core\View::e($c['phone'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
        </div>
        
        <!-- Company -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <h3 class="text-lg font-semibold text-white">Entreprise</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Nom de l'entreprise</label>
                    <input type="text" name="company" value="<?= Core\View::e($c['company'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">N° TVA</label>
                    <input type="text" name="vat_number" value="<?= Core\View::e($c['vat_number'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
        </div>
        
        <!-- Address -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <h3 class="text-lg font-semibold text-white">Adresse</h3>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Adresse</label>
                <input type="text" name="address" value="<?= Core\View::e($c['address'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Ville</label>
                    <input type="text" name="city" value="<?= Core\View::e($c['city'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Code postal</label>
                    <input type="text" name="postal_code" value="<?= Core\View::e($c['postal_code'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Pays</label>
                    <input type="text" name="country" value="<?= Core\View::e($c['country'] ?? 'France') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
        </div>
        
        <!-- Notes & Status -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <?php if ($mode === 'edit'): ?>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="active" <?= ($c['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($c['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Notes internes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($c['notes'] ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors"><i class="fas fa-save mr-2"></i> Enregistrer</button>
        </div>
    </div>
</form>
