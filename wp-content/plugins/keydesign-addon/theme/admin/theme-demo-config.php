<?php

if ( ! function_exists( 'keydesign_demo_import_files' ) ) {
  function keydesign_demo_import_files() {
    return array(
      array(
        'import_file_name'             => 'Business',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/business/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/business/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/business.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/business/',
        'has_slider'                   => true,
        'slider_name'                  => 'business',
      ),
      array(
        'import_file_name'             => 'Corporate',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/corporate/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/corporate/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/corporate.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/corporate/',
        'has_slider'                   => true,
        'slider_name'                  => 'corporate',
      ),
      array(
        'import_file_name'             => 'Digital Agency',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/digital-agency/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/digital-agency/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/digital-agency.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/digital-agency/',
      ),
      array(
        'import_file_name'             => 'Conference',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/event-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/event-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/event-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/event-landing/',
        'has_slider'                   => true,
        'slider_name'                  => 'event-landing',
      ),
      array(
        'import_file_name'             => 'Hosting',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/hosting/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/hosting/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/hosting.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/hosting/',
        'has_slider'                   => true,
        'slider_name'                  => 'hosting',
      ),
      array(
        'import_file_name'             => 'Marketing Agency',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/marketing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/marketing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/marketing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/marketing/',
      ),
      array(
        'import_file_name'             => 'Mobile App',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/mobile-app/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/mobile-app/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/mobile-app.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/mobile-app/',
      ),
      array(
        'import_file_name'             => 'Mobile App 2',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/mobile-app-2/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/mobile-app-2/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/mobile-app-2.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/mobile-app-2/',
      ),
      array(
        'import_file_name'             => 'SaaS',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/saas-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/saas-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/saas-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/saas-landing/',
      ),
      array(
        'import_file_name'             => 'SEO Agency',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/seo-agency/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/seo-agency/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/seo-agency.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/seo-agency/',
      ),
      array(
        'import_file_name'             => 'Single Product',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/product-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/product-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/product-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/product-landing/',
        'has_slider'                   => true,
        'slider_name'                  => 'product-landing',
      ),
      array(
        'import_file_name'             => 'Software',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/software-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/software-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/software-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/software-landing/',
      ),
      array(
        'import_file_name'             => 'StartUp',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/startup/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/startup/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/startup.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/startup/',
        'has_slider'                   => true,
        'slider_name'                  => 'startup',
      ),
      array(
        'import_file_name'             => 'Web Design',
        'categories'                   => array( 'Software/Marketing' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/web-design/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/web-design/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/web-design.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/web-design/',
        'has_slider'                   => true,
        'slider_name'                  => 'web-design',
      ),
      array(
        'import_file_name'             => 'Auto Service',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/auto-service/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/auto-service/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/auto-service.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/auto-service/',
      ),
      array(
        'import_file_name'             => 'Beauty Salon',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/beauty-salon/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/beauty-salon/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/beauty-salon.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/beauty-salon/',
        'has_slider'                   => true,
        'slider_name'                  => 'beauty-salon',
      ),
      array(
        'import_file_name'             => 'Catering',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/catering/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/catering/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/catering.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/catering/',
        'has_slider'                   => true,
        'slider_name'                  => 'catering',
      ),
      array(
        'import_file_name'             => 'Cleaning',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/cleaning/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/cleaning/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/cleaning.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/cleaning/',
        'has_slider'                   => true,
        'slider_name'                  => 'cleaning',
      ),
      array(
        'import_file_name'             => 'Dentist',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/dentist/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/dentist/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/dentist.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/dentist/',
        'has_slider'                   => true,
        'slider_name'                  => 'dentist',
      ),
      array(
        'import_file_name'             => 'Elder Care',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/elder-care/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/elder-care/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/elder-care.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/elder-care/',
        'has_slider'                   => true,
        'slider_name'                  => 'elder-care',
      ),
      array(
        'import_file_name'             => 'Gardener',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/gardener/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/gardener/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/gardener.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/gardener/',
        'has_slider'                   => true,
        'slider_name'                  => 'gardener',
      ),
      array(
        'import_file_name'             => 'Hair Salon',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/hair-salon/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/hair-salon/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/hair-salon.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/hair-salon/',
        'has_slider'                   => true,
        'slider_name'                  => 'hair-salon',
      ),
      array(
        'import_file_name'             => 'Makeup Artist',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/makeup-artist/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/makeup-artist/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/makeup-artist.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/makeup-artist/',
        'has_slider'                   => true,
        'slider_name'                  => 'makeup-artist',
      ),
      array(
        'import_file_name'             => 'Moving',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/moving/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/moving/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/moving.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/moving/',
      ),
      array(
        'import_file_name'             => 'Pizza',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/pizza/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/pizza/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/pizza.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/pizza/',
        'has_slider'                   => true,
        'slider_name'                  => 'pizza',
      ),
      array(
        'import_file_name'             => 'Restaurant',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/restaurant/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/restaurant/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/restaurant.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/restaurant/',
        'has_slider'                   => true,
        'slider_name'                  => 'restaurant',
      ),
      array(
        'import_file_name'             => 'Travel Agency',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/travel-agency/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/travel-agency/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/travel-agency.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/travel-agency/',
      ),
      array(
        'import_file_name'             => 'Veterinary',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/veterinary/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/veterinary/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/veterinary.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/veterinary/',
      ),
      array(
        'import_file_name'             => 'Winery',
        'categories'                   => array( 'Retail/Services' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/winery/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/winery/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/winery.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/winery/',
        'has_slider'                   => true,
        'slider_name'                  => 'winery',
      ),
      array(
        'import_file_name'             => 'Architecture',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/architecture/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/architecture/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/architecture.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/architecture/',
        'has_slider'                   => true,
        'slider_name'                  => 'architecture',
      ),
      array(
        'import_file_name'             => 'Construction',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/construction/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/construction/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/construction.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/construction/',
        'has_slider'                   => true,
        'slider_name'                  => 'construction',
      ),
      array(
        'import_file_name'             => 'Consulting',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/consulting/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/consulting/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/consulting.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/consulting/',
        'has_slider'                   => true,
        'slider_name'                  => 'consulting',
      ),
      array(
        'import_file_name'             => 'Fitness',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/fitness/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/fitness/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/fitness.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/fitness/',
        'has_slider'                   => true,
        'slider_name'                  => 'fitness',
      ),
      array(
        'import_file_name'             => 'Furniture Shop',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/furniture-shop/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/furniture-shop/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/furniture-shop.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/furniture-shop/',
      ),
      array(
        'import_file_name'             => 'Home Decor',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/home-decor/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/home-decor/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/home-decor.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/home-decor/',
        'has_slider'                   => true,
        'slider_name'                  => 'home-decor',
      ),
      array(
        'import_file_name'             => 'Jewelry Shop',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/jewelry-shop/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/jewelry-shop/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/jewelry-shop.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/jewelry-shop/',
        'has_slider'                   => true,
        'slider_name'                  => 'jewelry-shop',
      ),
      array(
        'import_file_name'             => 'Lawyer',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/lawyer/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/lawyer/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/lawyer.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/lawyer/',
        'has_slider'                   => true,
        'slider_name'                  => 'lawyer',
      ),
      array(
        'import_file_name'             => 'Real Estate',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/real-estate/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/real-estate/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/real-estate.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/real-estate/',
        'has_slider'                   => true,
        'slider_name'                  => 'real-estate',
      ),
      array(
        'import_file_name'             => 'Renewable Energy',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/renewable-energy/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/renewable-energy/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/renewable-energy.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/renewable-energy/',
        'has_slider'                   => true,
        'slider_name'                  => 'renewable-energy',
      ),
      array(
        'import_file_name'             => 'Rental',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/rental-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/rental-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/rental-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/rental-landing/',
      ),
      array(
        'import_file_name'             => 'Transport',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/transport/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/transport/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/transport.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/transport/',
      ),
      array(
        'import_file_name'             => 'Wedding Planner',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/wedding/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/wedding/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/wedding.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/wedding/',
      ),
      array(
        'import_file_name'             => 'Workspace',
        'categories'                   => array( 'Business' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/workspace/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/workspace/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/work-space.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/workspace/',
      ),
      array(
        'import_file_name'             => 'Charity',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/charity/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/charity/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/charity.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/charity/',
      ),
      array(
        'import_file_name'             => 'eBook',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/ebook-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/ebook-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/ebook-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/ebook-landing/',
      ),
      array(
        'import_file_name'             => 'Fashion Blog',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/fashion-blog/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/fashion-blog/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/fashion-blog.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/fashion-blog/',
        'has_slider'                   => true,
        'slider_name'                  => 'fashion-blog',
      ),
      array(
        'import_file_name'             => 'Health Blog',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/health-blog/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/health-blog/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/healthblog.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/health-blog/',
        'has_slider'                   => true,
        'slider_name'                  => 'health',
      ),
      array(
        'import_file_name'             => 'Musical Artist',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/artist-landing/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/artist-landing/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/artist-landing.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/artist-landing/',
      ),
      array(
        'import_file_name'             => 'NGO',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/ngo-nonprofit/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/ngo-nonprofit/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/ngo-nonprofit.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/ngo-nonprofit/',
      ),
      array(
        'import_file_name'             => 'Travel Blog',
        'categories'                   => array( 'Personal/Non-profit' ),
        'local_import_file'            => plugin_dir_path( __FILE__ ) . 'demos/travel-blog/demo-content.xml',
        'local_import_widget_file'     => plugin_dir_path( __FILE__ ) . 'demos/general/widgets.wie',
        'local_import_redux'           => array(
          array(
            'file_path'   => plugin_dir_path( __FILE__ ) . 'demos/travel-blog/theme-options.json',
            'option_name' => 'redux_ThemeTek',
          ),
        ),
        'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'screenshots/travel-blog.jpg',
        'preview_url'                  => 'https://www.ekko-wp.com/travel-blog/',
        'has_slider'                   => true,
        'slider_name'                  => 'travel-blog',
      ),

    );
  }
}
add_filter( 'pt-ocdi/import_files', 'keydesign_demo_import_files' );

// Automatically assign "Front page", "Posts page" and menu locations after the importer is done
// Import Revolution Slider if plugin is active
if ( ! function_exists( 'keydesign_demo_after_import' ) ) {
  function keydesign_demo_after_import($selected_import) {
  	// Assign menus to their locations.
  	$main_menu = get_term_by( 'name', 'Main menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer menu', 'nav_menu' );
    $topbar_menu = get_term_by( 'name', 'Topbar menu', 'nav_menu' );

  	set_theme_mod( 'nav_menu_locations', array(
  			'header-menu' => $main_menu->term_id,
        'footer-menu' => $footer_menu->term_id,
        'topbar-menu' => $topbar_menu->term_id,
  		)
  	);

  	// Assign front page and posts page (blog page)
    $front_page_name = 'Home ' . $selected_import['import_file_name'];
    $front_page_array = get_posts([
		'title' => $front_page_name,
		'post_type' => 'any',
	]);
	$front_page = $front_page_array[0];
	$front_page_id = $front_page->ID;

	$blog_page_array = get_posts([
		'title' => 'Blog',
		'post_type' => 'any',
	]);
	$blog_page = $blog_page_array[0];
	$blog_page_id = $blog_page->ID;

  	update_option( 'show_on_front', 'page' );
  	update_option( 'page_on_front', $front_page_id );
  	update_option( 'page_for_posts', $blog_page_id );

    // Configure permalinks
    global $wp_rewrite;
  	$wp_rewrite->set_permalink_structure( '/%postname%/' );
    flush_rewrite_rules();

    // Import Slider Revolution
    if ( class_exists( 'RevSlider' ) ) {
      if ($selected_import['has_slider']) {
        $slider_array = array( plugin_dir_path( __FILE__ ) . 'demos/revslider/'.$selected_import['slider_name'].'-slider.zip' );

        $slider = new RevSlider();

        foreach($slider_array as $filepath){
          $slider->importSliderFromPost(true,true,$filepath);
        }

        echo ' Slider processed';
      }
    }
  }
}
add_action( 'pt-ocdi/after_import', 'keydesign_demo_after_import' );

// Remove default widgets on demo import
if ( ! function_exists( 'keydesign_before_widgets_import' ) ) {
  function keydesign_before_widgets_import( $selected_import ) {
    update_option( 'sidebars_widgets', array( 'wp_inactive_widgets' => array() ) );
  }
}
add_action( 'pt-ocdi/before_widgets_import', 'keydesign_before_widgets_import' );

// Disable generation of smaller images (thumbnails) during the content import
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

// Disable the branding notice
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
?>
