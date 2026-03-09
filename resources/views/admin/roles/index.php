<?php
/**
 * TSILIZY LLC — Admin Roles Index View
 */

Core\View::layout('admin', ['page_title' => 'Rôles & Permissions']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-white">Rôles & Permissions</h2>
    <a href="<?= SITE_URL ?>/admin/roles/creer" class="inline-flex items-center px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouveau rôle</a>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($roles as $role): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 hover:border-gold-500/50 transition-colors">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-white"><?= Core\View::e($role['name']) ?></h3>
                <p class="text-dark-500 font-mono text-xs"><?= Core\View::e($role['slug']) ?></p>
            </div>
            <?php if (in_array($role['slug'], ['super_admin', 'admin'])): ?>
            <span class="px-2 py-1 text-xs bg-gold-500/20 text-gold-500 rounded">Système</span>
            <?php endif; ?>
        </div>
        
        <p class="text-dark-400 text-sm mb-4 line-clamp-2"><?= Core\View::e($role['description'] ?: 'Aucune description') ?></p>
        
        <div class="flex items-center justify-between text-sm mb-4">
            <span class="text-dark-500"><i class="fas fa-users mr-1"></i> <?= $role['users_count'] ?> utilisateur<?= $role['users_count'] > 1 ? 's' : '' ?></span>
            <span class="text-dark-500"><i class="fas fa-key mr-1"></i> <?= $role['permissions_count'] ?> permission<?= $role['permissions_count'] > 1 ? 's' : '' ?></span>
        </div>
        
        <div class="flex space-x-2 pt-4 border-t border-dark-700">
            <a href="<?= SITE_URL ?>/admin/roles/<?= $role['id'] ?>/utilisateurs" class="flex-1 text-center py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 text-sm"><i class="fas fa-users"></i></a>
            <a href="<?= SITE_URL ?>/admin/roles/<?= $role['id'] ?>/modifier" class="flex-1 text-center py-2 bg-gold-500/20 text-gold-500 rounded-lg hover:bg-gold-500/30 text-sm"><i class="fas fa-edit"></i></a>
            <?php if (!in_array($role['slug'], ['super_admin', 'admin', 'user'])): ?>
            <form action="<?= SITE_URL ?>/admin/roles/<?= $role['id'] ?>/supprimer" method="POST" class="flex-1" onsubmit="return confirm('Supprimer ce rôle ?')">
                <?= Core\CSRF::field() ?>
                <button type="submit" class="w-full py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 text-sm"><i class="fas fa-trash"></i></button>
            </form>
            <?php else: ?>
            <div class="flex-1"></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
