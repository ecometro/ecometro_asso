<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-footer-icons' ) ) : ?>
		<ul class="nav nav-pills">
			<?php dynamic_sidebar( 'sidebar-footer-icons' ); ?>
		</ul>
	<?php endif; ?>