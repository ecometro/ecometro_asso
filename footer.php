<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<footer>
<?php 
	
	wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'nav nav-pills' ) ); 
	get_sidebar('footer-icons');	
?>	
	  		
  		<!-- 
  		<li><a href="#"><img src="<?php //echo get_stylesheet_directory_uri();  ?>/img/icono.jpg" width="42" height="16"></a></li>
        <li><a href="#"><img src="<?php //echo get_stylesheet_directory_uri();  ?>/img/icono.jpg" width="42" height="16"></a></li>
        <li><a href="#"><img src="<?php //echo get_stylesheet_directory_uri();  ?>/img/icono.jpg" width="42" height="16"></a></li>
         -->
	
    <a class="github" href="https://github.com/montera34/ecometro" target="_blank"><img src="<?php echo get_stylesheet_directory_uri();  ?>/img/github.png" width="150" height="33" alt="Fork me on Github"></a> 
    <div class="span12">
        <div class="span7">
        <?php get_sidebar('patrocinadores');  ?>
        </div>
        <div class="span5">
            <?php get_sidebar('colaboradores');  ?>
        </div>
    </div>
</footer>
    </div>
  
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();  ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();  ?>/js/bootstrap-collapse.js"></script>
    <script>
    $(document).scroll(function(){
    // If has not activated (has no attribute "data-top"
    if (!$('.subnav').attr('data-top')) {
        // If already fixed, then do nothing
        if ($('.subnav').hasClass('subnav-fixed')) return;
        // Remember top position
        var offset = $('.subnav').offset()
        $('.subnav').attr('data-top', offset.top);
    }

    if ($('.subnav').attr('data-top') - $('.subnav').outerHeight() <= $(this).scrollTop())
        $('.subnav').addClass('subnav-fixed');
    else
        $('.subnav').removeClass('subnav-fixed');
});
    </script>

<?php wp_footer(); ?>
</body>
</html>