<?php
    header('Content-Type: text/xml; charset=utf-8');
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<articles>
<?php
	$args = array(
		'post_type'				=> 'post',
		'posts_per_page'	=> 50,
		'orderby'					=> 'modified',
    'order'						=> 'DESC',
	);
	if (!empty($wp->query_vars['category'])) {
		$args['category_name'] = $wp->query_vars['category'];
	}
	$the_query = new WP_Query( $args ); $i=0;
	while ( $the_query->have_posts() ) : $the_query->the_post();

		$feat_id			= get_post_thumbnail_id(get_the_id());
		$feat_og_id		= get_post_meta( get_the_id(), '_yoast_wpseo_opengraph-image-id', true );
		$feat_line_id = ($feat_og_id) ? $feat_og_id : $feat_id;
		$feat_image		= get_site_url() . wp_get_attachment_url($feat_line_id);
		$categories		= get_the_category();
		if ( ! empty( $categories ) ) {
			$category = esc_html( $categories[0]->name );
		}
		if($i==0) {
			echo '<UUID>post-' . get_the_id() . '-' . get_the_modified_time('U', get_the_id()) . '</UUID>';
			echo '<time>' . get_post_time('U', true) . '000</time>';
		}
?>
	<article>
		<ID><?php echo get_the_id(); ?></ID>
		<nativeCountry>TH</nativeCountry>
		<language>th</language>
		<publishCountries>
			<country>TH</country>
		</publishCountries>
		<startYmdtUnix><?php echo get_post_time('U', true); ?>000</startYmdtUnix>
		<endYmdtUnix><?php echo strtotime('+30 day', get_post_time('U', true)); ?>000</endYmdtUnix>
		<title><?php the_title(); ?></title>
		<category><?php echo (!empty($category)) ? $category : get_bloginfo( 'name' ); ?></category>
		<publishTimeUnix><?php echo get_post_time('U', true); ?>000</publishTimeUnix>
		<?php echo (get_the_modified_time('U', get_the_id())) ? '<updateTimeUnix>'.get_the_modified_time('U',  get_the_id()).'000</updateTimeUnix>' : '' ; ?>
		<contents>
			<image>
				<title><?php the_title(); ?></title>
				<url><?php echo (!empty($feat_image)) ? $feat_image : ""; ?></url>
			</image>
			<text>
				<content>
					<![CDATA[
						<?php
						$content = apply_filters( 'the_content', get_the_content() );
						echo do_shortcode(wpautop($content));
						?>
					]]>
				</content>
			</text>
		</contents>
		<?php
			$post_link = get_the_permalink();
			$tags = wp_get_post_tags(get_the_id());

			if ($tags):
		?>
		<recommendArticles>
			<?php
				$tag_ids = array();

				foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
					$args=array(
						'tag__in' => $tag_ids,
						'post__not_in' => array(get_the_id()),
						'posts_per_page'=>3
					);
				 
				$my_query = new wp_query( $args );

				while( $my_query->have_posts() ):
					$my_query->the_post();
			?>
			<article>
				<title><?php the_title(); ?></title>
				<url><?php the_permalink(); ?></url>
				<thumbnail><?php the_post_thumbnail_url(array('630','340')); ?></thumbnail>
			</article>
			<?php
				endwhile; // end post
			?>
		</recommendArticles>
		<?php
			endif; // end if
			//$post = $orig_post;
			wp_reset_query();
		?>
		<author><?php echo get_bloginfo( 'name' ); ?></author>
		<sourceUrl><?php echo $post_link; ?></sourceUrl>
	</article>
	<?php $i++; endwhile; wp_reset_postdata(); ?>
</articles>