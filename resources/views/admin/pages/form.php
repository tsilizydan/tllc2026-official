<?php
/**
 * TSILIZY LLC — Admin Page Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouvelle page' : 'Modifier la page']);
$p = $page ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/pages" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouvelle page' : 'Modifier la page' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/pages' : SITE_URL . '/admin/pages/' . ($p['id'] ?? '') ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($p['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Slug</label>
                    <input type="text" name="slug" value="<?= Core\View::e($p['slug'] ?? '') ?>" placeholder="Généré automatiquement" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white font-mono text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Contenu</label>
                    <textarea name="content" rows="15" class="tinymce w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($p['content'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Extrait</label>
                    <textarea name="excerpt" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($p['excerpt'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">SEO</h3>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre SEO</label>
                    <input type="text" name="seo_title" value="<?= Core\View::e($p['seo_title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description SEO</label>
                    <textarea name="seo_description" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($p['seo_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($p['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= ($p['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
                        <option value="archived" <?= ($p['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archivé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Template</label>
                    <select name="template" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="default" <?= ($p['template'] ?? 'default') === 'default' ? 'selected' : '' ?>>Par défaut</option>
                        <option value="full-width" <?= ($p['template'] ?? '') === 'full-width' ? 'selected' : '' ?>>Pleine largeur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Ordre</label>
                    <input type="number" name="order_index" value="<?= $p['order_index'] ?? 0 ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>
