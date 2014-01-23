<div class="row">
    <div class="category view">
    	<h2 class="importResults"><?php echo count($savedArticles); ?> articles imported (<?php echo count($unsavedArticles); ?> already imported, total <?php echo count($savedArticles)+count($unsavedArticles); ?>)</h2>
    	
    	<div class="accordion" id="accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Debug processed articles
                    </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse">
                    <div id="importDetails" class="accordion-inner">
                    	<h4>Imported</h4>
                        <pre><?php print_r($savedArticles); ?></pre>
                        <h4>Ignored</h4>
                        <pre><?php print_r($unsavedArticles); ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="actions">
        <h3><?php echo __('Actions'); ?></h3>
        <ul class="nav nav-pills nav-stacked">
        	<li><?php echo $this->Html->link(__('View Category'), array('action' => 'view', $category['Category']['id'])); ?> </li>
            <li><?php echo $this->Html->link(__('Edit Category'), array('action' => 'edit', $category['Category']['id'])); ?> </li>
            <li><?php echo $this->Form->postLink(__('Delete Category'), array('action' => 'delete', $category['Category']['id']), null, __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?> </li>
            <li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?> </li>
            <li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?> </li>
        </ul>
    </div>

    </div>
</div>