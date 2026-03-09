<?php
/**
 * TSILIZY LLC — User Dashboard Layout
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?= $page_title ?? 'Mon Compte' ?> | <?= SITE_NAME ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#FFFBEB', 100: '#FEF3C7', 200: '#FDE68A', 300: '#FCD34D',
                            400: '#FBBF24', 500: '#C9A227', 600: '#B8860B', 700: '#92700C'
                        },
                        dark: {
                            50: '#F8FAFC', 100: '#F1F5F9', 200: '#E2E8F0', 300: '#CBD5E1',
                            400: '#94A3B8', 500: '#64748B', 600: '#475569', 700: '#334155',
                            800: '#1E293B', 900: '#0F172A', 950: '#020617'
                        }
                    },
                    fontFamily: {
                        'serif': ['Cormorant Garamond', 'Georgia', 'serif'],
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <?= Core\CSRF::meta() ?>
    
    <style>
        .text-gold-gradient {
            background: linear-gradient(135deg, #C9A227 0%, #FDE68A 50%, #C9A227 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-dark-950 text-white font-sans antialiased">
    
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-dark-950/90 backdrop-blur-lg border-b border-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="<?= SITE_URL ?>" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center">
                        <span class="text-dark-950 font-serif font-bold text-lg">T</span>
                    </div>
                    <span class="text-lg font-serif font-semibold text-gold-500"><?= SITE_NAME ?></span>
                </a>
                
                <!-- User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-3 hover:bg-dark-800 rounded-lg px-3 py-2 transition-colors">
                        <div class="w-8 h-8 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 font-semibold">
                            <?= strtoupper(substr($current_user['first_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="hidden md:block text-sm"><?= Core\View::e($current_user['first_name'] ?? 'Utilisateur') ?></span>
                        <i class="fas fa-chevron-down text-xs text-dark-400"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-xl">
                        <a href="<?= SITE_URL ?>/mon-compte/profil" class="flex items-center px-4 py-3 hover:bg-dark-700">
                            <i class="fas fa-user w-4 mr-3 text-dark-400"></i>
                            <span class="text-sm">Mon Profil</span>
                        </a>
                        <?php if (Core\Auth::isAdmin()): ?>
                        <a href="<?= SITE_URL ?>/admin" class="flex items-center px-4 py-3 hover:bg-dark-700">
                            <i class="fas fa-cog w-4 mr-3 text-dark-400"></i>
                            <span class="text-sm">Administration</span>
                        </a>
                        <?php endif; ?>
                        <div class="border-t border-dark-700"></div>
                        <form action="<?= SITE_URL ?>/deconnexion" method="POST">
                            <?= Core\CSRF::field() ?>
                            <button type="submit" class="flex items-center w-full px-4 py-3 hover:bg-dark-700 text-red-400">
                                <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                                <span class="text-sm">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="flex pt-16">
        <!-- Sidebar -->
        <aside class="w-64 h-[calc(100vh-4rem)] bg-dark-900 border-r border-dark-800 fixed left-0 hidden lg:block overflow-y-auto">
            <nav class="p-4 space-y-1">
                <a href="<?= SITE_URL ?>/mon-compte" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= Core\View::isActive('mon-compte') ? 'bg-gold-500/10 text-gold-500 border-l-2 border-gold-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white' ?> transition-colors">
                    <i class="fas fa-home w-5"></i>
                    <span>Tableau de bord</span>
                </a>
                
                <a href="<?= SITE_URL ?>/mon-compte/profil" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= Core\View::isActive('profil') ? 'bg-gold-500/10 text-gold-500 border-l-2 border-gold-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white' ?> transition-colors">
                    <i class="fas fa-user w-5"></i>
                    <span>Mon Profil</span>
                </a>
                
                <a href="<?= SITE_URL ?>/mon-compte/tickets" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= Core\View::isActive('tickets') ? 'bg-gold-500/10 text-gold-500 border-l-2 border-gold-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white' ?> transition-colors">
                    <i class="fas fa-headset w-5"></i>
                    <span>Mes Tickets</span>
                </a>
                
                <a href="<?= SITE_URL ?>/mon-compte/contrats" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= Core\View::isActive('contrats') ? 'bg-gold-500/10 text-gold-500 border-l-2 border-gold-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white' ?> transition-colors">
                    <i class="fas fa-file-contract w-5"></i>
                    <span>Mes Contrats</span>
                </a>
                
                <a href="<?= SITE_URL ?>/mon-compte/factures" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= Core\View::isActive('factures') ? 'bg-gold-500/10 text-gold-500 border-l-2 border-gold-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white' ?> transition-colors">
                    <i class="fas fa-file-invoice-dollar w-5"></i>
                    <span>Mes Factures</span>
                </a>
                
                <div class="pt-6">
                    <a href="<?= SITE_URL ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-dark-400 hover:bg-dark-800 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left w-5"></i>
                        <span>Retour au site</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 p-6 min-h-[calc(100vh-4rem)]">
            
            <!-- Flash Messages -->
            <?php $flash = Core\Session::getAllFlash(); ?>
            <?php if (!empty($flash)): ?>
            <div class="mb-6 space-y-2" x-data="{ show: true }" x-show="show">
                <?php foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color): ?>
                    <?php if (isset($flash[$type])): ?>
                    <div class="flex items-center justify-between px-4 py-3 bg-<?= $color ?>-500/10 border border-<?= $color ?>-500/30 text-<?= $color ?>-400 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-<?= $type === 'success' ? 'check' : ($type === 'error' ? 'exclamation' : 'info') ?>-circle mr-3"></i>
                            <span><?= Core\View::e($flash[$type]) ?></span>
                        </div>
                        <button @click="show = false" class="hover:text-white"><i class="fas fa-times"></i></button>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <?= $content ?? '' ?>
            
        </main>
    </div>
    
</body>
</html>
