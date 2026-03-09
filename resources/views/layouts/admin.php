<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?= $page_title ?? 'Administration' ?> | <?= SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            300: '#FCD34D',
                            400: '#FBBF24',
                            500: '#C9A227',
                            600: '#B8860B',
                            700: '#92700C'
                        },
                        dark: {
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A',
                            950: '#020617'
                        }
                    },
                    fontFamily: {
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
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/q96zzqz4inb66gof20x44rx2hi6vdo7x980dqw1vc0ymu3io/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    
    <!-- CSRF Token -->
    <?= Core\CSRF::meta() ?>
    
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1E293B; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748B; }
        
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(201, 162, 39, 0.1) 0%, transparent 100%);
            border-left: 3px solid #C9A227;
            color: #C9A227;
        }
        
        [x-cloak] { display: none !important; }
        
        /* Print Styles */
        @media print {
            body { background: white !important; color: black !important; }
            aside, header, .no-print, button, form { display: none !important; }
            main { padding: 0 !important; overflow: visible !important; }
            .flex-1 { display: block !important; }
            .print-header { display: block !important; text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #C9A227; padding-bottom: 1rem; }
            .print-header h1 { font-size: 1.5rem; font-weight: bold; color: #1a1a1a !important; }
            .print-header p { color: #666 !important; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background: #f3f4f6; }
            .bg-dark-800, .bg-dark-900 { background: white !important; border: 1px solid #e5e7eb !important; }
            * { color: #1a1a1a !important; }
        }
        .print-header { display: none; }
    </style>
    
    <?php if (isset($extra_css)): ?>
    <?= $extra_css ?>
    <?php endif; ?>
</head>
<body class="bg-dark-950 text-white font-sans antialiased" x-data="{ mobileMenuOpen: false }">
    
    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 z-40 lg:hidden" x-transition.opacity x-cloak></div>
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-900 border-r border-dark-800 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto" 
               :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            
            <!-- Close button on mobile -->
            <button @click="mobileMenuOpen = false" class="lg:hidden absolute top-4 right-4 text-dark-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <!-- Logo -->
            <div class="h-16 flex items-center px-4 border-b border-dark-800">
                <a href="<?= SITE_URL ?>/admin" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-dark-950 font-bold">T</span>
                    </div>
                    <span class="text-lg font-semibold text-gold-500">Admin</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
                
                <!-- Dashboard -->
                <a href="<?= SITE_URL ?>/admin" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors <?= Core\View::isActive('admin') || Core\View::isActive('admin/tableau-de-bord') ? 'active' : '' ?>">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Tableau de bord</span>
                </a>
                
                <!-- Content Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Contenu</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/pages" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-file-alt w-5 text-center"></i>
                    <span>Pages</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/medias" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-images w-5 text-center"></i>
                    <span>Médias</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/annonces" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-bullhorn w-5 text-center"></i>
                    <span>Annonces</span>
                </a>
                
                <!-- Business Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Business</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/services" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-concierge-bell w-5 text-center"></i>
                    <span>Services</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/clients" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-user-tie w-5 text-center"></i>
                    <span>Clients</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/produits" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span>Produits</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/portfolio" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-briefcase w-5 text-center"></i>
                    <span>Portfolio</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/partenaires" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-handshake w-5 text-center"></i>
                    <span>Partenaires</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/evenements" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-calendar-alt w-5 text-center"></i>
                    <span>Événements</span>
                </a>
                
                <!-- Career Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Carrières</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/carrieres" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-user-tie w-5 text-center"></i>
                    <span>Offres d'emploi</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/candidatures" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-file-signature w-5 text-center"></i>
                    <span>Candidatures</span>
                </a>
                
                <!-- Documents Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Documents</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/contrats" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-file-contract w-5 text-center"></i>
                    <span>Contrats</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/factures" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                    <span>Factures</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/agenda" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span>Agenda</span>
                </a>
                
                <!-- Communication Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Communication</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/contacts" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-inbox w-5 text-center"></i>
                    <span>Contacts</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/messages" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-envelope w-5 text-center"></i>
                    <span>Messages</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/avis" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-star w-5 text-center"></i>
                    <span>Avis</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/newsletter" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-newspaper w-5 text-center"></i>
                    <span>Newsletter</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/sondages" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-poll w-5 text-center"></i>
                    <span>Sondages</span>
                </a>
                
                <!-- System Section -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider mb-2">Système</p>
                </div>
                
                <a href="<?= SITE_URL ?>/admin/utilisateurs" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Utilisateurs</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/roles" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-user-shield w-5 text-center"></i>
                    <span>Rôles</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/analytiques" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span>Analytiques</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/parametres" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Paramètres</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/journaux" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-history w-5 text-center"></i>
                    <span>Journaux</span>
                </a>
                
                <a href="<?= SITE_URL ?>/admin/notifications" class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                    <i class="fas fa-bell w-5 text-center"></i>
                    <span>Notifications</span>
                </a>
                
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar -->
            <header class="h-16 bg-dark-900 border-b border-dark-800 flex items-center justify-between px-6">
                
                <!-- Left -->
                <div class="flex items-center space-x-4">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-dark-400 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-white"><?= $page_title ?? 'Tableau de bord' ?></h1>
                </div>
                
                <!-- Right -->
                <div class="flex items-center space-x-4">
                    <!-- View Site -->
                    <a href="<?= SITE_URL ?>" target="_blank" class="text-dark-400 hover:text-gold-500 transition-colors" title="Voir le site">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    
                    <!-- Notifications -->
                    <?php 
                    $notifCount = \Core\Database::query("SELECT COUNT(*) as c FROM notifications WHERE is_read = 0 AND (user_id = ? OR user_id IS NULL)", [\Core\Auth::id()])[0]['c'] ?? 0;
                    $recentNotifs = \Core\Database::query("SELECT * FROM notifications WHERE (user_id = ? OR user_id IS NULL) ORDER BY created_at DESC LIMIT 5", [\Core\Auth::id()]);
                    ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative text-dark-400 hover:text-white transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <?php if ($notifCount > 0): ?>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs flex items-center justify-center"><?= min($notifCount, 9) ?><?= $notifCount > 9 ? '+' : '' ?></span>
                            <?php endif; ?>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-dark-800 border border-dark-700 rounded-lg shadow-xl z-50">
                            <div class="p-4 border-b border-dark-700 flex justify-between items-center">
                                <h3 class="font-semibold">Notifications</h3>
                                <?php if ($notifCount > 0): ?>
                                <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-xs rounded-full"><?= $notifCount ?> nouveau<?= $notifCount > 1 ? 'x' : '' ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <?php if (!empty($recentNotifs)): ?>
                                <?php foreach ($recentNotifs as $notif): ?>
                                <a href="<?= $notif['link'] ?: SITE_URL . '/admin/notifications' ?>" class="block p-4 hover:bg-dark-700 border-b border-dark-700 <?= !$notif['is_read'] ? 'bg-dark-700/30' : '' ?>">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas <?= $notif['type'] === 'success' ? 'fa-check text-green-400' : ($notif['type'] === 'warning' ? 'fa-exclamation text-yellow-400' : ($notif['type'] === 'error' ? 'fa-times text-red-400' : 'fa-info text-blue-400')) ?> mt-1"></i>
                                        <div>
                                            <p class="text-sm <?= !$notif['is_read'] ? 'font-medium' : '' ?>"><?= Core\View::e($notif['title']) ?></p>
                                            <p class="text-xs text-dark-400 mt-1"><?= Core\View::timeAgo($notif['created_at']) ?></p>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <div class="p-4 text-center text-dark-500 text-sm">Aucune notification</div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 border-t border-dark-700 text-center">
                                <a href="<?= SITE_URL ?>/admin/notifications" class="text-sm text-gold-500 hover:text-gold-400">Voir toutes les notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 hover:bg-dark-800 rounded-lg px-3 py-2 transition-colors">
                            <div class="w-8 h-8 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 font-semibold">
                                <?= strtoupper(substr($current_user['name'] ?? 'A', 0, 1)) ?>
                            </div>
                            <span class="hidden md:block text-sm"><?= Core\View::e($current_user['name'] ?? 'Admin') ?></span>
                            <i class="fas fa-chevron-down text-xs text-dark-400"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-xl z-50">
                            <a href="<?= SITE_URL ?>/mon-compte/profil" class="flex items-center space-x-2 px-4 py-3 hover:bg-dark-700 transition-colors">
                                <i class="fas fa-user text-dark-400 w-4"></i>
                                <span class="text-sm">Mon Profil</span>
                            </a>
                            <a href="<?= SITE_URL ?>/admin/parametres" class="flex items-center space-x-2 px-4 py-3 hover:bg-dark-700 transition-colors">
                                <i class="fas fa-cog text-dark-400 w-4"></i>
                                <span class="text-sm">Paramètres</span>
                            </a>
                            <div class="border-t border-dark-700"></div>
                            <form action="<?= SITE_URL ?>/deconnexion" method="POST">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="flex items-center space-x-2 px-4 py-3 hover:bg-dark-700 transition-colors w-full text-left text-red-400">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span class="text-sm">Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php $flash = Core\Session::getAllFlash(); ?>
            <?php if (!empty($flash)): ?>
            <div class="px-6 pt-4 space-y-2" x-data="{ show: true }" x-show="show">
                <?php if (isset($flash['success'])): ?>
                <div class="flex items-center justify-between px-4 py-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span><?= Core\View::e($flash['success']) ?></span>
                    </div>
                    <button @click="show = false" class="hover:text-white"><i class="fas fa-times"></i></button>
                </div>
                <?php endif; ?>
                <?php if (isset($flash['error'])): ?>
                <div class="flex items-center justify-between px-4 py-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span><?= Core\View::e($flash['error']) ?></span>
                    </div>
                    <button @click="show = false" class="hover:text-white"><i class="fas fa-times"></i></button>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?= $content ?? '' ?>
            </main>
            
        </div>
    </div>
    
    <!-- TinyMCE Init -->
    <script>
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                skin: 'oxide-dark',
                content_css: 'dark',
                branding: false
            });
        }
    </script>
    
    <?php if (isset($extra_js)): ?>
    <?= $extra_js ?>
    <?php endif; ?>
</body>
</html>
