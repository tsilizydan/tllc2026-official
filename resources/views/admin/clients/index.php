<?php
/**
 * TSILIZY LLC — Admin Clients Index View
 */

Core\View::layout('admin', ['page_title' => 'Clients']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-white">Clients</h2>
    <div class="flex flex-col sm:flex-row gap-3">
        <form class="relative">
            <input type="text" name="search" value="<?= Core\View::e($search ?? '') ?>" placeholder="Rechercher..." class="w-full sm:w-64 pl-10 pr-4 py-2 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 text-sm">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-dark-500"></i>
        </form>
        <a href="<?= SITE_URL ?>/admin/clients/creer" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg text-center"><i class="fas fa-plus mr-2"></i> Nouveau</a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Total clients</p>
        <p class="text-2xl font-bold text-white"><?= number_format($stats['total'] ?? 0) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Clients actifs</p>
        <p class="text-2xl font-bold text-green-400"><?= number_format($stats['active'] ?? 0) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">CA total</p>
        <p class="text-2xl font-bold text-gold-500"><?= Core\View::currency($stats['invoices_total'] ?? 0) ?></p>
    </div>
</div>

<?php if (!empty($clients)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-dark-900">
                <tr>
                    <th class="px-4 md:px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Client</th>
                    <th class="px-4 md:px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase hidden md:table-cell">Entreprise</th>
                    <th class="px-4 md:px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase hidden sm:table-cell">Email</th>
                    <th class="px-4 md:px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Statut</th>
                    <th class="px-4 md:px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                <?php foreach ($clients as $c): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-4 md:px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gold-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-gold-500 font-semibold"><?= strtoupper(substr($c['first_name'], 0, 1)) ?></span>
                            </div>
                            <div>
                                <p class="font-medium text-white"><?= Core\View::e($c['first_name'] . ' ' . $c['last_name']) ?></p>
                                <p class="text-dark-400 text-xs sm:hidden"><?= Core\View::e($c['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-dark-300 hidden md:table-cell"><?= Core\View::e($c['company'] ?: '-') ?></td>
                    <td class="px-4 md:px-6 py-4 text-dark-300 hidden sm:table-cell"><?= Core\View::e($c['email']) ?></td>
                    <td class="px-4 md:px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $c['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-300' ?>">
                            <?= $c['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-right space-x-2">
                        <a href="<?= SITE_URL ?>/admin/clients/<?= $c['id'] ?>" class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></a>
                        <a href="<?= SITE_URL ?>/admin/clients/<?= $c['id'] ?>/modifier" class="text-gold-500 hover:text-gold-400"><i class="fas fa-edit"></i></a>
                        <form action="<?= SITE_URL ?>/admin/clients/<?= $c['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ce client ?')">
                            <?= Core\CSRF::field() ?><button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($last_page > 1): ?>
<div class="flex justify-center mt-6">
    <nav class="flex space-x-1">
        <?php for ($i = 1; $i <= $last_page; $i++): ?>
        <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-2 rounded <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-400 hover:bg-dark-700' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-users text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun client trouvé.</p>
    <a href="<?= SITE_URL ?>/admin/clients/creer" class="inline-block mt-4 text-gold-500 hover:text-gold-400">Créer un client</a>
</div>
<?php endif; ?>
