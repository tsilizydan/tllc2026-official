<?php
/**
 * TSILIZY LLC — Admin Contract Form
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau contrat' : 'Modifier le contrat']);
$c = $contract ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/contrats" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau contrat' : 'Modifier le contrat' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/contrats' : SITE_URL . '/admin/contrats/' . $c['id'] ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($c['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                    <textarea name="description" rows="6" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($c['description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Dates</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Date de début</label>
                        <input type="date" name="start_date" value="<?= !empty($c['start_date']) ? date('Y-m-d', strtotime($c['start_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Date de fin</label>
                        <input type="date" name="end_date" value="<?= !empty($c['end_date']) ? date('Y-m-d', strtotime($c['end_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Client</label>
                    <select name="user_id" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= ($c['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>><?= Core\View::e($user['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Valeur (€)</label>
                    <input type="number" name="value" step="0.01" value="<?= $c['value'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($c['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="pending" <?= ($c['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="active" <?= ($c['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="completed" <?= ($c['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Terminé</option>
                        <option value="cancelled" <?= ($c['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                    </select>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>
