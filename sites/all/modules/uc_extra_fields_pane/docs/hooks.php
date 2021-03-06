<?php
/**
 * @file
 * These hooks are invoked by the Extra Fields Pane module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Perform actions when something happens with a field.
 * Note that this only about the field itself, not about filled in values by
 * customers.
 *
 * @param ucxf_field $field
 *   An instance of ucxf_field, representing a row from the uc_extra_fields
 *   table.
 * @param string $op
 *   The action that is occurring. Possible values:
 *   - load: called when the field data is loaded. This hook is invoked in
 *     uc_extra_fields_pane_field_load().
 *   - insert: called when a new field is created. This hook is invoked in the
 *     method save() from the ucxf_field class.
 *   - update: called when a field's settings is updated. This hook is invoked
 *     in the method save() from the ucxf_field class.
 *   - delete: called when a field is deleted.  This hook is invoked in the
 *     method delete() from the ucxf_field class.
 * @return void
 */
function hook_ucxf_field($field, $op) {
  switch ($op) {
    case 'delete':
      // The field is deleted, delete previous filled in values (this does not
      // happen by default).
      db_query("DELETE FROM {uc_extra_fields_values} WHERE field_id=%d", $field->field_id);
      break;
  }
}

/**
 * Add display options for a field or a field value.
 * Useful if you have additional places where you display a value filled
 * in by the customer.
 *
 * When you have a display option like in the example below, you can ask the
 * instance of ucxf_field whether the value (or field) may displayed like this:
 * $field->may_display('invoice');
 *
 * @param ucxf_field $field
 *   An instance of ucxf_field, representing a row from the uc_extra_fields
 *   table.
 * @return array
 */
function hook_ucxf_display_options($field) {
  $options = array(
    'invoice' => array(
      'title' => t('Invoice'),
      'description' => t('When checked, this value will be shown on my custom created invoice.'),
    ),
  );
  return $options;
}

/**
 * @} End of "addtogroup hooks".
 */
