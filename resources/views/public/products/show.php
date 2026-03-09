<?php
/**
 * TSILIZY LLC — Product Detail Page
 */

Core\View::layout('public', ['page_title' => $product['name']]);
?>

<section class="py-24 bg-dark-950">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <a href="<?= SITE_URL ?>/produits" class="text-dark-400 hover:text-gold-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Tous les produits
            </a>
        </nav>
        
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Image -->
            <div>
                <?php if ($product['image']): ?>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= Core\View::upload($product['image']) ?>" alt="<?= Core\View::e($product['name']) ?>" class="w-full">
                </div>
                <?php else: ?>
                <div class="aspect-square bg-dark-800 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-box text-6xl text-dark-600"></i>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Content -->
            <div>
                <?php if ($product['category']): ?>
                <span class="px-3 py-1 bg-gold-500/20 text-gold-500 text-sm rounded-full"><?= Core\View::e($product['category']) ?></span>
                <?php endif; ?>
                
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mt-4 mb-4"><?= Core\View::e($product['name']) ?></h1>
                
                <?php if ($product['short_description']): ?>
                <p class="text-xl text-dark-400 mb-6"><?= Core\View::e($product['short_description']) ?></p>
                <?php endif; ?>
                
                <?php if ($product['price']): ?>
                <div class="mb-8">
                    <span class="text-4xl font-bold text-gold-500"><?= Core\View::currency($product['price']) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="prose prose-invert max-w-none mb-8">
                    <?= $product['description'] ?>
                </div>
                
                <a href="<?= SITE_URL ?>/contact?produit=<?= urlencode($product['name']) ?>" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl hover:shadow-lg transition-all">
                    <i class="fas fa-paper-plane mr-2"></i> Demander un devis
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($related)): ?>
<section class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-white mb-8">Produits similaires</h2>
        <div class="grid md:grid-cols-4 gap-6">
            <?php foreach ($related as $item): ?>
            <a href="<?= SITE_URL ?>/produits/<?= $item['slug'] ?>" class="group bg-dark-800 border border-dark-700 rounded-xl overflow-hidden hover:border-gold-500/50 transition-all">
                <div class="h-32 overflow-hidden">
                    <?php if ($item['image']): ?>
                    <img src="<?= Core\View::upload($item['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    <?php else: ?>
                    <div class="w-full h-full bg-dark-700 flex items-center justify-center"><i class="fas fa-box text-dark-500"></i></div>
                    <?php endif; ?>
                </div>
                <div class="p-4">
                    <h4 class="font-medium text-white group-hover:text-gold-500 transition-colors"><?= Core\View::e($item['name']) ?></h4>
                    <?php if ($item['price']): ?>
                    <p class="text-gold-500 mt-1"><?= Core\View::currency($item['price']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
