<?php
/**
 * TSILIZY LLC — Admin Surveys Index View
 */

Core\View::layout('admin', ['page_title' => 'Sondages']);
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-white">Sondages</h2>
    <a href="<?= SITE_URL ?>/admin/sondages/creer" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouveau</a>
</div>

<?php if (!empty($surveys)): ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($surveys as $s): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 hover:border-gold-500/50 transition-colors">
        <div class="flex items-start justify-between mb-4">
            <h3 class="font-semibold text-white"><?= Core\View::e($s['title']) ?></h3>
            <span class="px-2 py-1 text-xs rounded-full <?= $s['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-300' ?>">
                <?= $s['status'] === 'active' ? 'Actif' : 'Brouillon' ?>
            </span>
        </div>
        <p class="text-dark-400 text-sm mb-4 line-clamp-2"><?= Core\View::e($s['description'] ?? '') ?></p>
        <div class="flex items-center justify-between text-sm">
            <span class="text-gold-500"><i class="fas fa-chart-bar mr-1"></i> <?= $s['responses_count'] ?> réponses</span>
            <span class="text-dark-500"><?= Core\View::date($s['created_at']) ?></span>
        </div>
        <div class="flex space-x-2 mt-4 pt-4 border-t border-dark-700">
            <a href="<?= SITE_URL ?>/admin/sondages/<?= $s['id'] ?>/resultats" class="flex-1 text-center py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30"><i class="fas fa-chart-pie"></i></a>
            <a href="<?= SITE_URL ?>/admin/sondages/<?= $s['id'] ?>/modifier" class="flex-1 text-center py-2 bg-gold-500/20 text-gold-500 rounded-lg hover:bg-gold-500/30"><i class="fas fa-edit"></i></a>
            <form action="<?= SITE_URL ?>/admin/sondages/<?= $s['id'] ?>/supprimer" method="POST" class="flex-1" onsubmit="return confirm('Supprimer ?')">
                <?= Core\CSRF::field() ?><button type="submit" class="w-full py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-poll text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun sondage créé.</p>
</div>
<?php endif; ?>
