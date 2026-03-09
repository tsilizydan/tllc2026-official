<?php
/**
 * TSILIZY LLC — Admin Notifications Index View
 */

Core\View::layout('admin', ['page_title' => 'Notifications']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Notifications</h2>
        <p class="text-dark-400 text-sm"><?= $unread ?> non lue<?= $unread > 1 ? 's' : '' ?> sur <?= $total ?></p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <form action="<?= SITE_URL ?>/admin/notifications/lues" method="POST">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-4 py-2 bg-dark-700 text-dark-300 rounded-lg hover:bg-dark-600"><i class="fas fa-check-double mr-2"></i> Tout marquer lu</button>
        </form>
        <form action="<?= SITE_URL ?>/admin/notifications/nettoyer" method="POST" onsubmit="return confirm('Supprimer les anciennes notifications lues ?')">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-4 py-2 bg-dark-700 text-dark-300 rounded-lg hover:bg-dark-600"><i class="fas fa-broom mr-2"></i> Nettoyer</button>
        </form>
        <a href="<?= SITE_URL ?>/admin/notifications/creer" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg text-center"><i class="fas fa-plus mr-2"></i> Envoyer</a>
    </div>
</div>

<?php if (!empty($notifications)): ?>
<div class="space-y-3">
    <?php foreach ($notifications as $n): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 flex items-start gap-4 <?= !$n['is_read'] ? 'border-l-4 border-l-gold-500' : '' ?>">
        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 <?php
            switch($n['type']) {
                case 'success': echo 'bg-green-500/20 text-green-400'; break;
                case 'warning': echo 'bg-yellow-500/20 text-yellow-400'; break;
                case 'error': echo 'bg-red-500/20 text-red-400'; break;
                default: echo 'bg-blue-500/20 text-blue-400';
            }
        ?>">
            <i class="fas <?php
                switch($n['type']) {
                    case 'success': echo 'fa-check'; break;
                    case 'warning': echo 'fa-exclamation'; break;
                    case 'error': echo 'fa-times'; break;
                    default: echo 'fa-info';
                }
            ?>"></i>
        </div>
        
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h4 class="font-medium text-white"><?= Core\View::e($n['title']) ?></h4>
                    <?php if ($n['message']): ?>
                    <p class="text-dark-400 text-sm mt-1"><?= Core\View::e($n['message']) ?></p>
                    <?php endif; ?>
                </div>
                <span class="text-dark-500 text-xs whitespace-nowrap"><?= Core\View::timeAgo($n['created_at']) ?></span>
            </div>
            
            <div class="flex items-center gap-3 mt-3">
                <?php if ($n['link']): ?>
                <a href="<?= Core\View::e($n['link']) ?>" class="text-sm text-gold-500 hover:text-gold-400"><i class="fas fa-external-link-alt mr-1"></i> Voir</a>
                <?php endif; ?>
                <?php if (!$n['is_read']): ?>
                <form action="<?= SITE_URL ?>/admin/notifications/<?= $n['id'] ?>/lu" method="POST" class="inline">
                    <?= Core\CSRF::field() ?>
                    <button type="submit" class="text-sm text-dark-400 hover:text-white"><i class="fas fa-check mr-1"></i> Marquer lu</button>
                </form>
                <?php endif; ?>
                <span class="text-dark-600">•</span>
                <span class="text-xs text-dark-500"><?= $n['user_name'] ?? 'Tous' ?></span>
            </div>
        </div>
        
        <form action="<?= SITE_URL ?>/admin/notifications/<?= $n['id'] ?>/supprimer" method="POST" onsubmit="return confirm('Supprimer ?')">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="text-dark-500 hover:text-red-400"><i class="fas fa-trash"></i></button>
        </form>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($last_page > 1): ?>
<div class="flex justify-center mt-6">
    <nav class="flex space-x-1">
        <?php for ($i = 1; $i <= $last_page; $i++): ?>
        <a href="?page=<?= $i ?>" class="px-3 py-2 rounded <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-400 hover:bg-dark-700' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-bell-slash text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucune notification.</p>
</div>
<?php endif; ?>
