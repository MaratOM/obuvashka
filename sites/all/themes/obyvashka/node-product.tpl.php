<?php
// $Id: node.tpl.php,v 1.4.2.1 2009/08/10 10:48:33 goba Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
?>
<?php
  $terms = '';
  $attr_options = '';
  $vocs = taxonomy_get_vocabularies();
  foreach ($node->taxonomy as $term) {
    if($term->vid == 11) {
      $over_icon_tid = $term->tid;
    }
    else {
      $terms .= '<p><span class="product-term-name">'.$vocs[$term->vid]->name.':</span> '.$term->name.'</p>';      
    }
  }
  
  $result = db_query("SELECT uao.name AS name FROM {uc_attribute_options} AS uao
          INNER JOIN {uc_product_options} AS upo
          ON uao.oid = upo.oid
          WHERE upo.nid = '%s'
          ", $node->nid);
  
  while($row = db_fetch_object($result)) {
    $attr_options .= $row->name . ' ';
  }
  
?>


<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

<?php print $picture ?>

<?php if (!$page): ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

<div class="title">
      <?php print $title ?>
  </div>
  
  
  <?php if ($terms): ?>
    <div class="terms terms-inline"><?php print $terms ?><br/>
    
  <?php if ($attr_options != ''): ?>
    <p class="attr-options-title">Доступные размеры:</p>  
    <p class="attr-options"><?php print $attr_options ?></p>
  <?php endif;?>      
    
    </div>
  <?php endif;?>
  
 

  <div class="content">
    <?php if ($over_icon_tid): ?>      
      <img class="over-icon-big" src="<?php print base_path() . path_to_theme()?>/images/icon-big-<?php print $over_icon_tid?>.png" />
    <?php endif;?>
    <?php print $content ?>
  </div>

  <?php print $links; ?>
</div>