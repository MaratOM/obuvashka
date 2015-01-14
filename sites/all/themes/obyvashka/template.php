<?php
function obyvashka_links($links, $attributes = array('class' => 'links')) {
  global $language;
  $output = '';

  if (count($links) > 0) {
    $output = '<ul'. drupal_attributes($attributes) .'>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;

		$class .= ' item-'.$i;
      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li'. drupal_attributes(array('class' => $class)) .'><nobr>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span'. $span_attributes .'>'. $link['title'] .'</span>';
      }

      $i++;
      $output .= "</nobr></li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

function obyvashka_date_display_range($date1, $date2, $timezone = NULL) {
  return '<span class="date-display-start">'. $date1 .'</span>';
}

function obyvashka_gmap($element) {
  // Track the mapids we've used already.
  static $mapids = array();

  _gmap_doheader();

  // Convert from raw map array if needed.
  if (!isset($element['#settings'])) {
    $element = array(
      '#settings' => $element,
    );
  }

  $mapid = FALSE;
  if (isset($element['#map']) && $element['#map']) {
    // The default mapid is #map.
    $mapid = $element['#map'];
  }
  if (isset($element['#settings']['id'])) {
    // Settings overrides it.
    $mapid = $element['#settings']['id'];
  }
  if (!$mapid) {
    // Hmm, no mapid. Generate one.
    $mapid = gmap_get_auto_mapid();
  }
  // Push the mapid back into #map.
  $element['#map'] = $mapid;

  gmap_widget_setup($element, 'gmap', $mapid);

  if (!$element['#settings']) {
    $element['#settings'] = array();
  }

  // Push the mapid back into #settings.
  $element['#settings']['id'] = $mapid;

  $mapdefaults = gmap_defaults();
  $map = array_merge($mapdefaults, $element['#settings']);
  // Styles is a subarray.
  if (isset($element['#settings']['styles'])) {
    $map['styles'] = array_merge($mapdefaults['styles'], $element['#settings']['styles']);
  }
  gmap_map_cleanup($map);

  // Add a class around map bubble contents.
  // @@@ Bdragon sez: Becw, this doesn't belong here. Theming needs to get fixed instead..
  if (isset($map['markers'])) {
    foreach ($map['markers'] as $i => $marker) {
      $map['markers'][$i]['text'] = '<div class="gmap-popup">' . $marker['text'] . '</div>';
    }
  }

  switch (strtolower($map['align'])) {
    case 'left':
      $element['#attributes']['class'] .= ' gmap-left';
      break;
    case 'right':
      $element['#attributes']['class'] .= ' gmap-right';
      break;
    case 'center':
    case 'centre':
      $element['#attributes']['class'] .= ' gmap-center';
  }

  $style = array();
  $style[] = 'width: '. $map['width'];
  $style[] = 'height: '. $map['height'];

  $element['#attributes']['class'] = trim($element['#attributes']['class'] .' gmap gmap-map gmap-'. $mapid .'-gmap');

  // Some markup parsers (IE) don't handle empty inners well. Use the space to let users know javascript is required.
  // @@@ Bevan sez: Google static maps could be useful here.
  // @@@ Bdragon sez: Yeah, would be nice, but hard to guarantee functionality. Not everyone uses the static markerloader.
  $o = '<div style="'. implode('; ', $style) .';" id="'. $element['#id'] .'"'. drupal_attributes($element['#attributes']) .'>'. t('Javascript is required to view this map.') .'</div>';

  // $map can be manipulated by reference.
  foreach (module_implements('gmap') as $module) {
    call_user_func_array($module .'_gmap', array('pre_theme_map', &$map));
  }

  if (isset($mapids[$element['#map']])) {
    drupal_set_message(t('Duplicate map detected! GMap does not support multiplexing maps onto one MapID! GMap MapID: %mapid', array('%mapid' => $element['#map'])), 'error');
    // Return the div anyway. All but one map for a given id will be a graymap,
    // because obj.map gets stomped when trying to multiplex maps!
    return $o;
  }
  $mapids[$element['#map']] = TRUE;

  // Inline settings extend.
drupal_add_js(array('gmap' => array($element['#map'] => $map)), 'setting');
  return $o;
}

function obyvashka_preprocess_page(&$variables) {
	if(arg(0) == 'node') {
		$node_loaded = node_load(arg(1));
		$show_in_menu_e_shop = $node_loaded->type == 'product' ? TRUE : FALSE;
	}

  if(strstr($_SERVER['REQUEST_URI'],'product') || $show_in_menu_e_shop) { 
    $menu = menu_navigation_links("menu-menu-e-shop");
    $menu_e_shop = theme('links', $menu, array('class' => 'menu-e-shop'));
  }
  $variables['menu_e_shop'] = $menu_e_shop;
	
	// Info on Front Page	
	if(drupal_is_front_page()) {
		//$node_loaded = node_load(110); //node #110 
		//$variables['front_page_content'] = $node_loaded->body;
	}
	
	// Contact page info
	//$node_loaded = node_load(71); //node #71
	//$variables['contact_info'] = $node_loaded->body;	

}




// The default function from uc_cart.module.
/*
function obyvashka_uc_cart_block_content() {
  global $user;

  if (
variable_get('uc_cart_show_help_text', FALSE)) {
    $output = '<span class="cart-help-text">'
            . variable_get('uc_cart_help_text', t('Click title to display cart contents.'))
             .'</span>';
  }

 
$output .= '<div id="block-cart-contents">';

 
$items = uc_cart_get_contents();

 
$item_count = 0;
  if (!empty($items)) {
    $output .= '<table class="cart-block-table">'
              .'<tbody class="cart-block-tbody">';
    foreach ($items as $item) {
      $output .= '<tr class="cart-block-item"><td class="cart-block-item-qty">'. $item->qty .'x</td>'
               . '<td class="cart-block-item-title">'. l($item->title, 'node/'. $item->nid) .'</td>'
               . '<td class="cart-block-item-price">'. uc_currency_format($item->price) .'</td></tr>';
      if (is_array($item->data['attributes']) && !empty($item->data['attributes'])) {
        $display_item = module_invoke($item->module, 'cart_display', $item);
        $output .= '<tr><td colspan="3">'. $display_item['options']['#value'] .'</td></tr>';
      }
      $total += ($item->price) * $item->qty;
      $item_count += $item->qty;
    }

   
$output .= '</tbody></table>';
  }
  else {
    $output .= '<p>'. t('There are no products in your shopping cart.') .'</p>';
  }

 
$output .= '</div>';

 
$item_text = format_plural($item_count, '1 Item', '@count Items');
  $view = '('. l(t('View cart'), 'cart', array('rel' => 'nofollow')) .')';
  if (variable_get('uc_checkout_enabled', TRUE)) {
    $checkout = ' ('. l(t('Checkout'), 'cart/checkout', array('rel' => 'nofollow')) .')';
  }
  $output .= '<a href="/card"><table class="cart-block-summary-table"><tbody class="cart-block-summary-tbody">'
            .'<tr class="cart-block-summary-tr"><td class="cart-block-summary-items">'
            . $item_text .'</td><td class="cart-block-summary-total">'
            .'<strong>'. t('Total:') .'</strong> '. uc_currency_format($total) .'</td></tr>';
  if ($item_count > 0) {
    $output .= '<tr><td colspan="2" class="cart-block-summary-checkout">'. $view . $checkout .'</td></tr>';
  }
  $output .= '</tbody></table></a>';

  return
$output;
}
*/
