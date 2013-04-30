<?php echo $this->Html->css('tablesorter'); ?>
<?php echo $this->Html->script('jquery.tablesorter.min'); ?>
<?php echo $this->Html->script('jquery.tablesorter.widgets.min'); ?>
<?php echo $this->Html->script('widget-scroller'); ?>

<div class="categories">

<?php foreach ($categories as $category): ?>
	
	<?php if(empty($category['ParentCategory']['name'])) : ?>
	<hr>
	<h1><?php echo __($category['Category']['name']); ?></h1>
	<?php else: ?>
		
		<?php if (!empty($category['Feed']['id'])): ?>
			
			<div class="feed_table_container">
				<h3><?php echo $category['Feed']['name']; ?></h3>
				<input id="btn_<?php echo $category['Category']['id']; ?>" type="button" value="refresh feed">
				<div id="response_div_<?php echo $category['Category']['id']; ?>"></div>
				<script>
				function fnc_<?php echo $category['Category']['id']; ?>(){
					$.ajax({ 
						url: "categories/getFeed",
						data: {
							feedUrl: "<?php echo $category['Feed']['url']; ?>",
							categoryID: <?php echo $category['Category']['id']; ?>
						},
						type: "POST",
						beforeSend: function(){
							$("#response_div_<?php echo $category['Category']['id']; ?>").html('<img style="margin:50px;" src="img/loading.gif" />');
						},
						success: function(response) {
							$("#response_div_<?php echo $category['Category']['id']; ?>").html(response);
							$(".tablefeed").tablesorter({
								showProcessing: true,
								widgets: [ 'scroller' ],
								widgetOptions : {
									scroller_height : 300,
									scroller_jumpToHeader: true,
									scroller_idPrefix : 's_'
								}
							});
						},
						error: function(errordata){
							$("#response_div_<?php echo $category['Category']['id']; ?>").html("An error occurred loading feed:<pre>"+errordata+"</pre>");
						}
					});
				}
				$("#btn_<?php echo $category['Category']['id']; ?>").click(function() {
					fnc_<?php echo $category['Category']['id']; ?>();
				});
				fnc_<?php echo $category['Category']['id']; ?>();
				
				</script>
			</div>
			
		<?php endif; ?>
	
	<?php endif; ?>

<?php endforeach; ?>

</div>

<div class="clearfix"></div>

<script>
	$('.feed_table_container').on('click', '.import-article', function() {
		$.ajax({
			url: $(this).parent('form').attr('action'),
			data: $(this).parent('form').serialize(),
			type: 'POST',
			success: function(response){
				var result = $(response);
				$('.modal-body').html(result.html());
				$('#modal').modal('show');
			}
		});
	});
</script>

<div id="modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Check data and insert article</h3>
	</div>
	<div class="modal-body"></div>
</div>
