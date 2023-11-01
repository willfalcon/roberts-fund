<?php 

if ( ! function_exists('write_log')) {
  function write_log ( $log )  {
		if (empty($log)) {
			error_log('');
		} else if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log);
		}
  }
}

function rip_tags($string) { 
    
    // ----- remove HTML TAGs ----- 
    $string = preg_replace ('/<[^>]*>/', ' ', $string); 
    
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
    
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    
    return $string; 

}

if ( ! function_exists('pp')) {
function pp( $obj, $label = '' ) {
	$data = json_encode( print_r( rip_tags($obj),true ) );
	?>
	<style type="text/css">
		#bsdLogger {
		position: absolute;
		top: 30px;
		right: 0px;
		border-left: 4px solid #bbb;
		padding: 6px;
		background: white;
		color: #444;
		z-index: 999;
		font-size: 1.25em;
		width: 600px;
		height: 800px;
		overflow: scroll;
		}
	</style>
	<script type="text/javascript">
		var doStuff = function(){
			var obj = <?php echo ($data); ?>;
			
			var logger = document.getElementById('bsdLogger');
			if (!logger) {
				logger = document.createElement('div');
				logger.id = 'bsdLogger';
				document.body.appendChild(logger);
			}
			
			var pre = document.createElement('pre');
			var h2 = document.createElement('h2');
			pre.innerHTML = obj;
			h2.innerHTML = '<?php echo addslashes($label); ?>';
			logger.appendChild(h2);
			logger.appendChild(pre);
		};
		window.addEventListener ("DOMContentLoaded", doStuff, false);
	</script>
	<?php
}
}

function ea_display_post_blocks() {
	global $post;
	pp( esc_html( $post->post_content ) );
}
//add_action( 'wp_footer', 'ea_display_post_blocks' );



