<?php
/**
 * TSILIZY LLC — Admin Services List
 */

Core\View::layout('admin', ['page_title' => 'Services']);
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Services</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> service(s) au total</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/services/creer" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg transition-all duration-300">
        <i class="fas fa-plus mr-2"></i> Nouveau service
    </a>
</div>

<!-- Services Table -->
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-dark-900/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Prix</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Ordre</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                    <tr class="hover:bg-dark-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gold-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas <?= Core\View::e($service['icon'] ?? 'fa-star') ?> text-gold-500"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium"><?= Core\View::e($service['title']) ?></p>
                                    <p class="text-dark-400 text-sm truncate max-w-xs">
                                        <?= Core\View::truncate(strip_tags($service['short_description'] ?? $service['description']), 50) ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-white">
                            <?= $service['price'] ? Core\View::currency($service['price']) : '-' ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?= $service['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-300' ?>">
                                <?= $service['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                            <?php if ($service['is_featured']): ?>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-gold-500/20 text-gold-400">
                                <i class="fas fa-star text-xs"></i>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-dark-400">
                            <?= $service['order_index'] ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="<?= SITE_URL ?>/services/<?= $service['slug'] ?>" target="_blank" class="p-2 text-dark-400 hover:text-white transition-colors" title="Voir">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="<?= SITE_URL ?>/admin/services/<?= $service['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= SITE_URL ?>/admin/services/<?= $service['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    <?= Core\CSRF::field() ?>
                                    <button type="submit" class="p-2 text-dark-400 hover:text-red-400 transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-dark-400">
                            <i class="fas fa-concierge-bell text-4xl mb-4 block"></i>
                            Aucun service trouvé
                            <a href="<?= SITE_URL ?>/admin/services/creer" class="block mt-4 text-gold-500 hover:text-gold-400">
                                Créer votre premier service
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($last_page > 1): ?>
    <div class="px-6 py-4 border-t border-dark-700 flex items-center justify-between">
        <p class="text-dark-400 text-sm">Page <?= $current_page ?> sur <?= $last_page ?></p>
        <div class="flex space-x-2">
            <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            <?php if ($current_page < $last_page): ?>
            <a href="?page=<?= $current_page + 1 ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
