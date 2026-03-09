<?php
/**
 * TSILIZY LLC — Admin Partner Form
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau partenaire' : 'Modifier le partenaire']);
$p = $partner ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/partenaires" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau partenaire' : 'Modifier le partenaire' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/partenaires' : SITE_URL . '/admin/partenaires/' . $p['id'] ?>" method="POST" enctype="multipart/form-data">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Nom <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required value="<?= Core\View::e($p['name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white resize-none"><?= Core\View::e($p['description'] ?? '') ?></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Site web</label>
                        <input type="url" name="website" value="<?= Core\View::e($p['website'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Catégorie</label>
                        <input type="text" name="category" value="<?= Core\View::e($p['category'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-white">Options</h3>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="active" <?= ($p['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="inactive" <?= ($p['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Ordre</label>
                    <input type="number" name="order_index" value="<?= $p['order_index'] ?? 0 ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= ($p['is_featured'] ?? false) ? 'checked' : '' ?>>
                    <label for="is_featured" class="text-sm text-dark-300">En vedette</label>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Logo</h3>
                <?php if ($p['logo'] ?? null): ?><img src="<?= Core\View::upload($p['logo']) ?>" class="w-full h-24 object-contain bg-white rounded-lg mb-4 p-2"><?php endif; ?>
                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dark-700 file:text-white">
            </div>
        </div>
    </div>
</form>
