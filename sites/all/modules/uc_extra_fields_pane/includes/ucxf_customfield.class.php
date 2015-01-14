<?php
/**
 * @file
 * Class for Extra Fields Pane Custom order field
 */

class ucxf_customfield extends ucxf_field {
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
    $this->returnpath = 'admin/store/settings/checkout/edit/extrafields';
  }
}