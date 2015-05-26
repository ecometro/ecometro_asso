<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<!-- <meta name="viewport" content="width=device-width; initial-scale = 1.0; maximum-scale=1.0; user-scalable=no" />-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
<?php if(is_user_logged_in()):?>
	<style>
    	.navbar { margin-top: 28px; !important;}
    </style>
<?php endif; ?>    
</head>

<body data-spy="scroll" data-target=".subnav" data-offset="50">
	<div class="navbar navbar-fixed-top">
  		<div class="navbar-inner">
   			<div class="container">
     			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
      			</a>
      			
        		<?php wp_nav_menu( array( 'theme_location' => 'header', 'container_class' => 'nav-collapse', 'menu_class' => 'nav', 'walker' => new Ecometro_Walker_Nav_Menu() ) ); ?>
              
    		</div>
        </div>
	</div>

<div class="container">
<header class="jumbotron subhead" id="overview">
  <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="ecómetro">ecómetro</a></h1>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="ecómetro"><img src="<?php echo get_stylesheet_directory_uri();  ?>/img/imagotipo-ecometro.png" width="89" height="85" alt="ecómetro"></a>
<div class="subnav">
  <!--
  <ul class="nav nav-pills">
    <li class="active"><a href="<?php //echo esc_url( home_url( '/' ) ); ?>">ESP</a></li>
    <li><a href="<?php //echo esc_url( home_url( '/' ) . '?lang=en'); ?>">ENG</a></li>        
  </ul>
  -->
</div>
<div class="breadcrumbs">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
</div>
</header>