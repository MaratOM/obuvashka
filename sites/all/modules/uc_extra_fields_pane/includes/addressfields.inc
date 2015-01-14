<?php
/**
 * @file
 * These functions adds extra address fields to forms
 */

// -------------------------------------------------------------------
// FORM ALTERS (extra address fields)
// -------------------------------------------------------------------

// --------------------------
// uc_store_address_fields
// --------------------------

/**
 * _uc_extra_fields_pane_address_fields_uc_store_address_fields_alter()
 * Adds extra address fields to form
 * which can be defined at /admin/store/settings/addressfields/add
 * @param array $form
 * @param array $form_state
 * @access private
 * @see _uc_extra_fields_pane_address_fields_uc_store_address_fields_submit()
 */
function _uc_extra_fields_pane_address_fields_uc_store_address_fields_alter(&$form, $form_state) {
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');

  // Similar to uc_store_address_fields_form() from uc_store.module
  foreach ($fields as $field => $data) {
    if (1) {
      $form['fields'][$field]['#summary callback'] = 'summarize_form';
      $form['fields'][$field]['enabled'] = array(
        '#type' => 'checkbox',
        '#summary callback' => 'summarize_checkbox',
        '#summary arguments' => array(
          t('@field is enabled.', array('@field' => $data->label)),
          t('@field is disabled.', array('@field' => $data->label)),
        ),
        '#default_value' => ($data->enabled) ? TRUE : FALSE,
      );
      $form['fields'][$field]['required'] = array(
        '#type' => 'checkbox',
        '#default_value' => ($data->required) ? TRUE : FALSE,
      );
    }
    $form['fields'][$field]['default'] = array(
      '#value' => $data->db_name,
    );
    $form['fields'][$field]['title'] = array(
      '#value' => $data->output('label'),
    );
    // Add id of field
    $form['fields'][$field]['field_id'] = array(
      '#type' => 'value',
      '#value' => $data->field_id,
    );
  }

  // Add link to add address field
  $form['add_field'] = array(
    '#type' => 'markup',
    '#value' => l(t('Add an address field'), 'admin/store/settings/addressfields/add') . '<br /><br />',
    '#weight' => 2,
  );
  $form['save']['#weight'] = 3;
  $form['reset']['#weight'] = 4;

  // Add submit function so 'enabled' and 'required' can be saved
  $form['#submit'][] = 'uc_extra_fields_pane_address_fields_uc_store_address_fields_submit';
}

/**
 * uc_extra_fields_pane_address_fields_uc_store_address_fields_submit()
 * Saves 'enabled' and 'required' for address fields
 * @param array $form
 * @param array $form_state
 * @see _uc_extra_fields_pane_address_fields_uc_store_address_fields_alter()
 */
function uc_extra_fields_pane_address_fields_uc_store_address_fields_submit($form, $form_state) {
  $fields_delivery = uc_extra_fields_pane_load_fields_from_db('extra_delivery');
  $fields_billing = uc_extra_fields_pane_load_fields_from_db('extra_billing');
  $address_fields = array_merge($fields_delivery, $fields_billing);

  $fields = $form_state['values']['fields'];

  foreach ($address_fields as $fieldname => $field) {
    db_query("UPDATE {uc_extra_fields} SET enabled=%d, required=%d WHERE field_id=%d", $fields[$fieldname]['enabled'], $fields[$fieldname]['required'], $fields[$fieldname]['field_id']);
  }
}

// --------------------------
// uc_cart_checkout_form
// --------------------------

/**
 * Fixes drop down 'saved addresses' and prefills extra address fields when UC Addresses is enabled.
 * @param array $form
 * @param array $form_state
 * @access private
 * @see uc_extra_fields_pane_form_uc_cart_checkout_form_alter()
 */
function uc_extra_fields_pane_address_fields_uc_cart_checkout_form_alter(&$form, $form_state) {
  global $user;

  // Check if there are extra address fields
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');
  if (count($fields) < 1) {
    // No address fields. No need to alter form
    return;
  }

  // Include file with code to get addresses
  module_load_include('inc', 'uc_extra_fields_pane', 'includes/address_select');

  // --------------------------
  // Alter address selection of delivery and billing pane
  // --------------------------
  $types = array('delivery', 'billing');
  foreach ($types as $type) {
    if (isset($form['panes'][$type][$type . '_address_select'])) {
      $default_address = NULL;
      $addresses = uc_extra_fields_pane_get_addresses($user->uid, $type);
      $options = array('0' => t('Select one...'));

      foreach ($addresses as $key => $address) {
        if (isset($address->address_name) && $address->address_name) {
          // Use address name as label for the option if available
          $option = $address->address_name;
        }
        elseif (module_exists('uc_addresses')) {
          // Format option label following uc_addresses logics
          $option = preg_replace('/<.*?>/', ', ',
            uc_address_format(
              $address->first_name,
              $address->last_name,
              $address->company,
              $address->street1,
              $address->street2,
              $address->city,
              $address->zone,
              $address->postal_code,
              $address->country
            )
          );
          // MegaChriz: the next lines are useful when using the (unfinished) address display feature,
          // see also http://drupal.org/node/904050
          if (module_exists('ucxfdev_addressdisplay') && function_exists('ucxfdev_addressdisplay_format_address')) {
            $option = ucxfdev_addressdisplay_format_address($address, '', $option);
          }
        }
        else {
          // Format option label following uc_store logics
          $option = $address->street1;
          // Check if the address is a duplicate (i.e. same address, but sent to different person)
          if ((isset($addresses[$key - 1]) && $option == $addresses[$key - 1]->street1) ||
              (isset($addresses[$key + 1]) && $option == $addresses[$key + 1]->street1)) {
            $option .= ' - ' . $address->first_name . ' ' . $address->last_name;
          }
        }
        $addresskey = drupal_to_js($address);
        $options[$addresskey] = check_plain($option);
        if ($address->is_default) {
          // If this address is the default, hold the key so the default value
          // for the address selection field can be set later.
          $default_address = $addresskey;
        }
      }

      // Apply options
      $form['panes'][$type][$type . '_address_select']['#options'] = $options;
      $form['panes'][$type][$type . '_address_select']['#attributes']['onchange'] .= "ucxf_apply_address('" . $type . "', this.value);";
      // Apply default value for select field if there is a default address
      // and if the default address may be filled in automatically.
      if ($default_address && variable_get('uc_addresses_default_' . $type . '_address', TRUE)) {
        $form['panes'][$type][$type . '_address_select']['#default_value'] = $default_address;
      }

      // Additions to copy address behaviour (because else values of checkboxes won't be copied)
      if (isset($form['panes'][$type]['copy_address'])) {
        $source_pane = ($type == 'delivery')? 'billing':'delivery';
        $target_pane = $type;
        $form['panes'][$type]['copy_address']['#attributes']['onclick'] .= "ucxf_copy_address(this.checked, '$source_pane' , '$target_pane');";
      }
    }
  }

  // Add JS function so that data from extra address fields can be applied.
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');
  $script = "
    function ucxf_apply_address(type, address_str) {
      if (address_str == '0') {
        return;
      }
      eval('var address = ' + address_str + ';');
      var temp = type + '-' + type;
  ";
  foreach ($fields as $fieldname => $field) {
    if ($field->value_type == ucxf_field::UCXF_WIDGET_TYPE_CHECKBOX) {
      $set_function = ".attr('checked', address." . $fieldname . " == 1 ? true : false)";
    }
    else {
      $set_function = ".val(address." . $fieldname . ")";
    }
    $script .= "$('#edit-panes-' + temp + '-" . form_clean_id($fieldname) . "')" . $set_function . ".trigger('change');\n";
  }
  $script .= '}';
  drupal_add_js($script, 'inline');

  // Add JS function for copying over checkbox fields
  drupal_add_js(drupal_get_path('module', 'uc_extra_fields_pane') . '/uc_extra_fields_pane.js');

  // --------------------------
  // Fill in defaults for extra address fields when UC Addresses is enabled
  // and when at least one of the following settings are true:
  // - 'uc_addresses_default_delivery_address'
  // - 'uc_addresses_default_billing_address'
  // --------------------------
  if (
    module_exists('uc_addresses') && (
      variable_get('uc_addresses_default_delivery_address', TRUE) ||
      variable_get('uc_addresses_default_billing_address', TRUE)
    )
  ) {
    // Load default UC Addresses address if available
    $default_address = NULL;
    $addresses = _uc_addresses_db_get_address($user->uid);
    if (is_array($addresses) && count($addresses) > 0) {
      foreach ($addresses as $address) {
        if ($address->is_default) {
          $default_address = $address;
        }
      }
      if ($default_address) {
        // Load values of extra address fields for this address
        $values = uc_extra_fields_pane_value_list_load($default_address->aid, UCXF_VALUE_ADDRESS);
        foreach ($values as $fieldname => $value_array) {
          // Apply default value for both delivery and billing address,
          // but only if the default value is not already set.
          if (!isset($form['panes']['delivery']['delivery_' . $fieldname]['#default_value']) && isset($form['panes']['delivery']['delivery_' . $fieldname]) && variable_get('uc_addresses_default_delivery_address', TRUE)) {
            $form['panes']['delivery']['delivery_' . $fieldname]['#default_value'] = $value_array['value'];
          }
          if (!isset($form['panes']['billing']['billing_' . $fieldname]['#default_value']) && isset($form['panes']['billing']['billing_' . $fieldname]) && variable_get('uc_addresses_default_billing_address', TRUE)) {
            $form['panes']['billing']['billing_' . $fieldname]['#default_value'] = $value_array['value'];
          }
        }
      }
    }
  }
}

/**
 * uc_extra_fields_pane_uc_checkout_pane_address()
 * @param string $type
 *  type is 'delivery' or 'billing'
 * @param string $op
 * @param mixed $arg1
 * @param mixed $arg2
 * @return mixed
 */
function uc_extra_fields_pane_uc_checkout_pane_address($type, $op, $arg1, $arg2) {
  // Throw error if $type is not what we expected
  if ($type != 'delivery' && $type != 'billing') {
    trigger_error(t("Provided type in uc_extra_fields_pane_uc_checkout_pane_address() should be either 'delivery' or 'billing'."), E_USER_WARNING);
    return;
  }

  $pane_type = 'extra_' . $type;

  // Include uc_cart_checkout_pane.inc
  module_load_include('inc', 'uc_cart', 'uc_cart_checkout_pane');

  switch ($op) {
    case 'view':
      if ($type == 'delivery') {
        $pane = uc_checkout_pane_delivery($op, $arg1, $arg2);
      }
      else {
        $pane = uc_checkout_pane_billing($op, $arg1, $arg2);
      }

      // Load extra fields for this pane
      $extra_address_fields = array();
      $extra_fields_db = uc_extra_fields_pane_load_fields_from_db($pane_type);
      foreach ($extra_fields_db as $fieldname => $field) {
        // Only view address fields that are enabled
        if ($field->enabled == 1) {
          $order_field_name = $fieldname . '_' . $type;
          $generated_field = $field->generate();

          switch ($field->value_type) {
            case ucxf_field::UCXF_WIDGET_TYPE_PHP:
            case ucxf_field::UCXF_WIDGET_TYPE_CONSTANT:
              break;
            default:
              // Adding default value for every field except for php and constant
              if (isset($arg1->extra_fields[$order_field_name])) {
                $generated_field['#default_value'] = $arg1->extra_fields[$order_field_name];
              }
              break;
          }

          if ($generated_field['#type'] != 'hidden') {
            // Prefix fieldname with 'delivery_' or 'billing_'
            $extra_address_fields[$type . '_' . $fieldname] = $generated_field;
          }
          else {
            // If the field is a hidden field, the field will be added to the array 'hidden_fields',
            // because else it will be displayed as if it was a normal field.
            $extra_address_fields['hidden_fields'][$type . '_' . $fieldname] = $generated_field;
            // Display value if the user asks
            // This currently applies to value_type of php and constant ONLY
            if ($field->may_display('checkout')) {
              $extra_address_fields[$type . '_' . $fieldname . '_i'] = array(
                '#type' => 'item',
                '#title' => $field->output('label'),
                '#value' => $generated_field['#value'],
              );
            }
          }
        }
      }

      // Merge extra fields array with original fields array
      $pane['contents'] = array_merge($pane['contents'], $extra_address_fields);
      return $pane;

    case 'process':
      if ($type == 'delivery') {
        $bResult = uc_checkout_pane_delivery($op, $arg1, $arg2);
      }
      else {
        $bResult = uc_checkout_pane_billing($op, $arg1, $arg2);
      }

      // If there were hidden fields, add them to the $arg2 array
      if (isset($arg2['hidden_fields'])) {
        foreach ($arg2['hidden_fields'] as $fieldname => $value) {
          $arg2[$fieldname] = $value;
        }
      }

      // MegaChriz: this may change later
      $arg2['extra_fields'] = array();
      foreach ($arg2 as $fieldname => $value) {
        if (strpos($fieldname, $type .'_ucxf') === 0) {
          // This is an extra field, substract type from fieldname
          $fixedfieldname = substr($fieldname, strlen($type . '_')) . '_' . $type;
          $arg2['extra_fields'][$fixedfieldname] = $value;
        }
      }
      // Initialize and fill array
      if ($arg1->extra_fields == NULL) {
        $arg1->extra_fields = array();
      }
      $arg1->extra_fields = array_merge($arg1->extra_fields, $arg2['extra_fields']);
      return $bResult;
      break;

    case 'review':
      if ($type == 'delivery') {
        $review = uc_checkout_pane_delivery($op, $arg1, $arg2);
      }
      else {
        $review = uc_checkout_pane_billing($op, $arg1, $arg2);
      }

      // Extra address fields
      $review2 = array();
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      if (count($fields)) {
        foreach ($fields as $field) {
          // Only display if the field is enabled and if it may be displayed.
          if ($field->enabled == 1 && $field->may_display('review')) {
            // get field name
            $order_field_name = $field->db_name . '_' . $type;
            // initialize field contents
            $field_contents = $field->output_value($arg1->extra_fields[$order_field_name]);
            $review2[] = array(
              'title' => $field->output('label'),
              'data' => $field_contents,
            );
          }
        }
      }

      $review = array_merge($review, $review2);
      return $review;
      break;
  }
}

// --------------------------
// UC Addresses integration: uc_addresses_get_address_form
// --------------------------

/**
 * uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_alter()
 * @param array $form
 * @param array $form_state
 * @return void
 * @see uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_submit()
 */
function uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_alter(&$form, $form_state) {
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');

  foreach ($fields as $fieldname => $field) {
    // Generate field
    $generated_field = $field->generate();

    // Set default value
    $oValue = uc_extra_fields_pane_value_load($form['panes']['address']['aid']['#value'], UCXF_VALUE_ADDRESS, $field->field_id);
    $generated_field['#default_value'] = $oValue->value;

    // Add field to pane
    $form['panes']['address'][$fieldname] = $generated_field;
  }

  // Add submit function
  $form['#submit'][] = 'uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_submit';
}

/**
 * uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_submit()
 * @param array $form
 * @param array $form_state
 * @see uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_alter()
 */
function uc_extra_fields_pane_addressfields_uc_addresses_get_address_form_submit($form, $form_state) {
  $address = $form_state['values']['panes']['address'];

  // Load field definitions
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');

  // MegaChriz:
  //  address id is not available when address is added, only when it is edited,
  //  we need to wait until uc_addresses is improved on this part.
  //  see: drupal.org/node/851658
  //  with the patch applied in #4, $form_state['storage']['aid'] will contain the address id.

  // Get address id
  // WARNING: $form_state['storage']['aid'] will never contain anything with current version of uc_addresses (v1.0)
  $address_id = ($address['aid'])? $address['aid']:$form_state['storage']['aid'];

  // Save values
  if ($address_id) {
    foreach ($fields as $fieldname => $field) {
      uc_extra_fields_pane_value_save(
        array(
          'element_id' => $address_id,
          'element_type' => UCXF_VALUE_ADDRESS,
          'field_id' => $field->field_id,
          'value' => $address[$fieldname],
        )
      );
    }
  }
}

// --------------------------
// UC Addresses integration: user_register
// --------------------------

/**
 * uc_extra_fields_pane_addressfields_user_register_form_alter
 * @param array $form
 * @param array $form_state
 * @return void
 * @see uc_extra_fields_pane_user()
 */
function uc_extra_fields_pane_addressfields_user_register_form_alter(&$form, $form_state) {
  $fields = uc_extra_fields_pane_load_fields_from_db('extra_address');

  foreach ($fields as $fieldname => $field) {
    $form[0][$fieldname] = $field->generate();
  }
}
