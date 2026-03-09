<?php
/**
 * TSILIZY LLC — Admin Users List
 */

Core\View::layout('admin', ['page_title' => 'Utilisateurs']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Utilisateurs</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> utilisateur(s) au total</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/utilisateurs/creer" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg transition-all duration-300">
        <i class="fas fa-plus mr-2"></i> Nouvel utilisateur
    </a>
</div>

<!-- Filters -->
<div class="bg-dark-800 border border-dark-700 rounded-xl p-4 mb-6">
    <form action="" method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?= Core\View::e($search ?? '') ?>" 
                   placeholder="Rechercher par nom ou email..."
                   class="w-full px-4 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500">
        </div>
        <div>
            <select name="status" class="px-4 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                <option value="">Tous les statuts</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                <option value="suspended" <?= ($status ?? '') === 'suspended' ? 'selected' : '' ?>>Suspendu</option>
                <option value="banned" <?= ($status ?? '') === 'banned' ? 'selected' : '' ?>>Banni</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
            <i class="fas fa-search mr-2"></i> Filtrer
        </button>
        <?php if ($search || $status): ?>
        <a href="<?= SITE_URL ?>/admin/utilisateurs" class="px-6 py-2 border border-dark-600 text-dark-300 rounded-lg hover:text-white hover:border-dark-500 transition-colors text-center">
            Réinitialiser
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Users Table -->
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-dark-900/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Dernière connexion</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Inscrit le</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-dark-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center text-dark-950 font-semibold">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="text-white font-medium"><?= Core\View::e($user['first_name'] . ' ' . $user['last_name']) ?></p>
                                    <p class="text-dark-400 text-sm"><?= Core\View::e($user['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">
                                <?= Core\View::e($user['role_name'] ?? 'Utilisateur') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $statusColors = [
                                'active' => 'bg-green-500/20 text-green-400',
                                'suspended' => 'bg-yellow-500/20 text-yellow-400',
                                'banned' => 'bg-red-500/20 text-red-400'
                            ];
                            $statusLabels = [
                                'active' => 'Actif',
                                'suspended' => 'Suspendu',
                                'banned' => 'Banni'
                            ];
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusColors[$user['status']] ?? 'bg-dark-600 text-dark-300' ?>">
                                <?= $statusLabels[$user['status']] ?? $user['status'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-dark-400 text-sm">
                            <?= $user['last_login_at'] ? Core\View::datetime($user['last_login_at']) : 'Jamais' ?>
                        </td>
                        <td class="px-6 py-4 text-dark-400 text-sm">
                            <?= Core\View::date($user['created_at']) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2" x-data="{ open: false }">
                                <a href="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="relative">
                                    <button @click="open = !open" class="p-2 text-dark-400 hover:text-white transition-colors">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-dark-700 border border-dark-600 rounded-lg shadow-xl z-10">
                                        <a href="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>" class="flex items-center px-4 py-2 text-sm text-dark-300 hover:bg-dark-600 hover:text-white">
                                            <i class="fas fa-eye w-4 mr-3"></i> Voir
                                        </a>
                                        <?php if ($user['status'] === 'active'): ?>
                                        <form action="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>/suspendre" method="POST">
                                            <?= Core\CSRF::field() ?>
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-yellow-400 hover:bg-dark-600">
                                                <i class="fas fa-pause w-4 mr-3"></i> Suspendre
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <?php if ($user['status'] !== 'banned'): ?>
                                        <form action="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>/bannir" method="POST">
                                            <?= Core\CSRF::field() ?>
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-orange-400 hover:bg-dark-600">
                                                <i class="fas fa-ban w-4 mr-3"></i> Bannir
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <div class="border-t border-dark-600"></div>
                                        <form action="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>/supprimer" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <?= Core\CSRF::field() ?>
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-dark-600">
                                                <i class="fas fa-trash w-4 mr-3"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-dark-400">
                            <i class="fas fa-users text-4xl mb-4 block"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($last_page > 1): ?>
    <div class="px-6 py-4 border-t border-dark-700 flex items-center justify-between">
        <p class="text-dark-400 text-sm">
            Page <?= $current_page ?> sur <?= $last_page ?>
        </p>
        <div class="flex space-x-2">
            <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $current_page - 2); $i <= min($last_page, $current_page + 2); $i++): ?>
            <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="px-4 py-2 <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-700 text-white hover:bg-dark-600' ?> rounded-lg transition-colors">
                <?= $i ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($current_page < $last_page): ?>
            <a href="?page=<?= $current_page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
