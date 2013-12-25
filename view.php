<script></script>
<div class="feedly_ts">

	<!-- Header -->
	<div class="feedly_ts_header"><?php echo $title ?></div>

	<!-- body -->
	<div class="feedly_ts_body <?php echo $fts_class ?>">
		
		<?php
			foreach ($cleaned_items as $item) {
				echo "<div class='fts_item'>" . 
					"<div class='fts_item_visual'><a target='_blank' href='" . $item['post_link'] . "'><img src='" . $item['url'] . "''></a></div>" .
					"<div class='fts_item_content_wrapper'>" . 
						"<div class='fts_item_title'><a target='_blank' href='" . $item['post_link'] . "'>" . $item['title'] . "</a></div>" .
						"<div class='fts_item_info'><span class='fts_item_engagement'>" . $item['engagement'] . " </span> " . $item['author'] . ", " . $item['published'] . "</div>" .
					"</div>" .
				"</div>";
			}
		 ?>

	</div>

	<!-- Footer -->
	<div class="feedly_ts_footer">	
		<a href="http://feedly.com" target="_blank" class="fts_logo"></a>
		<div class="follow_wrapper">
			<a href="<?php echo $feed_url ?>" target="_blank" class="fts_follow"><span>+</span>Follow</a>
			<div class="fts_readers_count"><?php echo $subscribers ?></div>
		</div>
	</div>
</div>