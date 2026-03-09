<?php
/**
 * TSILIZY LLC — User Dashboard Home
 */

Core\View::layout('dashboard', ['page_title' => 'Mon Compte']);
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Bienvenue, <?= Core\View::e($current_user['first_name'] ?? 'Utilisateur') ?> !</h1>
    <p class="text-dark-400 mt-1">Voici un aperçu de votre espace personnel</p>
</div>

<!-- Quick Stats -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Tickets ouverts</p>
                <p class="text-3xl font-bold text-white mt-1">
                    <?= count(array_filter($tickets ?? [], fn($t) => $t['status'] !== 'closed')) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-headset text-xl text-blue-400"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Contrats actifs</p>
                <p class="text-3xl font-bold text-white mt-1">
                    <?= count(array_filter($contracts ?? [], fn($c) => $c['status'] === 'active')) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-contract text-xl text-green-400"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Factures en attente</p>
                <p class="text-3xl font-bold text-white mt-1">
                    <?= count(array_filter($invoices ?? [], fn($i) => $i['status'] === 'pending' || $i['status'] === 'sent')) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-gold-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-xl text-gold-400"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Tickets -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Tickets récents</h2>
            <a href="<?= SITE_URL ?>/mon-compte/tickets" class="text-sm text-gold-500 hover:text-gold-400">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <?php if (!empty($tickets)): ?>
        <div class="space-y-3">
            <?php foreach (array_slice($tickets, 0, 5) as $ticket): ?>
            <a href="<?= SITE_URL ?>/mon-compte/tickets/<?= $ticket['id'] ?>" class="block p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-white font-medium">#<?= $ticket['reference'] ?></span>
                    <span class="px-2 py-1 text-xs rounded-full <?php
                        echo match($ticket['status']) {
                            'open' => 'bg-green-500/20 text-green-400',
                            'in_progress' => 'bg-blue-500/20 text-blue-400',
                            'closed' => 'bg-dark-600 text-dark-300',
                            default => 'bg-dark-600 text-dark-300'
                        };
                    ?>">
                        <?= ucfirst($ticket['status']) ?>
                    </span>
                </div>
                <p class="text-dark-400 text-sm truncate"><?= Core\View::e($ticket['subject']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-400 text-center py-8">Aucun ticket</p>
        <?php endif; ?>
        
        <div class="mt-4 pt-4 border-t border-dark-700">
            <a href="<?= SITE_URL ?>/mon-compte/tickets/creer" class="inline-flex items-center text-gold-500 hover:text-gold-400 text-sm font-medium">
                <i class="fas fa-plus mr-2"></i> Nouveau ticket
            </a>
        </div>
    </div>
    
    <!-- Recent Invoices -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Dernières factures</h2>
            <a href="<?= SITE_URL ?>/mon-compte/factures" class="text-sm text-gold-500 hover:text-gold-400">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <?php if (!empty($invoices)): ?>
        <div class="space-y-3">
            <?php foreach (array_slice($invoices, 0, 5) as $invoice): ?>
            <div class="p-4 bg-dark-900/50 rounded-lg">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-white font-medium"><?= $invoice['reference'] ?></span>
                    <span class="text-gold-500 font-semibold"><?= Core\View::currency($invoice['total']) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-dark-400 text-sm"><?= Core\View::date($invoice['created_at']) ?></span>
                    <span class="px-2 py-1 text-xs rounded-full <?php
                        echo match($invoice['status']) {
                            'paid' => 'bg-green-500/20 text-green-400',
                            'pending', 'sent' => 'bg-yellow-500/20 text-yellow-400',
                            'overdue' => 'bg-red-500/20 text-red-400',
                            default => 'bg-dark-600 text-dark-300'
                        };
                    ?>">
                        <?php
                        echo match($invoice['status']) {
                            'paid' => 'Payée',
                            'pending' => 'En attente',
                            'sent' => 'Envoyée',
                            'overdue' => 'En retard',
                            default => $invoice['status']
                        };
                        ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-400 text-center py-8">Aucune facture</p>
        <?php endif; ?>
    </div>
</div>
