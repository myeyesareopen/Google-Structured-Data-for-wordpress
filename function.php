function sk_site_meta() {
    $site_meta = array (
        'name' => 'Bitprismia',
        'logo' => 'https://bitprismia.com/wp-content/uploads/2023/04/logo-opacity-2.png',
        'picture' => 'https://bitprismia.com/wp-content/uploads/2023/04/twitter-picture.jpg', //when there is no feature image, show this picture in the tructured data
        'title' => 'Bitprismia: Your Daily Dose of Crypto News and Insights',
        'description' => 'Bitprismia is an all-in-one cryptocurrency news aggregator, bringing you the latest headlines from around the web. Stay up-to-date on the latest developments in the world of crypto with our constantly updated feed of news articles, opinion pieces, and analysis from top sources. From Bitcoin to Ethereum, from blockchain technology to decentralized finance, our platform covers it all. Join the thousands of users who rely on Bitprismia to stay informed and make smarter decisions in the fast-paced world of crypto.',
        'keywords' => 'Cryptocurrency news, Blockchain technology, Digital assets',
        'url' => 'https://Bitprismia.com' 
    );
    return $site_meta;
}

function sk_og_meta() { 
    global $post;
    $site_meta = sk_site_meta();
    $logo = $site_meta['logo'];
    $title = $site_meta['title'];
    $description = $site_meta['description'];
    $keywords = $site_meta['keywords'];
    $site_url = $site_meta['url'];
    $name = $site_meta['name'];

    //homepage setting
    if ( is_front_page() || is_home() ) {
        //Replace the homepage title with the title you have set.
        add_filter('pre_get_document_title', 'sk_change_page_title');
    ?>
        <meta property="og:title" content="<?php echo $title; ?>" />
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta property="og:image" content="<?php echo sk_site_meta()['picture']; ?>" />
        <meta property="og:url" content="<?php echo $site_url; ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="<?php echo $name; ?>" />
        <meta property="twitter:title" content="<?php echo $title; ?>" />
        <meta property="twitter:url" content="<?php echo $site_url; ?>" />
        <meta property="twitter:site" content="@<?php echo $name; ?>" />
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:image" content="<?php echo sk_site_meta()['picture']; ?>" />
        <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Organization",
              "url": "<?php echo $site_url; ?>",
              "logo": "<?php echo $logo; ?>"
            }
        </script>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebSite",
                "url": "<?php echo $site_url; ?>",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "<?php echo $site_url; ?>?s={search_term_string}"
                    },
                "query-input": "required name=search_term_string"
              }
            }
        </script>
    <?php } elseif ( is_page() || is_single() ) {
  
        //post setting
        if ($post->post_excerpt) {
            $description = $post->post_excerpt;
        }
        $id = get_the_ID();
        $title = get_the_title();
        if ( get_post_thumbnail_id( $id ) ) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' )[0];
        } else {
            $image = sk_site_meta()['picture'];
        }
        $description = mb_strimwidth(strip_tags(apply_filters('the_content', get_the_content())), 0, 300, "");
        $keywords = '';
        $keys = get_the_tags();
        if ($keys) {
            foreach ($keys as $key) {
                $keywords .= $key->name.', ';
            }
        }
        ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <meta name="keywords" content="<?php echo mb_strimwidth($keywords, 0, -2, ""); ?>" />
        <meta property="og:image" content="<?php echo $image; ?>" />
        <meta property="og:url" content="<?php echo get_permalink(); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="<?php echo $name; ?>" />
        <meta property="twitter:title" content="<?php echo $title; ?>" />
        <meta property="twitter:url" content="<?php echo get_permalink(); ?>" />
        <meta property="twitter:site" content="@<?php echo $name; ?>" />
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:image" content="<?php echo $image; ?>" />
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "<?php echo $site_url; ?>"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "<?php echo get_the_category()[0]->cat_name; ?>",
                        "item": "<?php echo get_category_link(get_the_category()[0]->cat_ID); ?>"
                    },
                    {
                        "@type": "ListItem",
                        "position": 3,
                        "name": "<?php echo $title; ?>"
                    }
                ]
            }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Article",
          "headline": "<?php echo $name; ?>",
          "image": [
            "<?php echo $image; ?>"
           ],
          "datePublished": "<?php echo the_time('Y-m-d\TH:i:s+08:00'); ?>",
          "dateModified": "<?php echo the_modified_time('Y-m-d\TH:i:s+08:00'); ?>",
          "author": [{
              "@type": "Organization",
              "name": "<?php echo $name; ?>", 
              "url": "<?php echo $site_url; ?>" 
            }]
        }
        </script>
        <?php
    }
}
add_action( 'neve_head_start_after', 'sk_og_meta' );

function sk_change_page_title() {
    $title = sk_site_meta()['title'];
    return $title;
}
