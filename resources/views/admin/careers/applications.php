<?php
/**
 * TSILIZY LLC — Admin Applications View
 */

Core\View::layout('admin', ['page_title' => $page_title ?? 'Candidatures']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/carrieres" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour aux offres</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $job ? 'Candidatures - ' . Core\View::e($job['title']) : 'Toutes les candidatures' ?></h2>
    </div>
</div>

<?php if (!empty($applications)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Candidat</th>
                <?php if (!$job): ?><th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Poste</th><?php endif; ?>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Date</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($applications as $app): ?>
            <tr class="hover:bg-dark-700/50">
                <td class="px-6 py-4">
                    <div class="font-medium text-white"><?= Core\View::e($app['name']) ?></div>
                    <?php if ($app['phone']): ?><div class="text-sm text-dark-400"><?= Core\View::e($app['phone']) ?></div><?php endif; ?>
                </td>
                <?php if (!$job): ?>
                <td class="px-6 py-4 text-dark-300"><?= Core\View::e($app['job_title'] ?? '-') ?></td>
                <?php endif; ?>
                <td class="px-6 py-4 text-dark-300"><?= Core\View::e($app['email']) ?></td>
                <td class="px-6 py-4">
                    <?php
                    $statusColors = ['pending' => 'bg-yellow-500/20 text-yellow-400', 'reviewing' => 'bg-blue-500/20 text-blue-400', 'accepted' => 'bg-green-500/20 text-green-400', 'rejected' => 'bg-red-500/20 text-red-400'];
                    $statusLabels = ['pending' => 'En attente', 'reviewing' => 'En cours', 'accepted' => 'Acceptée', 'rejected' => 'Refusée'];
                    ?>
                    <span class="px-2 py-1 text-xs rounded-full <?= $statusColors[$app['status']] ?? 'bg-dark-600 text-dark-300' ?>">
                        <?= $statusLabels[$app['status']] ?? $app['status'] ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-dark-400 text-sm"><?= Core\View::date($app['created_at']) ?></td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="<?= SITE_URL ?>/admin/candidatures/<?= $app['id'] ?>" class="text-gold-500 hover:text-gold-400"><i class="fas fa-eye"></i></a>
                    <?php if ($app['resume_path']): ?>
                    <a href="<?= Core\View::upload($app['resume_path']) ?>" target="_blank" class="text-blue-400 hover:text-blue-300"><i class="fas fa-file-pdf"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-inbox text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucune candidature pour le moment.</p>
</div>
<?php endif; ?>
