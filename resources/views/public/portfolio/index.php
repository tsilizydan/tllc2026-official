<?php
/**
 * TSILIZY LLC — Portfolio Listing Page
 */

Core\View::layout('public', [
    'page_title' => 'Portfolio',
    'seo_title' => $seo_title ?? 'Portfolio | ' . SITE_NAME,
    'seo_description' => $seo_description ?? ''
]);
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
            Portfolio
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            Nos <span class="text-gold-gradient">réalisations</span>
        </h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">
            Explorez notre portfolio et découvrez comment nous avons aidé nos clients à atteindre leurs objectifs.
        </p>
    </div>
</section>

<!-- Filter Section -->
<?php if (!empty($categories)): ?>
<section class="py-8 bg-dark-900/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?= SITE_URL ?>/portfolio" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$current_category ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:text-white hover:bg-dark-700' ?> transition-colors">
                Tous
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= SITE_URL ?>/portfolio?categorie=<?= urlencode($cat) ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $current_category === $cat ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:text-white hover:bg-dark-700' ?> transition-colors">
                <?= Core\View::e(ucfirst($cat)) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Portfolio Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($items)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($items as $item): ?>
            <div class="group relative bg-dark-800 rounded-2xl overflow-hidden border border-dark-700 hover:border-gold-500/50 transition-all duration-500">
                <!-- Image -->
                <div class="aspect-video bg-gradient-to-br from-gold-500/20 to-gold-600/10 relative overflow-hidden">
                    <?php if ($item['image']): ?>
                    <img src="<?= Core\View::upload($item['image']) ?>" alt="<?= Core\View::e($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-5xl text-gold-500/30"></i>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-dark-950/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <a href="<?= SITE_URL ?>/portfolio/<?= Core\View::e($item['slug']) ?>" class="w-12 h-12 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <?php if ($item['category']): ?>
                    <span class="text-xs text-gold-500 uppercase tracking-wider"><?= Core\View::e($item['category']) ?></span>
                    <?php endif; ?>
                    <h3 class="text-lg font-semibold text-white mt-2 mb-2 group-hover:text-gold-500 transition-colors">
                        <?= Core\View::e($item['title']) ?>
                    </h3>
                    <?php if ($item['short_description']): ?>
                    <p class="text-dark-400 text-sm line-clamp-2">
                        <?= Core\View::e($item['short_description']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ($item['client_name']): ?>
                    <div class="mt-4 pt-4 border-t border-dark-700 flex items-center justify-between">
                        <span class="text-dark-500 text-sm">Client: <?= Core\View::e($item['client_name']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-briefcase text-5xl text-dark-600 mb-4"></i>
            <p class="text-dark-400">Aucun projet trouvé<?= $current_category ? ' dans cette catégorie' : '' ?>.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-gold-600/20 to-gold-500/10 rounded-2xl p-12 border border-gold-500/20">
            <h2 class="text-3xl font-serif font-bold text-white mb-4">
                Vous avez un projet en tête ?
            </h2>
            <p class="text-dark-300 mb-8">
                Discutons ensemble de votre vision et transformons-la en réalité.
            </p>
            <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i> Discuter de mon projet
            </a>
        </div>
    </div>
</section>
