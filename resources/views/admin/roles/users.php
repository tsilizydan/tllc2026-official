<?php
/**
 * TSILIZY LLC — Admin Role Users View
 */

Core\View::layout('admin', ['page_title' => 'Utilisateurs - ' . ($role['name'] ?? '')]);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/roles" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour aux rôles</a>
        <h2 class="text-2xl font-bold text-white mt-2">Utilisateurs avec le rôle "<?= Core\View::e($role['name']) ?>"</h2>
    </div>
</div>

<?php if (!empty($users)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Utilisateur</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($users as $user): ?>
            <tr class="hover:bg-dark-700/50">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gold-500/20 rounded-full flex items-center justify-center">
                            <span class="text-gold-500 font-semibold"><?= strtoupper(substr($user['first_name'], 0, 1)) ?></span>
                        </div>
                        <span class="text-white"><?= Core\View::e($user['first_name'] . ' ' . $user['last_name']) ?></span>
                    </div>
                </td>
                <td class="px-6 py-4 text-dark-300"><?= Core\View::e($user['email']) ?></td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full <?= $user['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                        <?= $user['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>/modifier" class="text-gold-500 hover:text-gold-400"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-users text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun utilisateur avec ce rôle.</p>
</div>
<?php endif; ?>
