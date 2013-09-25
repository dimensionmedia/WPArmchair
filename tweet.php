<?php

include '../../../wp-load.php';
include '../../../wp-admin/includes/file.php';
include '../../../wp-admin/includes/image.php';


/*
- A customizable, fast-loading and fault-proof tweet button.
- Built by Umut Muhaddisoglu (@umutm) of http://www.webresourcesdepot.com.
- Strict LTADSSIYND License (Listen To A Dire Straits Song If You Never Did)
*/
/* Requires Update - START */
$viaText		= '';
$bitlyLogin		= 'o_22qbels3p0';
$bitlyApiKey	= 'R_4e290cbfb6f5eb1b44cdf99b21b476d6';
/* Requires Update - END */


/* ******************************No Need To Update Below****************************** */


/* Getting Post Variables - START */
$postURL 		= urlencode($_GET['postURL']);
$postTitle 		= html_entity_decode(htmlspecialchars_decode($_GET['postTitle'], ENT_QUOTES));
/* Getting Post Variables - END */


/* Bit.ly Shorten Function - START */
function getBitlyURL($theURL,$theBitlyLogin,$theBitlyApiKey) {

$response = wp_remote_get( 'http://api.bit.ly/v3/shorten?login=' . $theBitlyLogin . '&apiKey=' . $theBitlyApiKey . '&longUrl=' . $theURL . '&format=txt' );

if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
							
	return $response;

}
			
}
/* Bit.ly Shorten Function - END */


/* TinyURL Shorten Function - START */
function getTinyURL($theURL) {
	return file_get_contents('http://tinyurl.com/api-create.php?url=' . $theURL);
}
/* TinyURL Shorten Function - END */


/* Shorten URL - START */
/* $shortenedURL = getBitlyURL($postURL,$bitlyLogin,$bitlyApiKey);
if (strrpos($shortenedURL, "bit.ly") == false) {
	$shortenedURL = getTinyURL($postURL);
	if (strrpos($shortenedURL, "tinyurl") == false) {
		$shortenedURL = $postURL;
	}
} */
/* Shorten URL - END */


/* Prepare Tweet - START */
$tweet = urlencode($postTitle) . urlencode('') . $shortenedURL . $viaText;
$tweetLength = strlen($postTitle . '' . $shortenedURL . $viaText);
$postTitleLength = strlen($postTitle);
$restLength = strlen('' . $shortenedURL . $viaText);
$dotsMargin = 4; 

if ($tweetLength > 140) {
	$tweet = urlencode(substr($postTitle,0,140 - $restLength - $dotsMargin)) . urlencode('.. - ') . $shortenedURL . $viaText;
}
/* Prepare Tweet - END */


/* Redirect To Twitter - START */
$link = 'http://twitter.com/intent/tweet?text=' . $tweet;
wp_redirect ($link);
die();
/* Redirect To Twitter - END */
?>