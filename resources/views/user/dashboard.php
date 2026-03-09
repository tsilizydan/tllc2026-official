<?php
/**
 * TSILIZY LLC — User Dashboard View
 */
?>

<h1 class="text-2xl font-bold text-white mb-6">Bienvenue, <?= Core\View::e($user['first_name'] ?? 'Utilisateur') ?></h1>

<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gold-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-gold-500 text-xl"></i>
            </div>
            <div>
                <p class="text-dark-400 text-sm">Factures</p>
                <p class="text-2xl font-bold text-white"><?= count($invoices ?? []) ?></p>
            </div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-contract text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-dark-400 text-sm">Contrats</p>
                <p class="text-2xl font-bold text-white"><?= count($contracts ?? []) ?></p>
            </div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-bell text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-dark-400 text-sm">Notifications</p>
                <p class="text-2xl font-bold text-white"><?= count($notifications ?? []) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Invoices -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Dernières factures</h2>
            <a href="<?= SITE_URL ?>/mon-compte/factures" class="text-gold-500 text-sm hover:text-gold-400">Voir tout</a>
        </div>
        
        <?php if (!empty($invoices)): ?>
        <div class="space-y-3">
            <?php foreach ($invoices as $inv): ?>
            <div class="flex items-center justify-between py-3 border-b border-dark-700 last:border-0">
                <div>
                    <p class="text-white font-mono"><?= $inv['reference'] ?></p>
                    <p class="text-dark-500 text-sm"><?= Core\View::date($inv['created_at']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-gold-500 font-semibold"><?= Core\View::currency($inv['total']) ?></p>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $inv['status'] === 'paid' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                        <?= $inv['status'] === 'paid' ? 'Payée' : 'En attente' ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-500 text-center py-8">Aucune facture</p>
        <?php endif; ?>
    </div>
    
    <!-- Recent Notifications -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Notifications récentes</h2>
        
        <?php if (!empty($notifications)): ?>
        <div class="space-y-3">
            <?php foreach ($notifications as $notif): ?>
            <div class="flex items-start space-x-3 py-3 border-b border-dark-700 last:border-0">
                <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-blue-400 text-sm"></i>
                </div>
                <div>
                    <p class="text-white"><?= Core\View::e($notif['title']) ?></p>
                    <p class="text-dark-500 text-sm"><?= Core\View::timeAgo($notif['created_at']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-500 text-center py-8">Aucune notification</p>
        <?php endif; ?>
    </div>
</div>
