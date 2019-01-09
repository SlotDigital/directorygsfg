<?php
/*
 * Get Tweets
*/
function SCS_get_tweets($count = -1, $user = 'LilyKingWedding') {
	
	global $twitter_api_settings;
	
	$cacheFile = SCS_LIB.'twitter/cache/twitter_response_cache.json';

	if (file_exists($cacheFile)) {
        $fh = fopen($cacheFile, 'r');
        $cacheTime = trim(fgets($fh));

        if ($cacheTime > strtotime('-5 minutes')) {
            return json_decode(fread($fh, filesize($cacheFile)));
        }

        fclose($fh);
        unlink($cacheFile);
    }
	
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name='.$user.'&count='.$count.'&exclude_replies=false&include_rts=true';
    $requestMethod = 'GET';
    
    $twitter = new TwitterAPIExchange($twitter_api_settings);
    $tweets = $twitter->setGetfield($getfield)
                      ->buildOauth($url, $requestMethod)
                      ->performRequest();
 
    $fh = fopen($cacheFile, 'w');
    fwrite($fh, time() . "\n");
    fwrite($fh, $tweets);
    fclose($fh); 
      
    $tweets = json_decode($tweets);	
   	
    return $tweets;	
    
}

/*
 * Convert URLs and Hashtags to links etc from Twitter API response..
*/
function twitterify($ret) {
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
	$ret = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
	return $ret;
}

/*
 * Get Tweets END
*/
?>
<?php function get_twitter_slider() { ?>
<script>
jQuery(document).ready(function($){
$('.twitter-slides').bxSlider({
  auto: true,
  captions: true,
  speed: 1000,
  pause: 5000,
  controls: true,
  pager: false,
  mode: 'fade',
  responsive: true,
  touchEnabled: false,
  useCSS: false,
  nextSelector: '#slider-next-T',
  prevSelector: '#slider-prev-T',
  nextText: '<i class="fa fa-angle-right"></i>',
  prevText: '<i class="fa fa-angle-left"></i>',
  });
});
</script>
<section class="twitter-slider">
	<?php $tweets = SCS_get_tweets();
		 ?>
		<div class="tweet-header">
			<i class="fa fa-twitter"></i>
		</div>
	    <ul class="tweets-list list-unstyled twitter-slides">
	        <?php foreach($tweets as $tweet):  ?>
	        	<li>
	        		<div class="tweet">
		        		<?php echo twitterify($tweet->text); ?>
	        		</div>
	        		<time>
<?php echo relativeTime(strtotime($tweet->created_at, 2)); ?></time>
				</li>
			<?php endforeach; ?>
		</ul>
</section>

<?php } ?>