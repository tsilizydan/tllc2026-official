<?php
/**
 * TSILIZY LLC — User Tickets List
 */

Core\View::layout('dashboard', ['page_title' => 'Mes Tickets']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Mes Tickets</h1>
        <p class="text-dark-400 mt-1">Gérez vos demandes de support</p>
    </div>
    <a href="<?= SITE_URL ?>/mon-compte/tickets/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg transition-all duration-300">
        <i class="fas fa-plus mr-2"></i> Nouveau ticket
    </a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <?php if (!empty($tickets)): ?>
    <div class="divide-y divide-dark-700">
        <?php foreach ($tickets as $ticket): ?>
        <a href="<?= SITE_URL ?>/mon-compte/tickets/<?= $ticket['id'] ?>" class="block p-6 hover:bg-dark-700/50 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <span class="text-gold-500 font-mono text-sm">#<?= $ticket['reference'] ?></span>
                    <span class="px-2 py-1 text-xs rounded-full <?php
                        echo match($ticket['status']) {
                            'open' => 'bg-green-500/20 text-green-400',
                            'in_progress' => 'bg-blue-500/20 text-blue-400',
                            'waiting' => 'bg-yellow-500/20 text-yellow-400',
                            'closed' => 'bg-dark-600 text-dark-300',
                            default => 'bg-dark-600 text-dark-300'
                        };
                    ?>">
                        <?php
                        echo match($ticket['status']) {
                            'open' => 'Ouvert',
                            'in_progress' => 'En cours',
                            'waiting' => 'En attente',
                            'closed' => 'Fermé',
                            default => $ticket['status']
                        };
                        ?>
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full <?php
                        echo match($ticket['priority']) {
                            'high' => 'bg-red-500/20 text-red-400',
                            'medium' => 'bg-yellow-500/20 text-yellow-400',
                            'low' => 'bg-dark-600 text-dark-400',
                            default => 'bg-dark-600 text-dark-400'
                        };
                    ?>">
                        <?php
                        echo match($ticket['priority']) {
                            'high' => 'Haute',
                            'medium' => 'Moyenne',
                            'low' => 'Basse',
                            default => $ticket['priority']
                        };
                        ?>
                    </span>
                </div>
                <span class="text-dark-500 text-sm"><?= Core\View::datetime($ticket['created_at']) ?></span>
            </div>
            <h3 class="text-white font-medium mb-1"><?= Core\View::e($ticket['subject']) ?></h3>
            <p class="text-dark-400 text-sm line-clamp-2"><?= Core\View::truncate(strip_tags($ticket['message']), 150) ?></p>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <i class="fas fa-ticket-alt text-5xl text-dark-600 mb-4"></i>
        <p class="text-dark-400 mb-4">Vous n'avez pas encore de ticket</p>
        <a href="<?= SITE_URL ?>/mon-compte/tickets/creer" class="inline-flex items-center text-gold-500 hover:text-gold-400">
            <i class="fas fa-plus mr-2"></i> Créer votre premier ticket
        </a>
    </div>
    <?php endif; ?>
</div>
