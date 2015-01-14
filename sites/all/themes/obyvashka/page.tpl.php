<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28967528-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<meta name="google-site-verification" content="CNhg4jUWelGvjhNy21tPRo-XJc2ag4fewz_jJE-fsoE" />
<meta name='yandex-verification' content='553cb829bfb3dc98' />
<?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <script type="text/javascript"><?php /*No Flash of unformatted date in IE6*/ ?></script>
    
    
<!--[if IE 6]>
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_path().path_to_theme(); ?>/fix-ie.css" />
<script src="<?php print base_path().path_to_theme(); ?>/js/DD_belatedPNG.js"></script>
<script>
  /* EXAMPLE */
  DD_belatedPNG.fix('#shoes,#laces,#logo,.cart-block-summary,.seasons-list');
  
  /* string argument can be any CSS selector */
  /* .png_bg example is unnecessary */
  /* change it to what suits you! */
</script>
<![endif]--> 
</head>
<body>
    <div id="container_bg">
	<div id="container">
		<div id="top" class="region header"><div id="top_text"><h1>магазины детской обуви</h1></div>
				<a href="<?php print $front_page ?>" title="<?php print t('Home') ?>">
				<img src="<?php print base_path().path_to_theme(); ?>/images/logo_clear.png" id="logo" /></a>
				<img src="<?php print base_path().path_to_theme(); ?>/images/shoes.png" id="shoes" />
				<?php print $header ?>
				<a id="cart-link" href="/cart">&nbsp;</a>
	  </div>
		<div id="con_wrap">
			<div id="con_back"><img src="<?php print base_path().path_to_theme(); ?>/images/laces.png" id="laces" />
				<div id="con_side"  class="region left"><?php print $left ?>
					<div id="color">
					  <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
					  <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
					</div>
					<div id="step"><img src="<?php print base_path().path_to_theme(); ?>/images/step.jpg" /></div>
					<div class="region left_bottom"><?php print $left_bottom ?></div>
				</div><!--END con_side-->
				<div id="con_main">
				 <!--<div id="con_center">-->
					<?php if(arg(0)=='faq') :?>
						 <div class="title"><?php print $title?></div>
					<?php endif; ?>
					<?php if(arg(0)=='map') :?>
						 <div class="title">Наши магазины</div>
					<?php endif; ?>
					<?php //print $breadcrumb ?><!--{l2d2}-->
					<!--<div id="e-shop-phone">-->
            <?php //print $contact_info;?>
          <!--</div>-->
					<?php print $menu_e_shop;?>
					<?php print $content_top;?>
					<div class="clear-block"><?php print $tabs ?>
					<!--<h2 class="title"><?php //print $title ?></h2>-->
					<?php print $messages ?>
					<?php print $content ?><!--inctext--><!--{/l2d2}--></div>
					<?php if(drupal_is_front_page()) :?>
						<!--<div id="front_page_content"><?php //print $front_page_content;?></div>-->
					<?php endif; ?>	
				<!--</div>-->
				<!--<div id="con_right" class="region right">
				  <?php //print $right ?>
				</div>-->

				</div><!--END con_main-->
			</div><!--END con_back-->
			<div id="footer_im"><div id="footer"><?php print $footer_message . $footer ?>
<br/>
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t52.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров и"+
" посетителей за 24 часа' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->
<br/><a href="/content/karta-saita">Карта сайта</a>
</div>
			</div><!--END footer_im-->
		</div><!--END con_wrap-->
		
	</div><!--END container-->
	<div id="foot_line"></div>

	
      <!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter12371653 = new Ya.Metrika({id:12371653, enableAll: true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/12371653" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

    </div><!-- END container_bg-->
<?php print $scripts ?>
<?php print $closure ?>
</body>
</html>
