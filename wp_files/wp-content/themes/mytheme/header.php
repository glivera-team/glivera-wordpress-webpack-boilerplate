<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="description" content="<?php bloginfo('description') ?>" />

	<?php wp_head(); ?>

</head>

<body>
	<header class="header">
		<?php if (get_field('logo_header', 'option')) { ?>
			<a class="logo" href="<?php echo home_url(); ?>">
				<img class="logo_img" src="<?php the_field('logo_header', 'option'); ?>" alt="logo" />
			</a>
		<?php } ?>

		<nav class="main_nav">
			<?php
			wp_nav_menu(array(
				'theme_location'  => 'header_menu',
				'menu'            => 'Main menu',
				'container'       => '',
				'container_class' => '',
				'menu_class'      => 'main_menu_list',
				'walker'          => new Custom_Nav_Walker()
			));
			?>
		</nav>

	</header>

	<div class="wrapper">
		<div class="base">