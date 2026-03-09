<?php
/**
 * TSILIZY LLC — Admin Announcement Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouvelle annonce' : 'Modifier l\'annonce']);
$a = $announcement ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/annonces" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouvelle annonce' : 'Modifier l\'annonce' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/annonces' : SITE_URL . '/admin/annonces/' . ($a['id'] ?? '') ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($a['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Contenu</label>
                    <textarea name="content" rows="10" class="tinymce w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($a['content'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Extrait</label>
                    <textarea name="excerpt" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($a['excerpt'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($a['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= ($a['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
                        <option value="archived" <?= ($a['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archivé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Type</label>
                    <select name="type" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="info" <?= ($a['type'] ?? 'info') === 'info' ? 'selected' : '' ?>>Information</option>
                        <option value="success" <?= ($a['type'] ?? '') === 'success' ? 'selected' : '' ?>>Succès</option>
                        <option value="warning" <?= ($a['type'] ?? '') === 'warning' ? 'selected' : '' ?>>Avertissement</option>
                        <option value="urgent" <?= ($a['type'] ?? '') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_pinned" value="1" <?= ($a['is_pinned'] ?? 0) ? 'checked' : '' ?> class="w-4 h-4 rounded border-dark-500 text-gold-500 focus:ring-gold-500 bg-dark-900">
                        <span class="text-dark-300">Épingler en haut</span>
                    </label>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>
