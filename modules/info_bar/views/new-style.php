<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

?>
<div id="main">
    <div id="content"  class="bootstrap">
		<div class="wrap smile-add-style bend">
			<div class="wrap-container">
				<div class="msg"></div>

				<div id="search-sticky"></div>
				<div class="row smile-style-search-section">
					<div class="container">
						<div class="smile-search-ip-sec col-sm-6 col-sm-offset-3">
							<input type="search" autofocus="autofocus" class="js-shuffle-search" id="style-search" name="style-search" placeholder="<?php echo 'Search Template'; ?>" />
						</div>
					</div>
				</div>

				<div class="bend-content-wrap smile-add-style-content">

					<div class="container ">
						<div class="smile-style-category">
							<?php
							if ( function_exists( 'smile_style_dashboard' ) ) {
								smile_style_dashboard( 'Convert_Plug_Smile_Info_Bars', 'smile_info_bar_styles', 'info_bar' );
							}
							?>
							<div class="col-xs-6 col-sm-4 col-md-4 shuffle_sizer"></div>
							<!-- .styles-list -->
						</div>
						<!-- .smile-style-category -->
					</div>
					<!-- .container -->
					<div id="cp-scroll-up">
						<a title="Scroll up" href="#top"><i class="connects-icon-small-up" ></i></a>
					</div>
				</div>
				<!-- .bend-content-wrap -->
			</div>
			<!-- .wrap-container -->
		</div>
<!-- .wrap -->
	</div>
	</div><!-- #content -->
</div><!-- #main -->

