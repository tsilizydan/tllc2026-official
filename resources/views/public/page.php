<?php
/**
 * TSILIZY LLC — Dynamic Page View
 */

Core\View::layout('public', [
    'page_title' => $page['title'],
    'seo_title' => $seo_title ?? $page['title'] . ' | ' . SITE_NAME,
    'seo_description' => $seo_description ?? ''
]);
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            <?= Core\View::e($page['title']) ?>
        </h1>
        <?php if ($page['excerpt']): ?>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">
            <?= Core\View::e($page['excerpt']) ?>
        </p>
        <?php endif; ?>
    </div>
</section>

<!-- Content Section -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($page['featured_image']): ?>
        <div class="mb-12 rounded-2xl overflow-hidden">
            <img src="<?= Core\View::upload($page['featured_image']) ?>" alt="<?= Core\View::e($page['title']) ?>" class="w-full">
        </div>
        <?php endif; ?>
        
        <div class="prose prose-invert prose-lg max-w-none">
            <?= $page['content'] ?>
        </div>
    </div>
</section>
