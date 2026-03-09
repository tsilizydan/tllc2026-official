<?php
/**
 * TSILIZY LLC — Admin Careers List
 */

Core\View::layout('admin', ['page_title' => 'Offres d\'emploi']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Offres d'emploi</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> offre(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/carrieres/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i> Nouvelle offre
    </a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Poste</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Département</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Type</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Candidatures</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <p class="text-white font-medium"><?= Core\View::e($job['title']) ?></p>
                        <p class="text-dark-500 text-sm"><?= Core\View::e($job['location'] ?? 'Non spécifié') ?> <?= $job['is_remote'] ? '<span class="text-blue-400">• Remote</span>' : '' ?></p>
                    </td>
                    <td class="px-6 py-4 text-dark-400"><?= Core\View::e($job['department'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-dark-400"><?php echo match($job['employment_type'] ?? '') { 'full_time' => 'Temps plein', 'part_time' => 'Temps partiel', 'contract' => 'Contrat', 'internship' => 'Stage', default => '-' }; ?></td>
                    <td class="px-6 py-4">
                        <span class="text-gold-500 font-medium"><?= $job['applications_count'] ?? 0 ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $job['status'] === 'open' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-400' ?>">
                            <?= $job['status'] === 'open' ? 'Ouvert' : 'Fermé' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= SITE_URL ?>/admin/carrieres/<?= $job['id'] ?>/candidatures" class="p-2 text-dark-400 hover:text-blue-400" title="Candidatures"><i class="fas fa-users"></i></a>
                            <a href="<?= SITE_URL ?>/admin/carrieres/<?= $job['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-edit"></i></a>
                            <form action="<?= SITE_URL ?>/admin/carrieres/<?= $job['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-briefcase text-4xl mb-4 block"></i>Aucune offre d'emploi</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
