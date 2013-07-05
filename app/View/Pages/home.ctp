<?php echo $this->Html->css('tablesorter'); ?>
<?php echo $this->Html->script('jquery.inview'); ?>
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
				<input class="refreshButton btn btn btn-primary" id="btn_<?php echo $category['Category']['id']; ?>" type="button" value="refresh feed">
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
								//widgets: [ 'scroller' ],
								widgetOptions : {
									scroller_height : 300,
									scroller_jumpToHeader: true,
									scroller_idPrefix : 's_'
								}
							});
						},
						error: function(errordata){
							console.log(errordata);
							$("#response_div_<?php echo $category['Category']['id']; ?>").html('<div class="alert alert-error">An error ('+errordata.status+' - '+errordata.statusText+') occurred loading feed:</div><div class="well well-small">'+errordata.responseText+'</div>');
						}
					});
				}
				$("#btn_<?php echo $category['Category']['id']; ?>").click(function() {
					fnc_<?php echo $category['Category']['id']; ?>();
				});

				$('#response_div_<?php echo $category['Category']['id']; ?>').one('inview', function (event, visible) {
    				if (visible) {
      					fnc_<?php echo $category['Category']['id']; ?>();
    				}
				});
				
				</script>
			</div>
			
		<?php endif; ?>
	
	<?php endif; ?>

<?php endforeach; ?>

</div>

<div class="clearfix"></div>

<script>
	$('.feed_table_container').on('click', '.import-article', function() {
		var thisRow = $(this).parents('tr');
		$.ajax({
			url: $(this).parent('form').attr('action'),
			data: $(this).parent('form').serialize(),
			type: 'POST',
			success: function(response){
				var result = $(response);
				$('.modal-body').html(result.html());
				$('#modal').modal('show');
				$('.modal-body .submit input').click(function(e) {
					e.preventDefault();
					$.ajax({
						url: $(".modal-body").find('form').attr('action'),
						data: $(".modal-body").find('form').serialize(),
						type: 'POST',
						success: function(){
							$('.modal-body').html('<div class="alert alert-success">Article inserted</div>');
							thisRow.addClass('articleInserted');
							setTimeout(function(){
								$('#modal').modal('hide');
							}, 1000);
						},
						error: function(){
							$('.modal-body').append('<div class="alert alert-error">Error: article not inserted</div>');
						}
					});
				});
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
