<?php

function slugify($string) {
  //Lower case everything
  $string = strtolower($string);
  //Make alphanumeric (removes all other characters)
  $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
  //Clean up multiple dashes or whitespaces
  $string = preg_replace("/[\s-]+/", " ", $string);
  //Convert whitespaces and underscore to dash
  $string = preg_replace("/[\s_]/", "-", $string);
  return $string;
}


function format_phone($number) {
  $area = substr($number, 0, 3);
  $first = substr($number, 3, 3);
  $last = substr($number, 6, 4);
  return array(
    'href' => $area  . $first . $last,
    'display' => $area . '-' . $first . '-' . $last
  );
}

function rgba($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	//Return default if no color provided
	if(empty($color))
          return $default; 
 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity))
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}

function better_format_phone( $phone, $html = true, $extension_prefix = 'ext. ', $display_format = '%1$d (%2$d) %3$d-%4$d', $hide_us_code = true ) {
	// Regex pattern to split parts of a phone number, and optional extension.
	// Supports some country codes, but not all international phone number formats.
	// https://regex101.com/r/4coVfU/1
	$pattern = '/^\+?([0-9]{0,3})?[^0-9]*([0-9]{3,3})[^0-9]*([0-9]{3,3})[^0-9]*([0-9]{4,4})[^0-9]*([^0-9]*(-|e?xt?\.?)[^0-9\-]*([0-9\-]{1,}))?[^0-9]*$/i';
	
	$found = preg_match($pattern, $phone, $matches);
	
	// If the phone number could not be parsed, keep the original format
	if ( ! $found ) return $phone;
	
	// Result from regex match with input: "+1 (541) 123-4567 x999"
	// [1] => 1
	// [2] => 541
	// [3] => 123
	// [4] => 4567
	// [5] => (ignore)
	// [6] => (ignore)
	// [7] => 999
	
	$country_code = $matches[1] ?: 1;
	$extension = $matches[7] ?? '';
	
	// Should US country code be hidden?
	if ( $hide_us_code && $country_code == '1' ) {
		$display_format = str_replace( '%1$d', '', $display_format );
		$display_format = trim( $display_format );
	}
	
	$output = '';
	
	if ( $html ) {
		$output .= '<span class="tel">';
	}
	
	// Start html link
        $tel_href;
	if ( $html ) {
		$tel_href = sprintf('tel:+%d%d%d%d', $country_code, $matches[2], $matches[3], $matches[4]);
		$output .= '<a href="'. esc_attr($tel_href) .'" class="tel-link">';
	}
	
	// The actual phone number to display
        $display = sprintf('%d (%d) %d-%d', $country_code, $matches[2], $matches[3], $matches[4]);
	$output .= $display;
	
	// End html link
	if ( $html ) {
		$output .= '</a>';
	}
	
	// Add the extension
	// Links do not (reliably) support an extension, so it appears after the link.
	if ( $extension ) {
		if ( $html ) {
			$output .= '<span class="ext">';
			$output .=   '<span class="ext-prefix">'. esc_html($extension_prefix) .'</span>';
			$output .=   '<span class="ext-value">'. esc_html($extension) .'</span>';
			$output .= '</span>';
		}else{
			$output .= $extension_prefix . $extension;
		}
	}
	
	if ( $html ) {
		$output .= '</span>';
	}
	
        return array(
                'display' => $display,
                'href' => $tel_href
        );

}