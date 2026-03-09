<?php
/**
 * TSILIZY LLC — Admin Agenda Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau rendez-vous' : 'Modifier le rendez-vous']);
$apt = $appointment ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/agenda" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau rendez-vous' : 'Modifier le rendez-vous' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/agenda' : SITE_URL . '/admin/agenda/' . $apt['id'] ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="max-w-2xl space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                <input type="text" name="title" required value="<?= Core\View::e($apt['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($apt['description'] ?? '') ?></textarea>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Début <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="start_date" required value="<?= !empty($apt['start_date']) ? date('Y-m-d\TH:i', strtotime($apt['start_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Fin</label>
                    <input type="datetime-local" name="end_date" value="<?= !empty($apt['end_date']) ? date('Y-m-d\TH:i', strtotime($apt['end_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Client</label>
                    <select name="user_id" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($apt['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>><?= Core\View::e($u['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Lieu</label>
                    <input type="text" name="location" value="<?= Core\View::e($apt['location'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="scheduled" <?= ($apt['status'] ?? 'scheduled') === 'scheduled' ? 'selected' : '' ?>>Planifié</option>
                    <option value="confirmed" <?= ($apt['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                    <option value="completed" <?= ($apt['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Terminé</option>
                    <option value="cancelled" <?= ($apt['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>
            
            <div class="pt-4 border-t border-dark-600">
                <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
            </div>
        </div>
    </div>
</form>
