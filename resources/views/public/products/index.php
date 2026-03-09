<?php
/**
 * TSILIZY LLC — Products Listing Page
 */

Core\View::layout('public', ['page_title' => 'Produits']);
?>

<!-- Hero -->
<section class="relative py-24 bg-dark-950">
    <div class="absolute inset-0 bg-gradient-to-br from-gold-500/5 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/30 rounded-full text-gold-500 text-sm font-medium mb-6">
            <i class="fas fa-box mr-2"></i> Nos Produits
        </span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Produits de qualité</h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">Découvrez notre gamme de produits conçus pour répondre à vos besoins.</p>
    </div>
</section>

<!-- Products Grid -->
<section class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <?php if (!empty($products)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($products as $product): ?>
            <article class="group bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden hover:border-gold-500/50 transition-all duration-500">
                <div class="relative h-48 overflow-hidden">
                    <?php if ($product['image']): ?>
                    <img src="<?= Core\View::upload($product['image']) ?>" alt="<?= Core\View::e($product['name']) ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-gold-500/20 to-dark-700 flex items-center justify-center">
                        <i class="fas fa-box text-4xl text-dark-500"></i>
                    </div>
                    <?php endif; ?>
                    <?php if ($product['is_featured']): ?>
                    <div class="absolute top-4 left-4 px-3 py-1 bg-gold-500 text-dark-950 text-xs font-semibold rounded-full">
                        Populaire
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <?php if ($product['category']): ?>
                    <span class="text-gold-500 text-sm"><?= Core\View::e($product['category']) ?></span>
                    <?php endif; ?>
                    <h3 class="text-lg font-semibold text-white mt-1 mb-2 group-hover:text-gold-500 transition-colors">
                        <?= Core\View::e($product['name']) ?>
                    </h3>
                    <p class="text-dark-400 text-sm mb-4 line-clamp-2"><?= Core\View::e($product['short_description'] ?? '') ?></p>
                    
                    <div class="flex items-center justify-between">
                        <?php if ($product['price']): ?>
                        <span class="text-xl font-bold text-gold-500"><?= Core\View::currency($product['price']) ?></span>
                        <?php endif; ?>
                        <a href="<?= SITE_URL ?>/produits/<?= $product['slug'] ?>" 
                           class="text-gold-500 hover:text-gold-400 font-medium transition-colors">
                            Détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-box-open text-5xl text-dark-600 mb-4 block"></i>
            <h3 class="text-white text-xl font-semibold mb-2">Aucun produit disponible</h3>
            <p class="text-dark-400">Nos produits seront bientôt disponibles. Revenez nous voir !</p>
        </div>
        <?php endif; ?>
    </div>
</section>
