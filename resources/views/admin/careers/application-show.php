<?php
/**
 * TSILIZY LLC — Admin Application Detail View
 */

Core\View::layout('admin', ['page_title' => 'Candidature']);
$app = $application;
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/candidatures" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2">Candidature de <?= Core\View::e($app['name']) ?></h2>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Informations</h3>
            <dl class="grid md:grid-cols-2 gap-4">
                <div><dt class="text-dark-400 text-sm">Nom</dt><dd class="text-white"><?= Core\View::e($app['name']) ?></dd></div>
                <div><dt class="text-dark-400 text-sm">Email</dt><dd class="text-white"><?= Core\View::e($app['email']) ?></dd></div>
                <div><dt class="text-dark-400 text-sm">Téléphone</dt><dd class="text-white"><?= Core\View::e($app['phone'] ?? '-') ?></dd></div>
                <div><dt class="text-dark-400 text-sm">Poste</dt><dd class="text-white"><?= Core\View::e($app['job_title'] ?? '-') ?></dd></div>
                <div><dt class="text-dark-400 text-sm">Date</dt><dd class="text-white"><?= Core\View::datetime($app['created_at']) ?></dd></div>
            </dl>
        </div>
        
        <?php if ($app['cover_letter']): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Lettre de motivation</h3>
            <div class="prose prose-invert max-w-none text-dark-300"><?= nl2br(Core\View::e($app['cover_letter'])) ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Statut</h3>
            <form action="<?= SITE_URL ?>/admin/candidatures/<?= $app['id'] ?>/statut" method="POST" class="space-y-4">
                <?= Core\CSRF::field() ?>
                <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    <option value="pending" <?= $app['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                    <option value="reviewing" <?= $app['status'] === 'reviewing' ? 'selected' : '' ?>>En cours d'examen</option>
                    <option value="accepted" <?= $app['status'] === 'accepted' ? 'selected' : '' ?>>Acceptée</option>
                    <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Refusée</option>
                </select>
                <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg">Mettre à jour</button>
            </form>
        </div>
        
        <?php if ($app['resume_path']): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">CV</h3>
            <a href="<?= Core\View::upload($app['resume_path']) ?>" target="_blank" class="flex items-center justify-center px-4 py-3 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30">
                <i class="fas fa-file-pdf mr-2"></i> Télécharger le CV
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
