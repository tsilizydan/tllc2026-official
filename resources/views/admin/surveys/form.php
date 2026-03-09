<?php
/**
 * TSILIZY LLC — Admin Survey Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau sondage' : 'Modifier le sondage']);
$s = $survey ?? [];
$questions = $s['questions'] ?? [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/sondages" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau sondage' : 'Modifier le sondage' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/sondages' : SITE_URL . '/admin/sondages/' . $s['id'] ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required value="<?= Core\View::e($s['title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($s['description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Questions</h3>
                    <button type="button" onclick="addQuestion()" class="text-gold-500 hover:text-gold-400"><i class="fas fa-plus mr-1"></i> Ajouter</button>
                </div>
                <div id="questions-container" class="space-y-4">
                    <?php foreach ($questions as $i => $q): ?>
                    <div class="question-item bg-dark-700 rounded-lg p-4" data-index="<?= $i ?>">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <input type="text" name="questions[<?= $i ?>][text]" value="<?= Core\View::e($q['text'] ?? '') ?>" placeholder="Question..." class="flex-1 px-3 py-2 bg-dark-800 border border-dark-600 rounded-lg text-white text-sm">
                            <button type="button" onclick="removeQuestion(this)" class="text-red-400 hover:text-red-300"><i class="fas fa-times"></i></button>
                        </div>
                        <select name="questions[<?= $i ?>][type]" class="px-3 py-2 bg-dark-800 border border-dark-600 rounded-lg text-white text-sm">
                            <option value="text" <?= ($q['type'] ?? '') === 'text' ? 'selected' : '' ?>>Texte libre</option>
                            <option value="choice" <?= ($q['type'] ?? '') === 'choice' ? 'selected' : '' ?>>Choix unique</option>
                            <option value="multiple" <?= ($q['type'] ?? '') === 'multiple' ? 'selected' : '' ?>>Choix multiples</option>
                            <option value="rating" <?= ($q['type'] ?? '') === 'rating' ? 'selected' : '' ?>>Note (1-5)</option>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($s['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="active" <?= ($s['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="closed" <?= ($s['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Fermé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Date de début</label>
                    <input type="datetime-local" name="starts_at" value="<?= !empty($s['starts_at']) ? date('Y-m-d\TH:i', strtotime($s['starts_at'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Date de fin</label>
                    <input type="datetime-local" name="ends_at" value="<?= !empty($s['ends_at']) ? date('Y-m-d\TH:i', strtotime($s['ends_at'])) : '' ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                </div>
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let questionIndex = <?= count($questions) ?>;
function addQuestion() {
    const container = document.getElementById('questions-container');
    const html = `<div class="question-item bg-dark-700 rounded-lg p-4" data-index="${questionIndex}">
        <div class="flex items-start justify-between gap-4 mb-3">
            <input type="text" name="questions[${questionIndex}][text]" placeholder="Question..." class="flex-1 px-3 py-2 bg-dark-800 border border-dark-600 rounded-lg text-white text-sm">
            <button type="button" onclick="removeQuestion(this)" class="text-red-400 hover:text-red-300"><i class="fas fa-times"></i></button>
        </div>
        <select name="questions[${questionIndex}][type]" class="px-3 py-2 bg-dark-800 border border-dark-600 rounded-lg text-white text-sm">
            <option value="text">Texte libre</option>
            <option value="choice">Choix unique</option>
            <option value="multiple">Choix multiples</option>
            <option value="rating">Note (1-5)</option>
        </select>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    questionIndex++;
}
function removeQuestion(btn) { btn.closest('.question-item').remove(); }
</script>
