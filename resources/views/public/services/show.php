<?php
/**
 * TSILIZY LLC — Service Detail Page
 */

Core\View::layout('public', [
    'page_title' => $service['title'],
    'seo_title' => $seo_title ?? $service['title'] . ' | ' . SITE_NAME,
    'seo_description' => $seo_description ?? ''
]);
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="<?= SITE_URL ?>/services" class="inline-flex items-center text-dark-400 hover:text-gold-500 transition-colors mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux services
        </a>
        
        <div class="flex items-start space-x-6">
            <div class="w-20 h-20 bg-gradient-to-br from-gold-500/20 to-gold-600/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fas <?= Core\View::e($service['icon'] ?? 'fa-star') ?> text-3xl text-gold-500"></i>
            </div>
            <div>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">
                    <?= Core\View::e($service['title']) ?>
                </h1>
                <?php if ($service['short_description']): ?>
                <p class="text-xl text-dark-400">
                    <?= Core\View::e($service['short_description']) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <?php if ($service['image']): ?>
                <div class="mb-8 rounded-2xl overflow-hidden">
                    <img src="<?= Core\View::upload($service['image']) ?>" alt="<?= Core\View::e($service['title']) ?>" class="w-full">
                </div>
                <?php endif; ?>
                
                <div class="prose prose-invert prose-lg max-w-none">
                    <?= $service['description'] ?>
                </div>
                
                <?php 
                $features = $service['features'] ? json_decode($service['features'], true) : [];
                if (!empty($features)): 
                ?>
                <div class="mt-12">
                    <h2 class="text-2xl font-serif font-bold text-white mb-6">Caractéristiques</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php foreach ($features as $feature): ?>
                        <div class="flex items-start space-x-3 p-4 bg-dark-800 rounded-lg border border-dark-700">
                            <i class="fas fa-check-circle text-gold-500 mt-1"></i>
                            <span class="text-dark-300"><?= Core\View::e($feature) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Pricing Card -->
                <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 sticky top-24">
                    <?php if ($service['price']): ?>
                    <div class="text-center mb-6">
                        <p class="text-dark-400 text-sm">À partir de</p>
                        <p class="text-4xl font-bold text-gold-500"><?= Core\View::currency($service['price']) ?></p>
                        <?php if ($service['duration']): ?>
                        <p class="text-dark-500">/ <?= Core\View::e($service['duration']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?= SITE_URL ?>/contact?service=<?= urlencode($service['title']) ?>" class="block w-full py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg text-center hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i> Demander un devis
                    </a>
                    
                    <p class="text-dark-500 text-sm text-center mt-4">
                        Réponse sous 24h
                    </p>
                </div>
                
                <!-- Contact Card -->
                <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Besoin d'aide ?</h3>
                    <p class="text-dark-400 text-sm mb-4">
                        Notre équipe est disponible pour répondre à vos questions.
                    </p>
                    <a href="tel:<?= SITE_PHONE ?>" class="flex items-center text-gold-500 hover:text-gold-400 transition-colors">
                        <i class="fas fa-phone mr-3"></i>
                        <?= SITE_PHONE ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services -->
<?php if (!empty($related_services)): ?>
<section class="py-16 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-serif font-bold text-white mb-8">Autres services</h2>
        
        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($related_services as $related): ?>
            <a href="<?= SITE_URL ?>/services/<?= Core\View::e($related['slug']) ?>" class="group bg-dark-800 border border-dark-700 rounded-xl p-6 hover:border-gold-500/50 transition-all duration-300">
                <div class="w-12 h-12 bg-gold-500/20 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas <?= Core\View::e($related['icon'] ?? 'fa-star') ?> text-gold-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-gold-500 transition-colors">
                    <?= Core\View::e($related['title']) ?>
                </h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
