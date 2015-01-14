<?php
/**
 * @file
 * Checkout pane handling
 *
 * Note: this file and the functions in this file will be removed in a future version.
 * The theme function will be moved to a file called 'theme.inc'.
 */

/**
 * Pane handler
 * @param string $op
 * @param mixed $arg1
 * @param mixed $arg2
 * @param string $pane_type
 * @return mixed
 */
function uc_extra_fields_pane_load_pane_type($op, &$arg1 = NULL, $arg2 = NULL, $pane_type) {
  switch ($op) {
    case 'settings':
      return;

    case 'view':
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      $contents = array();
      // dynamically generate form elements
      $description = '';
      if (count($fields)) {
        foreach ($fields as $field) {
          $generated_field = $field->generate();

          switch ($field->value_type) {
            case ucxf_field::UCXF_WIDGET_TYPE_PHP:
            case ucxf_field::UCXF_WIDGET_TYPE_CONSTANT:
              break;
            default:
              // Adding default value for every field except for php and constant
              if (isset($arg1->extra_fields[$field->db_name])) {
                $generated_field['#default_value'] = $arg1->extra_fields[$field->db_name];
              }
              break;
          }

          // Add field
          $contents['extra_fields'][$field->db_name] = $generated_field;

          // If the field happens to be a hidden field, display value if the user asks
          // This currently applies to value_type of php and constant ONLY
          if ($generated_field['#type'] == 'hidden' && $field->may_display('checkout')) {
            $contents['extra_fields'][$field->db_name . '_i'] = array(
              '#type' => 'item',
              '#title' => $field->output('label'),
              '#value' => $generated_field['#value'],
            );
          }
        }
      }
      return array('description' => $description, 'contents' => $contents, 'theme' => 'uc_extra_fields_pane_checkout_pane');

    case 'process':
      // Initialize and fill array
      if ($arg1->extra_fields == NULL) {
        $arg1->extra_fields = array();
      }
      $arg1->extra_fields = array_merge($arg1->extra_fields, $arg2['extra_fields']);
      return TRUE;

    case 'review':
      $fields = uc_extra_fields_pane_load_fields_from_db($pane_type);
      if (count($fields)) {
        foreach ($fields as $field) {
          // Display it as data
          if ($field->may_display('review')) {
            $data = $field->output_value($arg1->extra_fields[$field->db_name]);
            $review[] = array(
              'title' => $field->output('label'),
              'data' => $data,
            );
          }
        }
      }
      return $review;
  }
}

// -----------------------------------------------------------------------------
// THEMING
// -----------------------------------------------------------------------------

/**
 * Theme the extra fields forms on the checkout page.
 * @param array $form
 * @return string
 * @ingroup themeable
 */
function theme_uc_extra_fields_pane_checkout_pane($form) {
  $req = '<span class="form-required">*</span>';
  $output = '<div class="uc-extra-fields-pane-table address-pane-table"><table>';
  foreach (element_children($form['extra_fields']) as $field) {
    if ($form['extra_fields'][$field]['#type'] != 'hidden') {
      $title = $form['extra_fields'][$field]['#title'];
      unset($form['extra_fields'][$field]['#title']);
      $output .= '<tr><td class="field-label">';
      if ($form['extra_fields'][$field]['#required']) {
        $output .= $req;
      }
      if (!empty($title)) {
        // Add title with colon only if title is not empty
        $output .= '<strong>' . $title .':</strong>';
      }
      $output .= '</td><td>'. drupal_render($form['extra_fields'][$field]) .'</td></tr>';
    }
  }
  $output .= '</table></div>';

  foreach (element_children($form) as $element) {
    $output .= drupal_render($form[$element]);
  }

  return $output;
}
