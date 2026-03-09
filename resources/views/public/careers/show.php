<?php
/**
 * TSILIZY LLC — Public Job Detail
 */

Core\View::layout('public', ['page_title' => $job['title']]);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<section class="py-24 bg-dark-950">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <a href="<?= SITE_URL ?>/carrieres" class="text-dark-400 hover:text-gold-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Toutes les offres
            </a>
        </nav>
        
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Content -->
            <div class="lg:col-span-2">
                <header class="mb-8">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php if ($job['department']): ?>
                        <span class="px-3 py-1 bg-gold-500/20 text-gold-500 text-sm rounded-full"><?= Core\View::e($job['department']) ?></span>
                        <?php endif; ?>
                        <span class="px-3 py-1 bg-dark-700 text-dark-300 text-sm rounded-full"><?php echo match($job['employment_type'] ?? '') { 'full_time' => 'Temps plein', 'part_time' => 'Temps partiel', 'contract' => 'Contrat', 'internship' => 'Stage', default => '-' }; ?></span>
                        <?php if ($job['is_remote']): ?>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full">Remote</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4"><?= Core\View::e($job['title']) ?></h1>
                    <div class="flex flex-wrap gap-6 text-dark-400">
                        <span><i class="fas fa-map-marker-alt mr-2 text-gold-500"></i> <?= Core\View::e($job['location'] ?? 'Non spécifié') ?></span>
                        <?php if ($job['salary_min'] && $job['salary_max']): ?>
                        <span><i class="fas fa-euro-sign mr-2 text-gold-500"></i> <?= number_format($job['salary_min']) ?>€ - <?= number_format($job['salary_max']) ?>€ / an</span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <div class="space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Description du poste</h2>
                        <div class="prose prose-invert max-w-none"><?= $job['description'] ?></div>
                    </div>
                    
                    <?php if ($job['requirements']): ?>
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Profil recherché</h2>
                        <div class="prose prose-invert max-w-none"><?= $job['requirements'] ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($job['benefits']): ?>
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Avantages</h2>
                        <div class="prose prose-invert max-w-none"><?= $job['benefits'] ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Application Form -->
            <aside>
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-white mb-6">Postuler</h3>
                    
                    <form action="<?= SITE_URL ?>/carrieres/<?= $job['slug'] ?>/postuler" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <?= Core\CSRF::field() ?>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="first_name" required placeholder="Prénom *" value="<?= Core\View::e(Core\Session::old('first_name')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border <?= isset($errors['first_name']) ? 'border-red-500' : 'border-dark-700' ?> rounded-lg text-white focus:border-gold-500">
                            </div>
                            <div>
                                <input type="text" name="last_name" required placeholder="Nom *" value="<?= Core\View::e(Core\Session::old('last_name')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border <?= isset($errors['last_name']) ? 'border-red-500' : 'border-dark-700' ?> rounded-lg text-white focus:border-gold-500">
                            </div>
                        </div>
                        
                        <input type="email" name="email" required placeholder="Email *" value="<?= Core\View::e(Core\Session::old('email')) ?>"
                               class="w-full px-4 py-3 bg-dark-800 border <?= isset($errors['email']) ? 'border-red-500' : 'border-dark-700' ?> rounded-lg text-white focus:border-gold-500">
                        
                        <input type="tel" name="phone" required placeholder="Téléphone *" value="<?= Core\View::e(Core\Session::old('phone')) ?>"
                               class="w-full px-4 py-3 bg-dark-800 border <?= isset($errors['phone']) ? 'border-red-500' : 'border-dark-700' ?> rounded-lg text-white focus:border-gold-500">
                        
                        <textarea name="message" rows="4" required placeholder="Lettre de motivation *"
                                  class="w-full px-4 py-3 bg-dark-800 border <?= isset($errors['message']) ? 'border-red-500' : 'border-dark-700' ?> rounded-lg text-white resize-none focus:border-gold-500"><?= Core\View::e(Core\Session::old('message')) ?></textarea>
                        
                        <div>
                            <label class="block text-dark-400 text-sm mb-2">CV (PDF, DOC)</label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx" class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dark-700 file:text-white hover:file:bg-dark-600">
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl hover:shadow-lg transition-all">
                            <i class="fas fa-paper-plane mr-2"></i> Envoyer ma candidature
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</section>
