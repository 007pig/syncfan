<?php
/**
 * Template part for displaying single posts.
 *
 * @package Realistic
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col'); ?>>
	<div class="breadcrumb mdl-color-text--grey-500" xmlns:v="http://rdf.data-vocabulary.org/#"><?php realistic_breadcrumb(); ?></div>   
	<header class="entry-header">
		<?php 
		_e(' in ', 'realistic');
		$category = get_the_category();
		echo '<span class="category"><a class="mdl-button mdl-js-button" href="' . get_category_link( $category[0]->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "realistic" ), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a></span>';
		?>
		<h1 class="entry-title">
			<?php the_field('movie_name_chinese'); ?>
			<?php the_title(); ?>
			<span class="year">(<?php the_field('movie_year'); ?>)</span>
		</h1>
		<?php realistic_post_meta(); ?>
	</header><!-- .entry-header -->

	<div class="clearfix">
		<?php if (has_post_thumbnail()) { ?>
			<div class="featured-image">
				<?php the_post_thumbnail('syncfan-featured-image-movie'); ?>
			</div>
		<?php } ?>
		<div class="movie-metadata">
			<table>
				<tr>
					<td>又　　名：</td>
					<td><?php the_field('movie_alias') ?></td>
				</tr>
				<tr>
					<td>类　　别：</td>
					<td><?php
						$movie_types = get_field('movie_type');
						$out = '';
						foreach ($movie_types as $movie_type) {
							$out .= $movie_type->name . ' / ';
						}
						echo substr($out, 0, -3);
						?></td>
				</tr>
				<tr>
					<td>国　　家：</td>
					<td><?php
						$movie_countries = get_field('movie_country');
						$out = '';
						foreach ($movie_countries as $movie_country) {
							$out .= $movie_country->name . ' / ';
						}
						echo substr($out, 0, -3);
						?></td>
				</tr>
				<tr>
					<td>语　　言：</td>
					<td><?php
						$movie_languages = get_field('movie_language');
						$out = '';
						foreach ($movie_languages as $movie_language) {
							$out .= $movie_language->name . ' / ';
						}
						echo substr($out, 0, -3);
						?></td>
				</tr>
				<tr>
					<td>上映日期：</td>
					<td><?php the_field('movie_release_date') ?></td>
				</tr>
				<tr>
					<td>片　　长：</td>
					<td><?php the_field('movie_length') ?> 分钟</td>
				</tr>
				<tr>
					<td>导　　演：</td>
					<td><?php
						$movie_directors = get_field('movie_director');
						$out = '';
						foreach ($movie_directors as $movie_director) {
							$out .= $movie_director->name . ' / ';
						}
						echo substr($out, 0, -3);
						?></td>
				</tr>
				<tr>
					<td>演　　员：</td>
					<td><?php
						$movie_actors = get_field('movie_actor');
						$out = '';
						foreach ($movie_actors as $movie_actor) {
							$out .= $movie_actor->name . ' / ';
						}
						echo substr($out, 0, -3);
						?></td>
				</tr>
				<?php if (get_field('movie_douban_rating')): ?>
					<tr>
						<td>豆瓣评分：</td>
						<td><?php the_field('movie_douban_rating') ?>/10</td>
					</tr>
				<?php endif; ?>
				<?php if (get_field('movie_douban_link')): ?>
					<tr>
						<td>豆瓣链接：</td>
						<td><a href="<?php the_field('movie_douban_link') ?>" target="_blank">点击这里</a></td>
					</tr>
				<?php endif; ?>
				<?php if (get_field('movie_imdb_rating')): ?>
					<tr>
						<td>IMDb评分：</td>
						<td><?php the_field('movie_imdb_rating') ?>/10</td>
					</tr>
				<?php endif; ?>
				<?php if (get_field('movie_imdb_link')): ?>
					<tr>
						<td>IMDb链接：</td>
						<td><a href="<?php the_field('movie_imdb_link') ?>" target="_blank">点击这里</a></td>
					</tr>
				<?php endif; ?>
			</table>
		</div>

	</div>

	<div class="entry-content">

		<?php syncfan_show_download_resources(); ?>

		<h5>内容简介</h5>

		<div class="entry-content clearfix">
			<?php
			the_content();

			wp_link_pages(array(
				'before' => '<div style="clear: both;"></div><div class="pagination clearfix">'
					. __('Pages:', 'colormag'),
				'after' => '</div>',
				'link_before' => '<span>',
				'link_after' => '</span>'
			));
			?>
		</div>

		<?php
		$images = get_field('movie_screenshot');
		if ($images):
			?>

			<h5>精彩截图</h5>

			<ul class="movie-screenshot-container clearfix">
				<?php foreach( $images as $image ): ?>
					<li>
						<a href="<?php echo $image['url']; ?>">
							<img src="<?php echo $image['sizes']['syncfan-movie-screenshot']; ?>"
								 width="<?php echo $image['sizes']['syncfan-movie-screenshot-width']; ?>"
								 height="<?php echo $image['sizes']['syncfan-movie-screenshot-height']; ?>"
								 alt="<?php echo $image['alt']; ?>" />
						</a>
						<p><?php echo $image['caption']; ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>


	</div><!-- .entry-content -->
</article><!-- #post-## -->
<?php realistic_related_posts(); ?>
