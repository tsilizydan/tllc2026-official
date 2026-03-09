<?php
/**
 * TSILIZY LLC — View Ticket
 */

Core\View::layout('dashboard', ['page_title' => 'Ticket #' . $ticket['reference']]);
?>

<div class="mb-6">
    <a href="<?= SITE_URL ?>/mon-compte/tickets" class="text-dark-400 hover:text-white text-sm inline-block mb-2">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux tickets
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white"><?= Core\View::e($ticket['subject']) ?></h1>
            <div class="flex items-center space-x-3 mt-2">
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
            </div>
        </div>
        <span class="text-dark-500 text-sm">Créé le <?= Core\View::datetime($ticket['created_at']) ?></span>
    </div>
</div>

<div class="space-y-4">
    <!-- Original Message -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 font-semibold">
                <?= strtoupper(substr($current_user['first_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <p class="text-white font-medium">Vous</p>
                <p class="text-dark-500 text-xs"><?= Core\View::datetime($ticket['created_at']) ?></p>
            </div>
        </div>
        <div class="prose prose-invert max-w-none">
            <?= nl2br(Core\View::e($ticket['message'])) ?>
        </div>
    </div>
    
    <!-- Replies -->
    <?php foreach ($replies as $reply): ?>
    <div class="bg-dark-800 border <?= $reply['is_current_user'] ? 'border-gold-500/30' : 'border-dark-700' ?> rounded-xl p-6 <?= !$reply['is_current_user'] ? 'ml-8' : '' ?>">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 <?= $reply['is_current_user'] ? 'bg-gold-500' : 'bg-blue-500' ?> rounded-full flex items-center justify-center text-dark-950 font-semibold">
                <?= strtoupper(substr($reply['user_name'] ?? 'S', 0, 1)) ?>
            </div>
            <div>
                <p class="text-white font-medium">
                    <?= $reply['is_current_user'] ? 'Vous' : Core\View::e($reply['user_name'] ?? 'Support') ?>
                </p>
                <p class="text-dark-500 text-xs"><?= Core\View::datetime($reply['created_at']) ?></p>
            </div>
        </div>
        <div class="prose prose-invert max-w-none">
            <?= nl2br(Core\View::e($reply['message'])) ?>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Reply Form -->
    <?php if ($ticket['status'] !== 'closed'): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Répondre</h3>
        <form action="<?= SITE_URL ?>/mon-compte/tickets/<?= $ticket['id'] ?>/repondre" method="POST">
            <?= Core\CSRF::field() ?>
            <textarea name="message" rows="4" required
                      class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 resize-none mb-4"
                      placeholder="Votre réponse..."></textarea>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-reply mr-2"></i> Envoyer
            </button>
        </form>
    </div>
    <?php else: ?>
    <div class="bg-dark-700/50 border border-dark-600 rounded-xl p-6 text-center">
        <i class="fas fa-lock text-dark-500 text-2xl mb-2"></i>
        <p class="text-dark-400">Ce ticket est fermé. Créez un nouveau ticket si vous avez besoin d'aide.</p>
    </div>
    <?php endif; ?>
</div>
