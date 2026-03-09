<?php
/**
 * TSILIZY LLC — Admin Media Index View
 */

Core\View::layout('admin', ['page_title' => 'Médias']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-white">Médias</h2>
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-upload mr-2"></i> Télécharger</button>
</div>

<!-- Folder Filter -->
<div class="mb-6 flex flex-wrap gap-2">
    <a href="<?= SITE_URL ?>/admin/medias" class="px-3 py-1.5 rounded-lg text-sm <?= $current_folder === 'all' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">Tous</a>
    <?php foreach ($folders as $f): ?>
    <a href="<?= SITE_URL ?>/admin/medias?folder=<?= urlencode($f) ?>" class="px-3 py-1.5 rounded-lg text-sm <?= $current_folder === $f ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>"><?= Core\View::e(ucfirst($f)) ?></a>
    <?php endforeach; ?>
</div>

<?php if (!empty($media)): ?>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    <?php foreach ($media as $m): ?>
    <div class="group relative bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
        <?php if (strpos($m['mime_type'], 'image/') === 0): ?>
        <img src="<?= SITE_URL ?>/uploads/<?= Core\View::e($m['path']) ?>" alt="<?= Core\View::e($m['original_name']) ?>" class="w-full h-32 object-cover">
        <?php else: ?>
        <div class="w-full h-32 flex items-center justify-center bg-dark-700">
            <i class="fas <?= strpos($m['mime_type'], 'video/') === 0 ? 'fa-video' : 'fa-file-pdf' ?> text-3xl text-dark-500"></i>
        </div>
        <?php endif; ?>
        
        <div class="p-3">
            <p class="text-sm text-white truncate"><?= Core\View::e($m['original_name']) ?></p>
            <p class="text-xs text-dark-500"><?= number_format($m['size'] / 1024, 1) ?> Ko</p>
        </div>
        
        <div class="absolute inset-0 bg-dark-900/80 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-3 transition-opacity">
            <button onclick="copyUrl('<?= SITE_URL ?>/uploads/<?= $m['path'] ?>')" class="w-10 h-10 bg-dark-700 rounded-full text-white hover:bg-gold-500 hover:text-dark-950"><i class="fas fa-link"></i></button>
            <form action="<?= SITE_URL ?>/admin/medias/<?= $m['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                <?= Core\CSRF::field() ?><button type="submit" class="w-10 h-10 bg-dark-700 rounded-full text-red-400 hover:bg-red-500 hover:text-white"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-images text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun média.</p>
</div>
<?php endif; ?>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-dark-800 border border-dark-700 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-white">Télécharger un fichier</h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-dark-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= SITE_URL ?>/admin/medias/telecharger" method="POST" enctype="multipart/form-data">
            <?= Core\CSRF::field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-dark-300 mb-2">Fichier</label>
                <input type="file" name="file" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                <p class="text-xs text-dark-500 mt-1">Max 10 Mo. Images, PDF, vidéos.</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-dark-300 mb-2">Dossier</label>
                <input type="text" name="folder" value="general" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
            </div>
            <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-upload mr-2"></i> Télécharger</button>
        </form>
    </div>
</div>

<script>
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => alert('URL copiée !'));
}
</script>
