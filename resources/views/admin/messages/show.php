<?php
/**
 * TSILIZY LLC — Admin Message Show View
 */

Core\View::layout('admin', ['page_title' => 'Message']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/messages" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= Core\View::e($message['subject']) ?></h2>
    </div>
    <form action="<?= SITE_URL ?>/admin/messages/<?= $message['id'] ?>/supprimer" method="POST" onsubmit="return confirm('Supprimer ce message ?')">
        <?= Core\CSRF::field() ?>
        <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30"><i class="fas fa-trash mr-2"></i> Supprimer</button>
    </form>
</div>

<div class="max-w-3xl space-y-6">
    <!-- Original Message -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gold-500/20 rounded-full flex items-center justify-center">
                    <span class="text-gold-500 font-bold text-lg"><?= strtoupper(substr($message['sender_name'] ?? 'U', 0, 1)) ?></span>
                </div>
                <div>
                    <p class="font-medium text-white"><?= Core\View::e($message['sender_name'] ?? 'Utilisateur') ?></p>
                    <p class="text-dark-500 text-sm"><?= Core\View::e($message['sender_email']) ?></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-dark-500 text-sm"><?= Core\View::datetime($message['created_at']) ?></p>
                <?php if ($message['is_broadcast']): ?>
                <span class="text-xs bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded">Diffusion</span>
                <?php else: ?>
                <p class="text-dark-500 text-xs">À: <?= Core\View::e($message['recipient_name'] ?? 'Vous') ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="prose prose-invert max-w-none text-dark-300">
            <?= nl2br(Core\View::e($message['content'])) ?>
        </div>
    </div>
    
    <!-- Replies -->
    <?php if (!empty($replies)): ?>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-white">Réponses (<?= count($replies) ?>)</h3>
        <?php foreach ($replies as $reply): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 ml-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gold-500/20 rounded-full flex items-center justify-center">
                        <span class="text-gold-500 font-semibold text-sm"><?= strtoupper(substr($reply['sender_name'] ?? 'U', 0, 1)) ?></span>
                    </div>
                    <span class="text-white font-medium"><?= Core\View::e($reply['sender_name']) ?></span>
                </div>
                <span class="text-dark-500 text-xs"><?= Core\View::timeAgo($reply['created_at']) ?></span>
            </div>
            <p class="text-dark-300"><?= nl2br(Core\View::e($reply['content'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Reply Form -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Répondre</h3>
        <form action="<?= SITE_URL ?>/admin/messages/<?= $message['id'] ?>/repondre" method="POST">
            <?= Core\CSRF::field() ?>
            <textarea name="content" rows="4" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white mb-4" placeholder="Votre réponse..."></textarea>
            <button type="submit" class="px-6 py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-reply mr-2"></i> Répondre</button>
        </form>
    </div>
</div>
