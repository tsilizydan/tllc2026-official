<?php
/**
 * TSILIZY LLC — Admin Career Form
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouvelle offre' : 'Modifier l\'offre']);
$j = $job ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/carrieres" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouvelle offre d\'emploi' : 'Modifier l\'offre' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/carrieres' : SITE_URL . '/admin/carrieres/' . $j['id'] ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre du poste <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($j['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" id="editor1" rows="8" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= $j['description'] ?? '' ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Exigences</label>
                    <textarea name="requirements" id="editor2" rows="6" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= $j['requirements'] ?? '' ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Avantages</label>
                    <textarea name="benefits" id="editor3" rows="4" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= $j['benefits'] ?? '' ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Rémunération</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Salaire min</label>
                        <input type="number" name="salary_min" value="<?= $j['salary_min'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Salaire max</label>
                        <input type="number" name="salary_max" value="<?= $j['salary_max'] ?? '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Devise</label>
                        <select name="salary_currency" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                            <option value="EUR" <?= ($j['salary_currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>EUR</option>
                            <option value="USD" <?= ($j['salary_currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-white">Publication</h3>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="open" <?= ($j['status'] ?? 'open') === 'open' ? 'selected' : '' ?>>Ouvert</option>
                        <option value="closed" <?= ($j['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Fermé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Département</label>
                    <input type="text" name="department" value="<?= Core\View::e($j['department'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Type de contrat</label>
                    <select name="employment_type" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="full_time" <?= ($j['employment_type'] ?? '') === 'full_time' ? 'selected' : '' ?>>Temps plein</option>
                        <option value="part_time" <?= ($j['employment_type'] ?? '') === 'part_time' ? 'selected' : '' ?>>Temps partiel</option>
                        <option value="contract" <?= ($j['employment_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contrat</option>
                        <option value="internship" <?= ($j['employment_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Stage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Lieu</label>
                    <input type="text" name="location" value="<?= Core\View::e($j['location'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_remote" name="is_remote" value="1" <?= ($j['is_remote'] ?? false) ? 'checked' : '' ?>>
                    <label for="is_remote" class="text-sm text-dark-300">Télétravail possible</label>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= ($j['is_featured'] ?? false) ? 'checked' : '' ?>>
                    <label for="is_featured" class="text-sm text-dark-300">En vedette</label>
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
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