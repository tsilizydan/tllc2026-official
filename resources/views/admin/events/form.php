<?php
/**
 * TSILIZY LLC — Admin Event Form
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouvel événement' : 'Modifier l\'événement']);
$e = $event ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/evenements" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouvel événement' : 'Modifier l\'événement' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/evenements' : SITE_URL . '/admin/evenements/' . $e['id'] ?>" method="POST" enctype="multipart/form-data">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($e['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description courte</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white resize-none"><?= Core\View::e($e['short_description'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" id="editor" rows="10" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= $e['description'] ?? '' ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Date & Lieu</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Date de début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="start_date" required value="<?= !empty($e['start_date']) ? date('Y-m-d\TH:i', strtotime($e['start_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Date de fin</label>
                        <input type="datetime-local" name="end_date" value="<?= !empty($e['end_date']) ? date('Y-m-d\TH:i', strtotime($e['end_date'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_online" name="is_online" value="1" <?= ($e['is_online'] ?? false) ? 'checked' : '' ?> class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500">
                    <label for="is_online" class="text-sm text-dark-300">Événement en ligne</label>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Lieu</label>
                        <input type="text" name="location" value="<?= Core\View::e($e['location'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Adresse</label>
                        <input type="text" name="address" value="<?= Core\View::e($e['address'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">URL de la réunion (si en ligne)</label>
                    <input type="url" name="meeting_url" value="<?= Core\View::e($e['meeting_url'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-white">Publication</h3>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($e['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= ($e['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Catégorie</label>
                    <input type="text" name="category" value="<?= Core\View::e($e['category'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Nombre max de participants</label>
                    <input type="number" name="max_attendees" value="<?= $e['max_attendees'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Prix (€)</label>
                    <input type="number" name="price" step="0.01" value="<?= $e['price'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= ($e['is_featured'] ?? false) ? 'checked' : '' ?> class="w-4 h-4 rounded">
                    <label for="is_featured" class="text-sm text-dark-300">En vedette</label>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Image</h3>
                <?php if ($e['image'] ?? null): ?><img src="<?= Core\View::upload($e['image']) ?>" class="w-full h-32 object-cover rounded-lg mb-4"><?php endif; ?>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dark-700 file:text-white">
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