
<?php if (count($cleaned_items) && !$bad_response) { ?>


<div class="feedly_ts" id="feedly_ts">

	<!-- Header -->
	<?php if ($title){ ?>
		<div class="feedly_ts_header"><?php echo $title ?></div>
	<? } ?>

	<!-- body -->
	<div style="
		font-family: <?php echo $font ?>; 
		color : <?php echo $color ?>;
		background-color : <?php echo $bgcolor ?>;" 
		class="feedly_ts_body <?php echo $fts_class ?>">
		
		<?php
			foreach ($cleaned_items as $item) {
				
				$visual = ($item['url']) ? "<a target='_blank' href='" . $item['post_link'] . "'><img src='" . $item['url'] . "'></a>" : '';

				echo "<div class='fts_item'>" . 
					"<div class='fts_item_visual'>" . $visual . "</div>" .
					"<div class='fts_item_content_wrapper'>" . 
						"<div class='fts_item_title'><a style='color: " . $color . ";' target='_blank' href='" . $item['post_link'] . "'>" . $item['title'] . "</a></div>" .
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

<?php } else { ?>

<div class="feedly_ts fts_only_btn" id="feedly_ts">
	<div class="feedly_ts_footer">	
		<a href="http://feedly.com" target="_blank" class="fts_logo"></a>
		<div class="follow_wrapper">
			<a href="<?php echo $feed_url ?>" target="_blank" class="fts_follow"><span>+</span>Follow</a>

			<?php if ($subscribers >= 0) { ?>
				<div class="fts_readers_count"><?php echo $subscribers ?></div>
			<?php } ?>
		</div>
	</div>
</div>


<?php } ?>