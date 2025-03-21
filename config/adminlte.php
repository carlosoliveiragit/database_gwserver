<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Dashboard | Login',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Servidor de Arquivos</b> GW',
    'logo_img' => 'vendor/adminlte/dist/img/icogw.svg',
    'logo_img_class' => 'brand-image img-circle elevation-4',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Dashboard GW',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark',
    // 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 500,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'ligth',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    //'register_url' => 'register',
    //'password_reset_url' => 'password/reset',
    //'password_email_url' => 'password/email',
    //'profile_url' => 'profile',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'type' => 'darkmode-widget',
            'topnav_right' => true, // Or "topnav => true" to place on the left.
            'icon_enabled' => 'fas fa-moon',
            'icon_disabled' => 'fas fa-sun',
            'color_enabled' => 'white',
            'color_disabled' => 'yellow'
        ],
        ['header' => ''],
        [
            'text' => 'PAGINA INICIAL',
            'url' => 'home',
            'icon' => 'fas fa-home',
        ],

        ['header' => ''],
        ['header' => 'BASE DE DADOS'],
        
           
                [
                    'text' => 'Pesquisar',
                    'url' => 'search_files',
                    'icon' => 'fa-solid fa-magnifying-glass',
                    //'can'  => 'is_admin',
                ],
                [
                    'text' => 'Lista Geral',
                    'url' => 'files',
                    'icon' => 'fa-solid fa-list-check',
                    //'can'  => 'is_admin',
                ],
                [
                    'text' => 'Clientes',
                    'url' => '#',
                    'icon' => 'fas fa-solid fa-water',
                    //'can'  => 'is_admin',
                ],
                ['header' => ''],

        ['header' => 'UPLOAD'],
        [
            'text' => 'OPERAÇÃO',
            'icon' => 'fa fa-cloud-arrow-up',
            'submenu' => [

                [
                    'text' => 'Dados de Produção',
                    'url' => 'upload_xlsx/production_data',
                    'icon' => 'fa-solid fa-table',
                ],
                [
                    'text' => 'Setpoints',
                    'url' => 'upload_setpoints',
                    'icon' => 'fa-solid fa-image',
                ],
                [
                    'text' => 'POP',
                    'url' => 'upload_pop/operacao',
                    'icon' => 'fa-solid fa-file-arrow-up ',
                ],
                ['header' => ''],

            ],
        ],
        [
            'text' => 'CCO',
            'icon' => 'fas fa-cloud-arrow-up',
            'submenu' => [

                [
                    'text' => 'Telemetria',
                    'url' => 'upload_telemetry',
                    'icon' => 'fa-solid fa-scroll',
                ],
                [
                    'text' => 'POP',
                    'url' => 'upload_pop/cco',
                    'icon' => 'fa-solid fa-file-arrow-up',
                ],
                ['header' => ''],

            ],
        ],

        [
            'text' => 'MANUTENÇÃO',
            'icon' => 'fas fa-cloud-arrow-up',
            'submenu' => [



                
                [
                    'text' => 'CLP',
                    'icon' => 'fa-solid fa-gears',
                    //'label' => "DEV",
                    //'label_color' => 'danger',
                    'submenu' => [

                        [
                            'text' => 'ABB',
                            'url' => 'upload_clp/abb',
                        ],
                        [
                            'text' => 'Altus',
                            'url' => 'upload_clp/altus',
                        ],
                        [
                            'text' => 'WEG',
                            'url' => 'upload_clp/weg',

                        ],
                        [
                            'text' => 'Allen-Bradley',
                            'url' => 'upload_clp/allenbradley',
                            'label' => "DEV",
                            'label_color' => 'warning',

                        ],
                        [
                            'text' => 'Metaltex',
                            'url' => 'upload_clp/metaltex',
                            'label' => "DEV",
                            'label_color' => 'warning',

                        ],
                    ],
                    ['header' => ''],
                ],
                [
                    'text' => 'IHM',
                    'url' => 'upload_ihm',
                    'icon' => 'fa-solid fa-desktop',

                ],
                [
                    'text' => 'POP',
                    'url' => 'upload_pop/manutencao',
                    'icon' => 'fa-solid fa-file-arrow-up',
                ],
                ['header' => ''],
            ],
        ],

        [
            'text' => 'APOIO',
            'icon' => 'fas fa-cloud-arrow-up',
            'submenu' => [
                [
                    'text' => 'Planilhas de apoio',
                    'url' => 'upload_xlsx/support_files',
                    'icon' => 'fa-solid fa-table',
                ],
                ['header' => ''],

            ],
        ],


        ['header' => ''],
        [
            'text' => 'Administração',
            'url' => '#',
            'icon' => 'fas fa-solid fa-gear',
            'icon_color' => 'red',
            'label' => "admin",
            'label_color' => 'danger',
            'can' => 'is_admin',
            'submenu' => [
                [
                    'text' => 'Usuários',
                    'url' => 'users',
                    'icon' => 'fas fa-solid fa-users',
                    'icon_color' => 'danger',
                ],
                [
                    'text' => 'Clientes',
                    'url' => 'clients',
                    'icon' => 'fas fa-solid fa-water',
                    'icon_color' => 'danger',
                ],
                [
                    'text' => 'Sistemas',
                    'url' => 'systems',
                    'icon' => 'fas fa-solid fa-sitemap',
                    'icon_color' => 'danger',
                ],
                [
                    'text' => 'Tipos',
                    'url' => 'types',
                    'icon' => 'fa-solid fa-text-height',
                    'icon_color' => 'danger',
                ],
                [
                    'text' => 'Setores',
                    'url' => 'sectors',
                    'icon' => 'fa-solid fa-vector-square',
                    'icon_color' => 'danger',
                ],

            ],
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */
    'DatatablesPlugins' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/pdfmake/pdfmake.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/pdfmake/vfs_fonts.js',
            ],
            [
                'type' => 'css',
                'asset' => false,
                'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
            ],

        ],
    ],

    'plugins' => [
        'BsCustomFileInput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bs-custom-file-input/bs-custom-file-input.min.js',
                ],
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js',
                ],


            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    */

    'livewire' => false,
];