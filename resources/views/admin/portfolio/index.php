<?php
/**
 * TSILIZY LLC — Admin Portfolio List
 */

Core\View::layout('admin', ['page_title' => 'Portfolio']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Portfolio</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> projet(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/portfolio/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i> Nouveau projet
    </a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Projet</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Client</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Catégorie</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-16 h-12 bg-dark-700 rounded-lg flex items-center justify-center overflow-hidden">
                                <?php if ($item['image']): ?>
                                <img src="<?= Core\View::upload($item['image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                <i class="fas fa-briefcase text-dark-500"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-white font-medium"><?= Core\View::e($item['title']) ?></p>
                                <?php if ($item['is_featured']): ?><span class="text-gold-500 text-xs"><i class="fas fa-star mr-1"></i>En vedette</span><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-dark-400"><?= Core\View::e($item['client_name'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-dark-400"><?= Core\View::e($item['category'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $item['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-400' ?>">
                            <?= $item['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= SITE_URL ?>/admin/portfolio/<?= $item['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-edit"></i></a>
                            <form action="<?= SITE_URL ?>/admin/portfolio/<?= $item['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-briefcase text-4xl mb-4 block"></i>Aucun projet</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
