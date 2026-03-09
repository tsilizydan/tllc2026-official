<?php
/**
 * TSILIZY LLC — Admin Announcements Index View
 */

Core\View::layout('admin', ['page_title' => 'Annonces']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-white">Annonces</h2>
    <a href="<?= SITE_URL ?>/admin/annonces/creer" class="inline-flex items-center px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouvelle annonce</a>
</div>

<?php if (!empty($announcements)): ?>
<div class="space-y-4">
    <?php foreach ($announcements as $a): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 <?php
                switch($a['type']) {
                    case 'success': echo 'bg-green-500/20 text-green-400'; break;
                    case 'warning': echo 'bg-yellow-500/20 text-yellow-400'; break;
                    case 'urgent': echo 'bg-red-500/20 text-red-400'; break;
                    default: echo 'bg-blue-500/20 text-blue-400';
                }
            ?>">
                <i class="fas <?php
                    switch($a['type']) {
                        case 'success': echo 'fa-check'; break;
                        case 'warning': echo 'fa-exclamation'; break;
                        case 'urgent': echo 'fa-bullhorn'; break;
                        default: echo 'fa-info';
                    }
                ?>"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold text-white"><?= Core\View::e($a['title']) ?></h3>
                    <?php if ($a['is_pinned']): ?>
                    <span class="px-2 py-0.5 text-xs bg-gold-500/20 text-gold-500 rounded">Épinglé</span>
                    <?php endif; ?>
                </div>
                <p class="text-dark-400 text-sm mt-1 line-clamp-2"><?= Core\View::e($a['excerpt'] ?: strip_tags($a['content'])) ?></p>
                <p class="text-dark-500 text-xs mt-2">Créé le <?= Core\View::date($a['created_at']) ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2 py-1 text-xs rounded-full <?= $a['status'] === 'published' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                <?= $a['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
            </span>
            <a href="<?= SITE_URL ?>/admin/annonces/<?= $a['id'] ?>/modifier" class="px-3 py-2 bg-dark-700 text-gold-500 hover:bg-dark-600 rounded-lg"><i class="fas fa-edit"></i></a>
            <form action="<?= SITE_URL ?>/admin/annonces/<?= $a['id'] ?>/supprimer" method="POST" onsubmit="return confirm('Supprimer ?')">
                <?= Core\CSRF::field() ?><button type="submit" class="px-3 py-2 bg-dark-700 text-red-400 hover:bg-dark-600 rounded-lg"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-bullhorn text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucune annonce.</p>
    <a href="<?= SITE_URL ?>/admin/annonces/creer" class="inline-block mt-4 text-gold-500 hover:text-gold-400">Créer une annonce</a>
</div>
<?php endif; ?>
