<?php
/**
 * TSILIZY LLC — Admin View Ticket
 */

Core\View::layout('admin', ['page_title' => 'Ticket #' . $ticket['reference']]);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/tickets" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= Core\View::e($ticket['subject']) ?></h2>
        <div class="flex items-center space-x-3 mt-2">
            <span class="text-gold-500 font-mono">#<?= $ticket['reference'] ?></span>
            <span class="px-2 py-1 text-xs rounded-full <?php echo match($ticket['status']) { 'open' => 'bg-green-500/20 text-green-400', 'in_progress' => 'bg-blue-500/20 text-blue-400', 'waiting' => 'bg-yellow-500/20 text-yellow-400', default => 'bg-dark-600 text-dark-400' }; ?>">
                <?php echo match($ticket['status']) { 'open' => 'Ouvert', 'in_progress' => 'En cours', 'waiting' => 'En attente', 'closed' => 'Fermé', default => $ticket['status'] }; ?>
            </span>
        </div>
    </div>
    <form action="<?= SITE_URL ?>/admin/tickets/<?= $ticket['id'] ?>/statut" method="POST" class="flex items-center space-x-2">
        <?= Core\CSRF::field() ?>
        <select name="status" class="px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-white text-sm">
            <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Ouvert</option>
            <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>En cours</option>
            <option value="waiting" <?= $ticket['status'] === 'waiting' ? 'selected' : '' ?>>En attente</option>
            <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Fermé</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600">Mettre à jour</button>
    </form>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <!-- Original Message -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 font-bold">
                    <?= strtoupper(substr($ticket['user_name'] ?? 'C', 0, 1)) ?>
                </div>
                <div>
                    <p class="text-white font-medium"><?= Core\View::e($ticket['user_name'] ?? 'Client') ?></p>
                    <p class="text-dark-500 text-xs"><?= Core\View::datetime($ticket['created_at']) ?></p>
                </div>
            </div>
            <div class="prose prose-invert max-w-none"><?= nl2br(Core\View::e($ticket['message'])) ?></div>
        </div>
        
        <!-- Replies -->
        <?php foreach ($replies as $reply): ?>
        <div class="bg-dark-800 border <?= $reply['user_id'] == $ticket['user_id'] ? 'border-dark-700' : 'border-blue-500/30' ?> rounded-xl p-6 <?= $reply['user_id'] != $ticket['user_id'] ? 'ml-8' : '' ?>">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 <?= $reply['user_id'] == $ticket['user_id'] ? 'bg-gold-500' : 'bg-blue-500' ?> rounded-full flex items-center justify-center text-dark-950 font-bold">
                    <?= strtoupper(substr($reply['user_name'] ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <p class="text-white font-medium"><?= Core\View::e($reply['user_name'] ?? 'Admin') ?></p>
                    <p class="text-dark-500 text-xs"><?= Core\View::datetime($reply['created_at']) ?></p>
                </div>
            </div>
            <div class="prose prose-invert max-w-none"><?= nl2br(Core\View::e($reply['message'])) ?></div>
        </div>
        <?php endforeach; ?>
        
        <!-- Reply Form -->
        <?php if ($ticket['status'] !== 'closed'): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Répondre</h3>
            <form action="<?= SITE_URL ?>/admin/tickets/<?= $ticket['id'] ?>/repondre" method="POST">
                <?= Core\CSRF::field() ?>
                <textarea name="message" rows="4" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white resize-none mb-4" placeholder="Votre réponse..."></textarea>
                <div class="flex items-center justify-between">
                    <select name="status" class="px-4 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm">
                        <option value="">Conserver le statut</option>
                        <option value="in_progress">En cours</option>
                        <option value="waiting">En attente client</option>
                        <option value="closed">Fermer</option>
                    </select>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg"><i class="fas fa-reply mr-2"></i> Envoyer</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Client</h3>
            <div class="space-y-3">
                <div><span class="text-dark-500">Nom:</span><br><span class="text-white"><?= Core\View::e($ticket['user_name'] ?? 'N/A') ?></span></div>
                <div><span class="text-dark-500">Email:</span><br><a href="mailto:<?= Core\View::e($ticket['user_email'] ?? '') ?>" class="text-gold-500"><?= Core\View::e($ticket['user_email'] ?? 'N/A') ?></a></div>
            </div>
        </div>
        
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Détails</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-dark-500">Catégorie</span><span class="text-white"><?= ucfirst($ticket['category'] ?? 'Général') ?></span></div>
                <div class="flex justify-between"><span class="text-dark-500">Priorité</span><span class="<?= $ticket['priority'] === 'high' ? 'text-red-400' : 'text-white' ?>"><?= ucfirst($ticket['priority']) ?></span></div>
                <div class="flex justify-between"><span class="text-dark-500">Créé</span><span class="text-white"><?= Core\View::date($ticket['created_at']) ?></span></div>
            </div>
        </div>
    </div>
</div>
