<?php
/**
 * TSILIZY LLC — Admin Product Form
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau produit' : 'Modifier le produit']);
$p = $product ?? [];
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/produits" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau produit' : 'Modifier le produit' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/produits' : SITE_URL . '/admin/produits/' . $p['id'] ?>" method="POST" enctype="multipart/form-data">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($p['title'] ?? Core\Session::old('title')) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description courte</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white resize-none"><?= Core\View::e($p['short_description'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" id="editor" rows="10" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white" required><?= $p['description'] ?? '' ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Prix & stock</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Prix (€)</label>
                        <input type="number" name="price" step="0.01" value="<?= $p['price'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Prix promo</label>
                        <input type="number" name="sale_price" step="0.01" value="<?= $p['sale_price'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">SKU</label>
                        <input type="text" name="sku" value="<?= Core\View::e($p['sku'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Stock</label>
                        <input type="number" name="stock" value="<?= $p['stock'] ?? 0 ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
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
                    <textarea name="seo_description" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white resize-none"><?= Core\View::e($p['seo_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-white">Publication</h3>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="active" <?= ($p['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="draft" <?= ($p['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Catégorie</label>
                    <input type="text" name="category" value="<?= Core\View::e($p['category'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Ordre</label>
                    <input type="number" name="order_index" value="<?= $p['order_index'] ?? 0 ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= ($p['is_featured'] ?? false) ? 'checked' : '' ?> class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500">
                    <label for="is_featured" class="text-sm text-dark-300">Produit en vedette</label>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Image</h3>
                <?php if ($p['image'] ?? null): ?>
                <img src="<?= Core\View::upload($p['image']) ?>" class="w-full h-32 object-cover rounded-lg mb-4">
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-dark-700 file:text-white hover:file:bg-dark-600">
            </div>
        </div>
    </div>
</form>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/q96zzqz4inb66gof20x44rx2hi6vdo7x980dqw1vc0ymu3io/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>