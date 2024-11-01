<?php
	global $TLPtestimonial;
	$settings = get_option($TLPtestimonial->options['settings']);
?>
<div class="wrap">

	<div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>
	<h2><?php _e('TLP Testimonial Settings', TPL_TESTIMONIAL_SLUG); ?></h2>
	<div id="response" class="updated"></div>
	<form id="tlp-settings" onsubmit="tlptestiSettings(this); return false;">


		<h3><?php _e('General settings',TPL_TESTIMONIAL_SLUG);?></h3>

		<table class="form-table">

			<tr>
				<th scope="row"><label for="imgWidth"><?php _e('Image Size',TPL_TESTIMONIAL_SLUG);?></label></th>
				<td><input name="img[width]" type="text" value="<?php echo (isset($settings['img']['width']) ? @$settings['img']['width'] : 150); ?>" size="4" class=""> * <input name="img[height]" type="text" value="<?php echo (isset($settings['img']['height']) ? @$settings['img']['height'] : 150); ?>" size="4" class=""> <?php _e('(Width * Height)',TPL_TESTIMONIAL_SLUG); ?></td>
			</tr>

		</table>
		<p class="submit"><input type="submit" name="submit" id="tlpSaveButton" class="button button-primary" value="Save Changes"></p>

		<?php wp_nonce_field( $TLPtestimonial->nonceText(), 'tlp_nonce' ); ?>
	</form>

	<p><?php _e('Short Code',TPL_TESTIMONIAL_SLUG); ?> : [tlptestimonial number="4"]</p>

</div>
