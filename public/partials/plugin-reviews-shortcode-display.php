<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://shopitpress.com
 * @since      1.0.0
 *
 * @package    Sip_Reviews_Shortcode_Woocommerce
 * @subpackage Sip_Reviews_Shortcode_Woocommerce/public/partials
 */

add_shortcode ('woocommerce_reviews', 'sip_review_shortcode_wc' );

	/**
	 * TO get aggregate rating
	 *
	 * @since    	1.0.0
	 * @return 		int
	 */
	function sip_wc_product_reviews_pro_get_product_rating_count( $product_id, $rating = null ) {
		global $wpdb;
		$where_meta_value 	= $rating ? $wpdb->prepare( " AND meta_value = %d", $rating ) : " AND meta_value > 0";
		$count 							= $wpdb->get_var( $wpdb->prepare("
														SELECT COUNT(meta_value) FROM $wpdb->commentmeta
														LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
														WHERE meta_key = 'rating'
														AND comment_post_ID = %d
														AND comment_approved = '1'
														", $product_id ) . $where_meta_value );
		return $count;
	}

	/**
	 * Sortcode function Template
	 *
	 * @since    	1.0.0
	 */
	function sip_review_shortcode_wc( $atts ) {
  	global $post,$wpdb;
  	extract( shortcode_atts(
			array(
				'id' 							=> '',
				'no_of_reviews' 	=> '',
				'product_title' 	=> '',
			), $atts )
		);

	  // if number of review not mention in shor coode then defaul value will be assign
		if( $no_of_reviews == "" ){
			$no_of_reviews = 5;
		}

  	// if product title is not mention by user in shortcode then get default value
		if( $product_title == "" ){
			$query 					= "SELECT post_title FROM {$wpdb->prefix}posts p WHERE p.ID = {$id} AND p.post_type = 'product' AND p.post_status = 'publish'";
			$product_title 	= $wpdb->get_var( $query );
		}

		$options = get_option( 'color_options' );
	  $star_color = ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
	  $bar_color = ( $options['bar_color'] != "" ) ? sanitize_text_field( $options['bar_color'] ) : '#AD74A2';

	  ?>
	  	<style type="text/css">.star-rating:before, .woocommerce-page .star-rating:before, .star-rating span:before {color: <?php echo $star_color; ?>;}</style>
	  <?php

	  if( $star_color != "")
	  	$star_color = "style='color:". $star_color .";'";

	  if( $bar_color != "")
	  	$bar_color = "background-color:".$bar_color .";";


		// To check that post id is product or not
		if( get_post_type( $id ) == 'product' ) {
			ob_start();
			// to get the detail of the comments etc aproved and panding status
			$comments_count = wp_count_comments( $id );
			?>

		<!--Wrapper: Start -->
		<section class="sip-rswc-wrapper"> 
		  <!--Main Container: Start -->
		  <section class="main-container">
		    <aside class="page-wrap">
		      <div class="share-wrap">
			      <?php //It calculate the rating for each star ?>
						<?php $ratings 	= array( 5, 4, 3, 2, 1 ); ?>
						<?php $result		=	0; ?>
						<?php 
							global $wpdb;
							$comments_approved 	= $wpdb->get_var( $wpdb->prepare("
																		SELECT COUNT(comment_ID) FROM $wpdb->comments AS c
																		WHERE c.comment_approved = 1
																		AND c.comment_parent = 0
																		AND c.comment_post_ID = %d
																		", $id ) );
						?>
						<?php foreach ( $ratings as $rating ) : ?>
						<?php
						if( $comments_count->approved > 0 ) {
							$count 				= sip_wc_product_reviews_pro_get_product_rating_count( $id, $rating );
							$percentage 	= $count * $rating / $comments_approved ;
							$result 			= $result + $percentage;
						}
						?>
						<?php endforeach; ?>
						<!-- it is not for display it is only to generate schema for goolge search result -->
						<div itemscope itemtype="http://schema.org/Product"  style="display:none;">
							<span itemprop="name"><?php echo $product_title; ?></span>
							<div class="star_container" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
								<span itemprop="itemReviewed"><?php echo $product_title; ?></span>
								<span itemprop="ratingValue"><?php echo number_format($result, 2); ?></span>
								<span itemprop="bestRating">5</span>
								<span itemprop="reviewcount" style="display:none;"><?php echo $comments_approved ?></span>
							</div>
							<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
								<span itemprop="priceCurrency" content="<?php $currency = get_woocommerce_currency(); echo $currency; ?>"><?php echo get_woocommerce_currency_symbol($currency) ?></span>
								<span itemprop="price" content="<?php $get_price = get_post_meta( $id , '_price' ); echo $get_price[0]; ?>"><?php echo get_woocommerce_currency_symbol(); echo $get_price[0]; ?></span>
							</div>
						</div>

		        <div class="share-left">
		          <div class="big-text"><?php echo number_format( $result, 2 ); ?> out of 5 stars</div>
		          <div class="sm-text"><?php echo $comments_approved ?> 
			          <span class="review-icon-image">reviews		
									<?php if(get_option('sip-rswc-affiliate-check-box') == "true") { ?>
										<?php $options = get_option('sip-rswc-affiliate-radio'); ?>
										<?php if( 'value1' == $options['option_three'] ) { $url = "https://shopitpress.com/?utm_source=referral&utm_medium=credit&utm_campaign=sip-reviews-shortcode-woocommerce" ; } ?>
										<?php if( 'value2' == $options['option_three'] ) { $url = "https://shopitpress.com/?offer=". esc_attr( get_option('sip-rswc-affiliate-affiliate-username')) ; } ?>
										<a class="sip-rswc-credit" href="<?php echo $url ; ?>" target="_blank" data-tooltip="These reviews were created with SIP Reviews Shortcode Plugin"></a>
									<?php } ?>
								</span>
							</div>
		        </div>
		        <div class="share-right">
		          <div class="product-rating-details">
		          	<table>
			            <tbody>
										<?php $count 			= 0 ; ?>
										<?php $percentage = 0 ; ?>
										<?php foreach ( $ratings as $rating ) : ?>
											<?php
											if( $comments_count->approved > 0 ) {
												$count 				= sip_wc_product_reviews_pro_get_product_rating_count( $id, $rating );
												$percentage 	= $count / $comments_count->approved * 100;
											}
											?>
											<?php $url = get_permalink(); ?>
											<tr>
												<td class="rating-number">
													<a href="javascript:void(0);" <?php echo $star_color; ?>><?php echo $rating; ?> <span class="fa fa-star"></span></a>
												</td>

												<td class="rating-graph">
													<a style="float:left; <?php echo $bar_color; ?> width: <?php echo $percentage; ?>%" class="bar" href="javascript:void(0);" title="<?php printf( '%s%%', $percentage ); ?>"></a>
												</td>

												<td class="rating-count">
													<a href="javascript:void(0);" <?php echo $star_color; ?>><?php echo $count; ?></a>
												</td>

												<td class="rating-count">
													<a href="<?php echo $url; ?>#comments" <?php echo $star_color; ?>></a>
												</td>
											</tr>
										<?php endforeach; ?>            
		            	</tbody>
	          		</table>
	          	</div>
	        	</div>
      		</div>
					<!--Tabs: Start -->
					<aside class="tabs-wrap">
						<div class="page-wrap">
							<div class="tabs-content">
							
							<?php woocommerce_print_reviews( $id , $product_title , $no_of_reviews); ?> 
								
							</div>
						</div>
					</aside>
					<!--Tabs: Start -->				
	    	</aside>
	  	</section>
	  	<!--Main Container: End --> 
		</section>
		<!--Wrapper: End --> 			
		<div style="clear:both"></div>
		<?php
			return ob_get_clean();
		}// end of post id is product or not
	}


	/**
	 * To give complete list of comments in ul tag, it ie printing the all data of li
	 *
	 * @since    	1.0.0
	 * @return 		string , mixed html string in $out_reviews
	 */
	function woocommerce_print_reviews( $id = "" , $title="" , $no_of_reviews=5 ) {
		$path = ABSPATH . 'wp-config.php';
		$path = str_replace ("\\", "/", $path);
		?>

    <script>

      $(document).ready(function($){   
        $('#sip-rswc-more-<?php echo $id ?>').click(function(){
            
          var get_last_post_display=$("[id*='li-comment-<?php echo $id ?>-']").last().attr('id'); //get ip last <li>
          var limit	=	$('ul.commentlist').attr('id'); //get ip last <li>
          var id 		= <?php echo $id ?>;
          var path 	= "<?php echo $path; ?>";
          var data 	= { 'last_id_post': get_last_post_display , 'limit': limit, 'id' : id, 'path' : path };

          $('#sip-rswc-more-<?php echo $id ?>').html('<img src="<?php echo SIP_RSWC_URL; ?>public/img/ajax-loader.gif" >');
          $.ajax({                
              type: "POST",
              url: "<?php echo SIP_RSWC_URL; ?>public/more-post.php",
              data: data, //send id ip last <li> to more_post.php
              cache: false,
              success: function(html){
                  $('ul.commentlist-<?php echo $id ?>').append(html);
                  $('#sip-rswc-more-<?php echo $id ?>').text('Load More'); //add text "Load More Post" to button again
                  if( html == "" ){
                  	$('#sip-rswc-more-<?php echo $id ?>').text('No more comments'); // when last record add text "No more posts to load" to button.
                  }
                  if (!html.trim()) {
									    // is empty or whitespace
									    $('#sip-rswc-more-<?php echo $id ?>').text('No more comments');
									    // $('#sip-rswc-more').remove();
									}
              }
          });
        });                 
      });
    </script>
				
		<?php

			$options = get_option( 'color_options' );
		  $star_color = ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
		  $load_more_button = ( $options['load_more_button'] != "" ) ? sanitize_text_field( $options['load_more_button'] ) : '';
			$load_more_text = ( $options['load_more_text'] != "" ) ? sanitize_text_field( $options['load_more_text'] ) : '';

			$review_body_text_color 	= ( $options['review_body_text_color'] != "" ) ? sanitize_text_field( $options['review_body_text_color'] ) : '';
			$review_background_color 	= ( $options['review_background_color'] != "" ) ? sanitize_text_field( $options['review_background_color'] ) : '';
			$review_title_color 			= ( $options['review_title_color'] != "" ) ? sanitize_text_field( $options['review_title_color'] ) : '';

			if( $star_color != "")
  			echo '<style type="text/css">.br-theme-fontawesome-stars .br-widget a.br-selected:after {color: '.$star_color.';}</style>';
  		$button = 'style="';
  		if( $load_more_button != "")
  			$button .= 'background-color:'. $load_more_button .';';
  		if( $load_more_text != "")
  			$button .= 'color:'. $load_more_text .';';
			$button .= '"';

		  if( $review_title_color != "")
  			$review_title_color = "style='color:". $review_title_color .";'";

			$review_background = 'style="';
  		if( $review_background_color != "")
  			$review_background .= 'background-color:'. $review_background_color .';';
  		if( $review_body_text_color != "")
  			$review_background .= 'color:'. $review_body_text_color .';';
			$review_background .= '"';
		
			global $wpdb, $post;
			$query 							= 	"SELECT c.* FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = {$id} AND p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0 AND c.comment_parent = 0 ORDER BY c.comment_date DESC limit {$no_of_reviews}";
			$comments_products 	= 	$wpdb->get_results($query, OBJECT);
			
			$out_reviews				= 	"";
			if ( $comments_products ) {
				foreach ( $comments_products as $comment_product ) {
					$id_ 						= 	$comment_product->comment_post_ID;
					$name_author 		= 	$comment_product->comment_author;
					$comment_id  		= 	$comment_product->comment_ID;
					$comment_parent = 	$comment_product->comment_parent;
					$comment_date  	= 	get_comment_date( 'M d, Y', $comment_id );
					$_product 			= 	get_product( $id_ );
					$rating 				=  	intval( get_comment_meta( $comment_id, 'rating', true ) );
					$rating_html 		= 	$_product->get_rating_html( $rating );
					$user_id	 			=		$comment_product->user_id;
					$votes 					=		"";
					$avatar 				=   "";
					$comment_chield	=		"";

					$args = array(
					    'status' 	=> 'approve', 
					    'number' 	=> '5',
					    'post_id' => $id_,
					    'parent' 	=> $comment_id

					);
					$comments 				= get_comments($args);
					$comments_length 	= count($comments);
					$iteration    		= -1;

					$comment_parent_id = $comment_id;

					do {

					if( $comment_parent  > 0 ){
						$comment_chield = " show-everthing-sub";
					}
					
					$out_reviews 	.= '<li itemprop="review" itemscope="" itemtype="http://schema.org/Review" id="li-comment-'.$id.'-'.$comment_parent_id.'" class="show-everthing ShowEve '.$comment_chield.'"> 
															<div class="comment-borderbox" '.$review_background.'> 
																<div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Product" style="display:none;">
																	<span itemprop="name">'.$title.'</span>
																</div>';

																if( $comment_parent == 0 ) {
																	$out_reviews .=	'<div class="br-wrapper br-theme-fontawesome-stars"><p class="sip-star-rating" style="display:none;">'.$rating.'</p><select class="rating-readonly-'.$rating.'"><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>';
																}
					$out_reviews 	.=		 '<p class="author" '.$review_title_color.' itemprop="author">
																	<strong>'.$name_author.'</strong> – <time itemprop="datePublished" datetime="'.$comment_date.'">'.$comment_date.'</time>
																</p>
																<div itemprop="description">
																	<p style="color:'.$review_body_text_color.'">'.nl2br( get_comment_text( $comment_id ) ).'</p>
																</div>
															</div>
											      </li>';											

					++$iteration;
					++$comment_parent;
					if( $comments_length > 0 ) {
						if( !empty($comments[$iteration]->comment_author) ) {
							$name_author 	= $comments[$iteration]->comment_author;
						}
						if( !empty( $comments[$iteration]->comment_ID ) ) {
							$comment_date = get_comment_date( 'M d, Y', $comments[$iteration]->comment_ID );	
						}
						if( !empty($comments[$iteration]->comment_ID )) {
							$comment_id 	= $comments[$iteration]->comment_ID;	
						}	
					}
				} while ( $comments_length > $iteration );
			}//end of lop
		} //end of if condition
		if ( $out_reviews != '' ) {
			$out_reviews  = '<ul id="'.$no_of_reviews.'" class="commentbox commentlist commentlist-'. $id .' commentlist_'. $id .'">' . $out_reviews . '</ul><button '. $button .' class="sip-rswc-more" id="sip-rswc-more-'. $id .'" type="button">Load More</button>';
		} else {
			$out_reviews = '<ul class="commentlist"><li><p class="commentbox content-comment">'. __('No products reviews.') . '</p></li></ul>';
		}
		echo $out_reviews;
	}
?>