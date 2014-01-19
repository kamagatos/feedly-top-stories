<?php
/*
Plugin Name: Feedly Top Stories
Plugin URI: 
Description: 
Version: 1.0
Author: Mohamed Kamagate
Author URI: http://kamagatos.com
License: 
*/

class FTSWidget extends WP_Widget {
	
	//Register the widget
	function FTSWidget() {
		$widget_ops = array( 'description' => 'Display Feedly Top Stories' );
		parent::WP_Widget( 'feedlywidget', $name = 'Feedly top stories',  $widget_ops);
	}

	// Frontend
	function widget( $args, $instance ) {
		
		global $sfplugin;
		
		// extract user options
		extract( $args );
		extract( $instance );

		//Set the url
		$url = 'feed/' . $url;

		//Set the count
		if ($nbposts <= 0)
			$nbposts = 1;
		else if ($nbposts >= 20)
			$nbposts = 20;

		//Bad response
		$bad_response = false;

		//Get the data
		$json1 = file_get_contents('http://cloud.feedly.com/v3/feeds/' . urlencode($url));
		$json = file_get_contents('http://cloud.feedly.com/v3/mixes/' . urlencode($url) . '/contents?count=' . $nbposts);

		if ($json && $json1)
		{
			$obj1 = json_decode($json1);
			$subscribers = subscribers_count($obj1->subscribers);

			$obj = json_decode($json);

		    $items = $obj->items;
		    $cleaned_items = Array();
		    $fts_class = 'fts_non_visual';
		    $feed_url = 'http://feedly.com/#subscription/' . urlencode($url);
		    
		    foreach($items as $i => $item)
		    {
		      	$cleaned_items[$i]['title'] = $item->title;
		      	$cleaned_items[$i]['post_link'] = $item->originId;
		      	$cleaned_items[$i]['author'] = $item->author;
		      	$cleaned_items[$i]['engagement'] = $item->engagement;
		      	$cleaned_items[$i]['published'] = timeago($item->published/1000);
				$visual = $item->visual;
	      		if ($visual->url)
	      		{
	      			$fts_class = '';
	      			$cleaned_items[$i]['is_visual'] = true;
	      			$cleaned_items[$i]['url'] = $visual->url;
	      		}
		    }
		}
		else
		{
			$bad_response = true;
			$subscribers = -1;
		}

		// include view
		include( $sfplugin->pluginPath . 'view.php' );
	}	

	//Sanitize widget form values as they are saved
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['url'] = strip_tags( $new_instance['url'] );
		$instance['nbposts'] = strip_tags( $new_instance['nbposts'] );
		$instance['font'] = strip_tags( $new_instance['font'] );
		$instance['bgcolor'] = strip_tags( $new_instance['bgcolor'] );
		$instance['color'] = strip_tags( $new_instance['color'] );
		
		return $instance;
	}

	//Back-end form
	function form( $instance ) {
		
		extract( array_merge( array(
			'Title'			=> 'Feedly top stories',
			'Feed ID'			=> 'feed/http://blog.feedly.com/feed/',
			'Widget font'			=> 'helvetica',
			'Number of posts to show'			=> '3',
			'Background color'			=> '#ffffff',
			'Text color'			=> '#323232',
		), $instance ) ); ?>
			
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Feed ID:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('font'); ?>"><?php _e('Font :'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('font'); ?>" name="<?php echo $this->get_field_name('font'); ?>" type="text" value="<?php echo $font; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color of the text:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo $color; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('bgcolor'); ?>"><?php _e('Background color:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcolor'); ?>" name="<?php echo $this->get_field_name('bgcolor'); ?>" type="text" value="<?php echo $bgcolor; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nbposts'); ?>"><?php _e('Number of posts to show:'); ?></label>
			<input size="6" id="<?php echo $this->get_field_id('nbposts'); ?>" name="<?php echo $this->get_field_name('nbposts'); ?>" type="text" value="<?php echo $nbposts; ?>" />
		</p>
	<?php 
	}	
}

// Have to clean those functions later on (public static)
function timeago($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
        return '2 seconds';

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}

function subscribers_count($count){
	if ($count <= 0)
		return 'No readers';
	else if ($count == 1)
		return '1 reader';
	else
	{
		if ($count > 999 && $count <= 999999) 
		    $result = floor($count / 1000) . 'K';
		elseif ($count > 999999)
		    $result = floor($count / 1000000) . 'M';
		else
		    $result = $count;
		return $result . ' readers';
	}
}

?>