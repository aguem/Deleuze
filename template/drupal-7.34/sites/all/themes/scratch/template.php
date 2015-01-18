<?php
$options = array('scope' => 'footer'  );
drupal_add_js(drupal_get_path('theme', 'scratch') . '/js/foundation.min.js', $options);
$foundation='jQuery(document).foundation();';
$options['type']='inline';
drupal_add_js($foundation, $options);

  $small-range: (0em, 40em);
   $medium-range: (40.063em, 48em);
   $xmedium-range: (48.063em, 64em);
    $large-range: (64.063em, 90em);
$xlarge-range: (90.063em, 120em);
$xxlarge-range: (120.063em, 99999999em);

$xmedium-up: "#{$screen} and (min-width:#{lower-bound($xmedium-range)})";
$xmedium-only: "#{$screen} and (min-width:#{lower-bound($xmedium-range)}) and (max-width:#{upper-bound($xmedium-range)})"
$topbar-media-query: $xmedium-up;


function template_preprocess_page(&$variables) {
  // Move some variables to the top level for themer convenience and template cleanliness.
  $variables['show_messages'] = $variables['page']['#show_messages'];

  foreach (system_region_list($GLOBALS['theme']) as $region_key => $region_name) {
    if (!isset($variables['page'][$region_key])) {
      $variables['page'][$region_key] = array();
    }
  }

  // Set up layout variable.
  $variables['layout'] = 'none';
  if (!empty($variables['page']['sidebar_first'])) {
    $variables['layout'] = 'first';
  }
  if (!empty($variables['page']['sidebar_second'])) {
    $variables['layout'] = ($variables['layout'] == 'first') ? 'both' : 'second';
  }

  $variables['base_path'] = base_path();
  $variables['front_page'] = url();
  $variables['feed_icons'] = drupal_get_feeds();
  $variables['language'] = $GLOBALS['language'];
  $variables['language']->dir = $GLOBALS['language']->direction ? 'rtl' : 'ltr';
  $variables['logo'] = theme_get_setting('logo');
  $variables['main_menu'] = theme_get_setting('toggle_main_menu') ? menu_main_menu() : array();
  $variables['secondary_menu'] = theme_get_setting('toggle_secondary_menu') ? menu_secondary_menu() : array();
  $variables['action_links'] = menu_local_actions();
  $variables['site_name'] = (theme_get_setting('toggle_name') ? filter_xss_admin(variable_get('site_name', 'Drupal')) : '');
  $variables['site_slogan'] = (theme_get_setting('toggle_slogan') ? filter_xss_admin(variable_get('site_slogan', '')) : '');
  $variables['tabs'] = menu_local_tabs();

  if ($node = menu_get_object()) {
    $variables['node'] = $node;
  }

  // Populate the page template suggestions.
  if ($suggestions = theme_get_suggestions(arg(), 'page')) {
    $variables['theme_hook_suggestions'] = $suggestions;
  }
  
  $viewport = array(
   '#tag' => 'meta',
   '#attributes' => array(
     'name' => 'viewport',
     'content' => 'width=device-width, initial-scale=1, maximum-scale=1',
   ),
  );
drupal_add_html_head($viewport, 'viewport');
}

function menu_tree_output($tree) {
  $build = array();
  $items = array();

  // Pull out just the menu links we are going to render so that we
  // get an accurate count for the first/last classes.
  foreach ($tree as $data) {
    if ($data['link']['access'] && !$data['link']['hidden']) {
      $items[] = $data;
    }
  }

  $router_item = menu_get_item();
  $num_items = count($items);
  foreach ($items as $i => $data) {
    $class = array();
    if ($i == 0) {
      $class[] = 'first';
    }
    if ($i == $num_items - 1) {
      $class[] = 'last';
    }
    // Set a class for the <li>-tag. Since $data['below'] may contain local
    // tasks, only set 'expanded' class if the link also has children within
    // the current menu.
    if ($data['link']['has_children'] && $data['below']) {
      $class[] = 'expanded';
    }
    elseif ($data['link']['has_children']) {
      $class[] = 'collapsed';
    }
    else {
      $class[] = 'leaf';
    }
    // Set a class if the link is in the active trail.
    if ($data['link']['in_active_trail']) {
      $class[] = 'active-trail';
      $data['link']['localized_options']['attributes']['class'][] = 'active-trail';
    }
    // Normally, l() compares the href of every link with $_GET['q'] and sets
    // the active class accordingly. But local tasks do not appear in menu
    // trees, so if the current path is a local task, and this link is its
    // tab root, then we have to set the class manually.
    if ($data['link']['href'] == $router_item['tab_root_href'] && $data['link']['href'] != $_GET['q']) {
      $data['link']['localized_options']['attributes']['class'][] = 'active';
    }

    // Allow menu-specific theme overrides.
    $element['#theme'] = 'menu_link__' . strtr($data['link']['menu_name'], '-', '_');
    $element['#attributes']['class'] = $class;
    $element['#title'] = $data['link']['title'];
    $element['#href'] = $data['link']['href'];
    $element['#localized_options'] = !empty($data['link']['localized_options']) ? $data['link']['localized_options'] : array();
    $element['#below'] = $data['below'] ? menu_tree_output($data['below']) : $data['below'];
    $element['#original_link'] = $data['link'];
    // Index using the link's unique mlid.
    $build[$data['link']['mlid']] = $element;
  }
  if ($build) {
    // Make sure drupal_render() does not re-order the links.
    $build['#sorted'] = TRUE;
    // Add the theme wrapper for outer markup.
    // Allow menu-specific theme overrides.
    $build['#theme_wrappers'][] = 'menu_tree__' . strtr($data['link']['menu_name'], '-', '_');
  }

  return $build;
}

function menu_tree_all_data($menu_name, $link = NULL, $max_depth = NULL) {
  $tree = &drupal_static(__FUNCTION__, array());

  // Use $mlid as a flag for whether the data being loaded is for the whole tree.
  $mlid = isset($link['mlid']) ? $link['mlid'] : 0;
  // Generate a cache ID (cid) specific for this $menu_name, $link, $language, and depth.
  $cid = 'links:' . $menu_name . ':all:' . $mlid . ':' . $GLOBALS['language']->language . ':' . (int) $max_depth;

  if (!isset($tree[$cid])) {
    // If the static variable doesn't have the data, check {cache_menu}.
    $cache = cache_get($cid, 'cache_menu');
    if ($cache && isset($cache->data)) {
      // If the cache entry exists, it contains the parameters for
      // menu_build_tree().
      $tree_parameters = $cache->data;
    }
    // If the tree data was not in the cache, build $tree_parameters.
    if (!isset($tree_parameters)) {
      $tree_parameters = array(
        'min_depth' => 1,
        'max_depth' => $max_depth,
      );
      if ($mlid) {
        // The tree is for a single item, so we need to match the values in its
        // p columns and 0 (the top level) with the plid values of other links.
        $parents = array(0);
        for ($i = 1; $i < MENU_MAX_DEPTH; $i++) {
          if (!empty($link["p$i"])) {
            $parents[] = $link["p$i"];
          }
        }
        $tree_parameters['expanded'] = $parents;
        $tree_parameters['active_trail'] = $parents;
        $tree_parameters['active_trail'][] = $mlid;
      }

      // Cache the tree building parameters using the page-specific cid.
      cache_set($cid, $tree_parameters, 'cache_menu');
    }

    // Build the tree using the parameters; the resulting tree will be cached
    // by _menu_build_tree()).
    $tree[$cid] = menu_build_tree($menu_name, $tree_parameters);
  }

  return $tree[$cid];
}

function scratch_js_alter(&$javascript) {
 
  //On veut utiliser la version JQuery 1.11.1
  $jquery_path = drupal_get_path('theme','scratch') . '/js/jquery1111.js';
 
  //On récupére les information de la version JQuery native
  $javascript[$jquery_path] = $javascript['misc/jquery.js'];

  //on fait les remplacements adaptés
  $javascript[$jquery_path]['version'] = '1.11.1';
  $javascript[$jquery_path]['data'] = $jquery_path;
 
  //et on supprime la version native
  unset($javascript['misc/jquery.js']); 
}

function scratch_preprocess_page(&$variables) {
   
   $variables['topbar_main_menu'] = '';

   //On parcourt le menu en entier
   $links = menu_tree_output(menu_tree_all_data(variable_get('menu_main_links_source', 'main-menu')));
  
   //On construit le markup de chaque élément de lien
   $output = '';
   foreach (element_children($links) as $key) {   
	$output .= _scratch_render_link($links[$key]);	
   }
 
   $variables['topbar_main_menu'] =  '

    ' . $output . '

';
}

function _scratch_render_link($link) {
  $output = '';

  if ($link['#href'] === '') {
    $link['#attributes']['class'][] = 'divider';
    $rendered_link = '';
  }
 else  $rendered_link = l($link['#title'], $link['#href'], $link['#localized_options']);
 $output .= '' . $rendered_link. '';
  return $output;
}
?>