<div class="row">
    <div class="category view">
    	<h2>Import all Articles</h2>


        <script type="text/javascript">var categories = [];

        <?php foreach ($categories as $category) {
            $category['Category']['parentname'] = $category['ParentCategory']['name'];
            echo 'categories.push('.json_encode($category['Category']).');';
        }?>

        </script>
        <p>Processing <?php echo count($categories); ?> categories.</p>

        <div class="progress progress-striped active">
            <div class="bar" style="width: 2%;"></div>
        </div>
    	
    	<div class="accordion" id="accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                </div>
            </div>
        </div>
    </div>
    
    <div class="actions">
        <h3><?php echo __('Actions'); ?></h3>
        <ul class="nav nav-pills nav-stacked">
            <li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?> </li>
            <li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?> </li>
        </ul>
    </div>

    </div>
</div>

<script>
    $( document ).ready(function() {

        $(categories).each(function(i, category){
            var percent = (100/categories.length)*(i+1);
            
            $.ajax({
                url: 'importCategoryArticles/'+category.id,
                type: 'POST',
                async: false,
                success: function(response){

                    $('.progress .bar').attr('style', 'width: '+percent+'%')
                    var result = $(response);
                    $('.accordion-heading').append('<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#cat'+category.id+'">['+category.parentname+'] '+category.name+' &mdash; '+$(response).find('.importResults').html()+'</a>');
                    $('.accordion-group').append('<div id="cat'+category.id+'" class="accordion-body collapse"><div class="accordion-inner">'+$(result).find('#importDetails').html()+'</div></div>')
                }
            });
        });

        $('.progress.progress-striped.active').removeClass('active');
    });
</script>