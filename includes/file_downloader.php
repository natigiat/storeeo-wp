<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This function uploads from a URL.
* To upload from a local file path instead
* @see: https://gist.github.com/RadGH/3b544c827193927d1772
*
* Example usage: Upload photo from URL, display the attachment as as html <img>
*   $attachment_id = storeeo_upload_from_url( "http://example.com/images/photo.png" );
*   echo wp_get_attachment_image( $attachment_id, 'large' );
*/

/**
 * Upload a file to the media library using a URL.
 * 
 * @version 1.2
 *
 * @param string $url         URL to be uploaded
 * @param null|string $title  If set, used as the post_title
 *
 * @return int|false
 */
function storeeo_upload_from_url( $url, $title = null ) {
 

    require_once( dirname( __FILE__ ) . '/../../../../wp-load.php');
	require_once( dirname( __FILE__ ) . '/../../../../wp-admin/includes/image.php');
	require_once( dirname( __FILE__ ) . '/../../../../wp-admin/includes/file.php');
	require_once( dirname( __FILE__ ) . '/../../../../wp-admin/includes/media.php');
	
	// Download url to a temp file
	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) return false;
	
    
	// Get the filename and extension ("photo.png" => "photo", "png")
	$filename = pathinfo($url, PATHINFO_FILENAME);
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	
	// An extension is required or else WordPress will reject the upload
	if ( ! $extension ) {
		// Look up mime type, example: "/photo.png" -> "image/png"
		$mime = mime_content_type( $tmp );
		$mime = is_string($mime) ? sanitize_mime_type( $mime ) : false;
		
		// Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
		$mime_extensions = array(
			// mime_type         => extension (no period)
			'text/plain'         => 'txt',
			'text/csv'           => 'csv',
			'application/msword' => 'doc',
			'image/jpg'          => 'jpg',
			'image/jpeg'         => 'jpeg',
			'image/gif'          => 'gif',
			'image/png'          => 'png',
			'video/mp4'          => 'mp4',
		);
		
		if ( isset( $mime_extensions[$mime] ) ) {
			// Use the mapped extension
			$extension = $mime_extensions[$mime];
		}else{
			// Could not identify extension
			@unlink($tmp);
			return false;
		}
	}
	
	
	
	// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
	$args = array(
		'name' => "$filename.$extension",
		'tmp_name' => $tmp,
	);
	
	// Do the upload
	$attachment_id = media_handle_sideload( $args, 0, $title);
	
	// Cleanup temp file
	@unlink($tmp);
	
	// Error uploading
	if ( is_wp_error($attachment_id) ) return false;
	
	// Success, return attachment ID (int)
	return (int) $attachment_id;
}