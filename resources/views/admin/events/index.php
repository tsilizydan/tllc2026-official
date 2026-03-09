<?php
/**
 * TSILIZY LLC — Admin Events List
 */

Core\View::layout('admin', ['page_title' => 'Événements']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Événements</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> événement(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/evenements/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i> Nouvel événement
    </a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Événement</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Date</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Lieu</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-dark-700 rounded-lg flex items-center justify-center overflow-hidden">
                                <?php if ($event['image']): ?>
                                <img src="<?= Core\View::upload($event['image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                <i class="fas fa-calendar text-dark-500"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-white font-medium"><?= Core\View::e($event['title']) ?></p>
                                <p class="text-dark-500 text-sm"><?= Core\View::e($event['category'] ?? 'Général') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white"><?= Core\View::datetime($event['start_date']) ?></p>
                        <?php if ($event['end_date']): ?>
                        <p class="text-dark-500 text-sm">→ <?= Core\View::datetime($event['end_date']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-dark-400">
                        <?php if ($event['is_online']): ?>
                        <span class="text-blue-400"><i class="fas fa-video mr-1"></i> En ligne</span>
                        <?php else: ?>
                        <?= Core\View::e($event['location'] ?? '-') ?>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php
                            echo match($event['status']) {
                                'published' => 'bg-green-500/20 text-green-400',
                                'draft' => 'bg-dark-600 text-dark-400',
                                'cancelled' => 'bg-red-500/20 text-red-400',
                                default => 'bg-dark-600 text-dark-400'
                            };
                        ?>"><?php echo match($event['status']) { 'published' => 'Publié', 'draft' => 'Brouillon', 'cancelled' => 'Annulé', default => $event['status'] }; ?></span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= SITE_URL ?>/admin/evenements/<?= $event['id'] ?>/inscriptions" class="p-2 text-dark-400 hover:text-blue-400" title="Inscriptions"><i class="fas fa-users"></i></a>
                            <a href="<?= SITE_URL ?>/admin/evenements/<?= $event['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-edit"></i></a>
                            <form action="<?= SITE_URL ?>/admin/evenements/<?= $event['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-calendar text-4xl mb-4 block"></i>Aucun événement</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
