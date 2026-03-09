<?php
/**
 * TSILIZY LLC — Admin Products List
 */

Core\View::layout('admin', ['page_title' => 'Produits']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Produits</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> produit(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/produits/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg transition-all duration-300">
        <i class="fas fa-plus mr-2"></i> Nouveau produit
    </a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Produit</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Catégorie</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Prix</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Stock</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-dark-700 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                <?php if ($product['image']): ?>
                                <img src="<?= Core\View::upload($product['image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                <i class="fas fa-box text-dark-500"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-white font-medium"><?= Core\View::e($product['title']) ?></p>
                                <p class="text-dark-500 text-sm"><?= $product['sku'] ?? 'N/A' ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-dark-400"><?= Core\View::e($product['category'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <?php if ($product['sale_price']): ?>
                        <span class="text-gold-500 font-semibold"><?= Core\View::currency($product['sale_price']) ?></span>
                        <span class="text-dark-500 line-through text-sm ml-1"><?= Core\View::currency($product['price']) ?></span>
                        <?php else: ?>
                        <span class="text-white font-semibold"><?= Core\View::currency($product['price']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="<?= $product['stock'] > 0 ? 'text-green-400' : 'text-red-400' ?>">
                            <?= $product['stock'] ?? 0 ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $product['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-400' ?>">
                            <?= $product['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= SITE_URL ?>/admin/produits/<?= $product['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-edit"></i></a>
                            <form action="<?= SITE_URL ?>/admin/produits/<?= $product['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-box text-4xl mb-4 block"></i>Aucun produit</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if ($last_page > 1): ?>
    <div class="px-6 py-4 border-t border-dark-700 flex items-center justify-between">
        <p class="text-dark-400 text-sm">Page <?= $current_page ?> sur <?= $last_page ?></p>
        <div class="flex space-x-2">
            <?php if ($current_page > 1): ?><a href="?page=<?= $current_page - 1 ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
            <?php if ($current_page < $last_page): ?><a href="?page=<?= $current_page + 1 ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600"><i class="fas fa-chevron-right"></i></a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
