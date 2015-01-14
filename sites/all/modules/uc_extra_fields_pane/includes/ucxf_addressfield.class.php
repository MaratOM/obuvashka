<?php
/**
 * @file
 * Class for Extra Fields Pane Address field
 */

class ucxf_addressfield extends ucxf_field {
  // -----------------------------------------------------------------------------
  // CONSTRUCT
  // -----------------------------------------------------------------------------

  /**
   * ucxf_customfield object constructor
   * @access public
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->returnpath = 'admin/store/settings/checkout/edit/fields';
    $this->pane_types = array('extra_delivery', 'extra_billing');
  }

  // -----------------------------------------------------------------------------
  // FORMS
  // -----------------------------------------------------------------------------

  /**
   * Override of ucxf_field::edit_form().
   *
   * Get the edit form for the item.
   *
   * @access public
   * @return array
   */
  public function edit_form() {
    $form = parent::edit_form();

    // Unset pane type field, the pane types asking is handled differently
    unset($form['ucxf']['pane_type']);

    // Unset also weight field, because the weight for an address field is implemented differently
    unset($form['ucxf']['weight']);

    // Add form element to ask in which panes they need to appear
    $form['ucxf']['panes'] = array(
      '#title' => t('Select the panes the field must get into'),
      '#type' => 'checkboxes',
      '#options' => array(
        'extra_delivery' => t('Delivery pane'),
        'extra_billing' => t('Billing pane'),
      ),
      '#weight' => 5,
      '#default_value' => $this->pane_types,
    );
    return $form;
  }

  /**
   * Override of ucxf_field::edit_form_submit().
   *
   * Submit the edit form for the item.
   *
   * @param array $form
   * @param array $form_state
   * @access public
   * @return void
   */
  public function edit_form_submit($form, &$form_state) {
    $field = $form_state['values']['ucxf'];
    // Check if user wants field in both delivery and billing pane
    // only delivery pane = 'extra_delivery'
    // only billing pane = 'extra_billing'
    // both panes = 'extra_delivery|extra_billing'
    foreach ($field['panes'] as $pane_type) {
      if ($pane_type) {
        $form_state['values']['ucxf']['pane_type'][$pane_type] = $pane_type;
      }
    }
    parent::edit_form_submit($form, $form_state);
  }
}