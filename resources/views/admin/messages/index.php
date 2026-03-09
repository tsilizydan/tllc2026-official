<?php
/**
 * TSILIZY LLC — Admin Messages Index View
 */

Core\View::layout('admin', ['page_title' => 'Messages']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Messages</h2>
        <?php if ($unread_count > 0): ?>
        <p class="text-gold-500 text-sm"><?= $unread_count ?> message<?= $unread_count > 1 ? 's' : '' ?> non lu<?= $unread_count > 1 ? 's' : '' ?></p>
        <?php endif; ?>
    </div>
    <a href="<?= SITE_URL ?>/admin/messages/composer" class="inline-flex items-center px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-pen mr-2"></i> Nouveau message</a>
</div>

<!-- Filters -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="<?= SITE_URL ?>/admin/messages" class="px-4 py-2 rounded-lg text-sm <?= $filter === 'all' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">Tous</a>
    <a href="<?= SITE_URL ?>/admin/messages?filter=unread" class="px-4 py-2 rounded-lg text-sm <?= $filter === 'unread' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">Non lus</a>
    <a href="<?= SITE_URL ?>/admin/messages?filter=sent" class="px-4 py-2 rounded-lg text-sm <?= $filter === 'sent' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">Envoyés</a>
</div>

<?php if (!empty($messages)): ?>
<div class="space-y-3">
    <?php foreach ($messages as $m): ?>
    <a href="<?= SITE_URL ?>/admin/messages/<?= $m['id'] ?>" class="block bg-dark-800 border border-dark-700 rounded-xl p-4 hover:border-gold-500/50 transition-colors <?= !$m['is_read'] && $m['sender_id'] != Core\Auth::id() ? 'border-l-4 border-l-gold-500' : '' ?>">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 bg-gold-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-gold-500 font-semibold"><?= strtoupper(substr($m['sender_name'] ?? 'U', 0, 1)) ?></span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-white"><?= Core\View::e($m['sender_name'] ?? 'Utilisateur') ?></span>
                        <?php if ($m['is_broadcast']): ?>
                        <span class="px-2 py-0.5 text-xs bg-blue-500/20 text-blue-400 rounded">Diffusion</span>
                        <?php endif; ?>
                        <?php if (!$m['is_read'] && $m['sender_id'] != Core\Auth::id()): ?>
                        <span class="w-2 h-2 bg-gold-500 rounded-full"></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-white font-medium mt-1"><?= Core\View::e($m['subject']) ?></p>
                    <p class="text-dark-400 text-sm mt-1 line-clamp-1"><?= Core\View::e(Core\View::truncate(strip_tags($m['content']), 100)) ?></p>
                </div>
            </div>
            <span class="text-dark-500 text-xs whitespace-nowrap"><?= Core\View::timeAgo($m['created_at']) ?></span>
        </div>
    </a>
    <?php endforeach; ?>
</div>

<?php if ($last_page > 1): ?>
<div class="flex justify-center mt-6">
    <nav class="flex space-x-1">
        <?php for ($i = 1; $i <= $last_page; $i++): ?>
        <a href="?page=<?= $i ?>&filter=<?= $filter ?>" class="px-3 py-2 rounded <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-400 hover:bg-dark-700' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-envelope-open text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun message.</p>
    <a href="<?= SITE_URL ?>/admin/messages/composer" class="inline-block mt-4 text-gold-500 hover:text-gold-400">Envoyer un message</a>
</div>
<?php endif; ?>
