<?php
/**
 * TSILIZY LLC — Event Detail View
 */

Core\View::layout('public', ['page_title' => $event['title'] ?? 'Événement']);
?>

<section class="py-24 bg-dark-950">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <a href="<?= SITE_URL ?>/evenements" class="text-dark-400 hover:text-gold-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Tous les événements
            </a>
        </nav>
        
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Content -->
            <div class="lg:col-span-2">
                <header class="mb-8">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php if ($event['category']): ?>
                        <span class="px-3 py-1 bg-gold-500/20 text-gold-500 text-sm rounded-full"><?= Core\View::e($event['category']) ?></span>
                        <?php endif; ?>
                        <?php if ($event['is_online']): ?>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full"><i class="fas fa-video mr-1"></i> En ligne</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4"><?= Core\View::e($event['title']) ?></h1>
                    <?php if ($event['short_description']): ?>
                    <p class="text-xl text-dark-400"><?= Core\View::e($event['short_description']) ?></p>
                    <?php endif; ?>
                </header>
                
                <!-- Featured Image -->
                <?php if ($event['image']): ?>
                <div class="mb-8 rounded-2xl overflow-hidden">
                    <img src="<?= Core\View::upload($event['image']) ?>" alt="<?= Core\View::e($event['title']) ?>" class="w-full">
                </div>
                <?php endif; ?>
                
                <div class="prose prose-lg prose-invert max-w-none">
                    <?= $event['description'] ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <aside>
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 sticky top-24">
                    <div class="text-center mb-6 pb-6 border-b border-dark-700">
                        <div class="w-20 h-20 mx-auto bg-gold-500/10 rounded-2xl flex flex-col items-center justify-center mb-4">
                            <span class="text-3xl font-bold text-gold-500"><?= date('d', strtotime($event['start_date'])) ?></span>
                            <span class="text-sm text-dark-400 uppercase"><?= strftime('%b', strtotime($event['start_date'])) ?></span>
                        </div>
                        <p class="text-white font-medium"><?= date('H:i', strtotime($event['start_date'])) ?></p>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <?php if ($event['location']): ?>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-gold-500 mt-1 w-5"></i>
                            <div>
                                <span class="text-dark-500 text-sm block">Lieu</span>
                                <span class="text-white"><?= Core\View::e($event['location']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($event['price']): ?>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-euro-sign text-gold-500 mt-1 w-5"></i>
                            <div>
                                <span class="text-dark-500 text-sm block">Tarif</span>
                                <span class="text-white"><?= Core\View::currency($event['price']) ?></span>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-gift text-green-400 mt-1 w-5"></i>
                            <span class="text-green-400 font-medium">Gratuit</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    $isPast = strtotime($event['start_date']) < time();
                    ?>
                    <?php if (!$isPast): ?>
                    <a href="<?= SITE_URL ?>/contact?event=<?= urlencode($event['title']) ?>" 
                       class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-calendar-check mr-2"></i> S'inscrire
                    </a>
                    <?php else: ?>
                    <div class="text-center text-dark-500 py-4">
                        <i class="fas fa-clock text-2xl mb-2 block"></i>
                        Événement passé
                    </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Related Events -->
<?php if (!empty($related)): ?>
<section class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-white mb-8">Autres événements</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($related as $item): ?>
            <a href="<?= SITE_URL ?>/evenements/<?= $item['slug'] ?>" class="group bg-dark-800 border border-dark-700 rounded-xl overflow-hidden hover:border-gold-500/50 transition-all">
                <div class="p-6">
                    <p class="text-gold-500 text-sm mb-2"><?= Core\View::date($item['start_date']) ?></p>
                    <h3 class="text-lg font-semibold text-white group-hover:text-gold-500 transition-colors"><?= Core\View::e($item['title']) ?></h3>
                    <p class="text-dark-500 text-sm mt-2"><?= Core\View::e($item['location'] ?? 'En ligne') ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
