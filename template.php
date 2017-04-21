<?php

//echo "you are here"; exit(0);

/**

 * @file

 * This file is empty by default because the base theme chain (Alpha & Omega) provides

 * all the basic functionality. However, in case you wish to customize the output that Drupal

 * generates through Alpha & Omega this file is a good place to do so.

 * 

 * Alpha comes with a neat solution for keeping this file as clean as possible while the code

 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders

 * for more information on this topic.

 */

 

function assure_preprocess_html(&$vars){


//db_query("DELETE FROM {cache};");
	// Add Modernizr script

	// $script = path_to_theme() . '/js/script.js';

	// drupal_add_js($script, array('group'=> JS_LIBRARY, 'weight' => -1));



	drupal_add_css('http://fonts.googleapis.com/css?family=Lato:400,700', 'external');



	$clicky_code = '<script src="//static.getclicky.com/js" type="text/javascript"></script>

<script type="text/javascript">try{ clicky.init(100616325); }catch(e){}</script>

<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100616325ns.gif" /></p></noscript>';

	$clicky = array(

		'#type' => 'markup',

		'#markup' => $clicky_code,

		);

	drupal_add_html_head($clicky, 'get_clicky');





$gooogle_analytic = "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-24124061-1', 'auto');
  ga('send', 'pageview');";

drupal_add_js($gooogle_analytic,
    array('type' => 'inline', 'scope' => 'footer', 'weight' => 5)
  );


}



