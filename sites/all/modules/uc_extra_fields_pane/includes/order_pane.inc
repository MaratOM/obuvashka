<?php
/**
 * @file
 * Order pane handling
 *
 * Note: this file and the functions in this file will be removed in a future version.
 * The theme function will be moved to a file called 'theme.inc'.
 */

// MegaChriz: maybe the code specific for addresses should be moved to a separate function.
/**
 * Wrapped function to load any order pane of $pane_type
 * @param string $op
 * @param mixed $arg1
 * @param mixed $arg2
 * @param string $pane_type
 * @return mixed
 */
function uc_extra_fields_pane_order_handler($op, $arg1, $arg2=NULL, $pane_type=NULL) {
  $addresstype = '';
  if ($pane_type == 'extra_delivery') {
    $addresstype = 'delivery';
  }
  elseif ($pane_type == 'extra_billing') {
    $addresstype = 'billing';
  }

  switch ($op) {
    case 'edit-theme':
      $pane_fields = uc_extra_fields_pane_load_fields_from_db($pane_type);

      // Remove pane_fields that are not enabled
      foreach ($pane_fields as $fieldname => $pane_field) {
        if (!$pane_field->enabled && isset($arg1['ucxf_' . $pane_type][$fieldname])) {
          unset($arg1['ucxf_' . $pane_type][$fieldname]);
        }
      }

      $output = theme('uc_extra_fields_pane_order_pane', $arg1['ucxf_' . $pane_type]);
      return $output;

    case 'edit-process':
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      $changes['ucxf_' . $pane_type] = array();

      // dynamically generate form elements
      if (count($fields)) {
        foreach ($fields as $fieldname => $field) {
          if (isset($arg1['ucxf_' . $pane_type][$field->db_name])) {
            $fixedfieldname = ($addresstype)? $fieldname . '_' . $addresstype:$fieldname;
            $changes['ucxf_' . $pane_type][$fixedfieldname] = $arg1['ucxf_' . $pane_type][$field->db_name];
          }
        }
      }
      return $changes;

    case 'edit-form':
      $form['ucxf_' . $pane_type] = array(
        '#type' => 'fieldset',
        '#title' => t('Additional order information'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        '#tree' => TRUE,
      );
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      // Dynamically generate form elements
      if (count($fields)) {
        foreach ($fields as $field) {
          if ($field->enabled) {
            $generated_field = $field->generate();
            switch ($pane_type) {
              case 'extra_delivery':
              case 'extra_billing':
                $order_field_name = $field->db_name . '_' . $addresstype;
                $generated_field['#default_value'] = isset($arg1->extra_fields[$order_field_name]) ? $arg1->extra_fields[$order_field_name] : NULL;
                break;

              default:
                $generated_field['#default_value'] = isset($arg1->extra_fields[$field->db_name]) ? $arg1->extra_fields[$field->db_name] : NULL;
                break;
            }
            // On the order edit form, a generated field shouldn't be a hidden field.
            // In this case the field will be set to a normal textfield, so it's editable.
            if ($generated_field['#type'] == 'hidden') {
              $generated_field['#type'] = 'textfield';
              // Unset value, field already has an default value
              unset($generated_field['#value']);
              // Set title and size of field.
              $generated_field['#title'] = $field->output('label');
              $generated_field['#size'] = 32;
            }

            // In case of select fields, add default value as an option if it is not an available option yet.
            if ($generated_field['#type'] == 'select') {
              $default_value = $generated_field['#default_value'];
              if ($default_value != '' && !isset($generated_field['#options'][$default_value])) {
                $generated_field['#options'][$default_value] = t('Deleted option: @option', array('@option' => $default_value));
              }
            }

            // Do not make any field required on the edit form
            $generated_field['#required'] = FALSE;
            // Add generated field to form
            $form['ucxf_' . $pane_type][$field->db_name] = $generated_field;
          }
        }
      }
      return $form;

    // customer or administrator order detail page.
    case 'customer':

    case 'view':
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      $output = '';

      // Load values (depends on pane type)
      switch ($pane_type) {
        case 'extra_delivery':
          $values = uc_extra_fields_pane_value_list_load($arg1->order_id, UCXF_VALUE_ORDER_DELIVERY);
          break;
        case 'extra_billing':
          $values = uc_extra_fields_pane_value_list_load($arg1->order_id, UCXF_VALUE_ORDER_BILLING);
          break;
        default:
          $values = uc_extra_fields_pane_value_list_load($arg1->order_id, UCXF_VALUE_ORDER_INFO);
          break;
      }
      $custom_order_fields = array();

      if (count($fields)) {
        foreach ($fields as $field) {
          // warning: user input --> check_plain
          if (isset($values[$field->db_name])) {
            // Only display if it may be displayed
            if ($field->may_display('order')) {
              $field_contents = $field->output_value($values[$field->db_name]['value']);
              $custom_order_fields[] = '<strong>' . $field->output('label') . '</strong>: ' . $field_contents . '<br />';
            }
          }
        }
      }

      if (count($custom_order_fields)) {
        $output .= '<br />' . implode('<br />', $custom_order_fields);
      }
      return $output;
  }
}

// -----------------------------------------------------------------------------
// THEMING
// -----------------------------------------------------------------------------

/**
 * Theme the extra fields forms on the order edit page.
 * @param array $form
 * @return string
 * @ingroup themeable
 */
function theme_uc_extra_fields_pane_order_pane($form) {
  $output = '';
  if (count(element_children($form)) > 0) {
    $output .= '<table class="order-edit-table">';
    foreach (element_children($form) as $key => $field) {
      $title = $form[$field]['#title'];
      unset($form[$field]['#title']);
      unset($form[$field]['#description']);
      if (!empty($title)) {
        // Add colon only if title is not empty
        $title .= ':';
      }
      $output .= '<tr><td class="oet-label">' . $title . '</td><td>' . drupal_render($form[$field]) . '</td></tr>';
    }
    $output .= '</table>';
  }
  return $output;
}
