<?php echo $this->Html->css('tablesorter'); ?>
<?php echo $this->Html->script('jquery.tablesorter.min'); ?>

<script>
jQuery(document).ready(function($){
    $("#rssitems").tablesorter();
} 
);
</script>

<div class="row">
    <div class="categories view">
        <h2>Category "<?php echo h($category['Category']['name']); ?>"</h2>
        <dl>
        <!--
            <dt><?php echo __('Id'); ?></dt>
            <dd>
                <?php echo h($category['Category']['id']); ?>
                &nbsp;
            </dd>
        -->
            <dt><?php echo __('App Bg Color'); ?></dt>
            <dd>
                <?php echo h($category['Category']['color']); ?>
                &nbsp;
            </dd>

            <dt><?php echo __('Auto import Articles'); ?></dt>
            <dd>
                <?php echo $category['Category']['auto_import'] ? 'true' : 'false'; ?>
                &nbsp;
            </dd>


            <?php if (!empty($category['ParentCategory']['name'])): ?>
            <dt><?php echo __('Parent Category'); ?></dt>
            <dd>
                <?php echo $this->Html->link($category['ParentCategory']['name'], array('controller' => 'categories', 'action' => 'view', $category['ParentCategory']['id'])); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Slug'); ?></dt>
            <dd>
                <?php echo h($category['Category']['slug']); ?>
                &nbsp;
            </dd>
            <?php endif; ?>
            <?php if (!empty($category['Feed']['id'])): ?>
            <dt><?php echo __('Feed'); ?></dt>
            <dd>
                <?php echo $this->Html->link($category['Feed']['name'], array('controller' => 'feeds', 'action' => 'view', $category['Feed']['id'])); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Feed Url'); ?></dt>
            <dd>
                <?php echo $category['Feed']['url']; ?>
                &nbsp;
            </dd>
            <?php endif; ?>
        </dl>
    </div>
    <div class="actions">
        <h3><?php echo __('Actions'); ?></h3>
        <ul class="nav nav-pills nav-stacked">
            <li><?php echo $this->Html->link(__('Edit Category'), array('action' => 'edit', $category['Category']['id'])); ?> </li>
            <li><?php echo $this->Html->link(__('Import all Articles'), array('action' => 'importCategoryArticles', $category['Category']['id']), array(), 'Vuoi davvero importare tutti gli Articoli dal feeed di questa categoria?'); ?> </li>
            <li><?php echo $this->Form->postLink(__('Delete Category'), array('action' => 'delete', $category['Category']['id']), null, __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?> </li>
            <li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?> </li>
            <li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?> </li>
        </ul>
    </div>

<?php if (!empty($category['Feed']['id'])):

        $xmlFeed = Xml::build($category['Feed']['url']);
        $feedData = Set::reverse($xmlFeed);

        //IS RSS OR ATOM?
        $channel = isset($feedData['rss']) ? $feedData['rss']['channel']['item'] : $feedData['feed']['entry'];

        $this->Paginator->options(array(
            'update' => '#content',
            'evalScripts' => true
        ));
?>
    <div class="related">
        <h3><?php echo __('Related Feed "'.$category['Feed']['name'].'"'); ?></h3>
        
        <h4>Parsed Articles</h4>
        <table id="rssitems" class="tablesorter table table-condensed table-hover" cellpadding = "0" cellspacing = "0">
            <thead>
                <tr>
                    <th><?php echo __('Title'); ?></th>
                    <th><?php echo __('Description'); ?></th>
                    <th><?php echo __('Pub Date'); ?></th>
                    <th><?php echo __('Images'); ?></th>
                    <th><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 0;
            
                foreach ($channel as $itemField):

                    $itemTitle = $itemField['title'];
                    //$itemLink = is_array($itemField['link']) ? $itemField['link']['@'] : $itemField['link'];
                    $itemLink = $this->RssStalker->findLink($itemField['link'], null);
                    $imageURL = $this->RssStalker->findImages($itemField, null);

                    if(isset($feedData['rss'])){
                        //RSS structure
                        $dateOk = isset($itemField['pubDate']) ? true : false;
                        $pubDate = isset($itemField['pubDate']) ? date('Y-m-d H:i:s', strtotime($itemField['pubDate'])) : date('Y-m-d H:i:s');
                        $itemDesc = isset($itemField['description']) ? html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, '') : '<span class="label label-important">Descrizione mancante</span>';

                    } else {
                        //ATOM STRUCTURE
                        $dateOk = isset($itemField['updated']) ? true : false;
                        $pubDate = isset($itemField['updated']) ? date('Y-m-d H:i:s', strtotime($itemField['updated'])) : date('Y-m-d H:i:s');
                        $itemDesc = isset($itemField['summary']) ? html_entity_decode(strip_tags($itemField['summary']['@']), ENT_QUOTES, '') : '<span class="label label-important">Descrizione mancante</span>';

                    }
                    
                    $inserted = false;
                    foreach($articles as $article){
                        if($article['Article']['link']==$itemLink)
                            $inserted = true;
                    }

            ?>
                <tr <?php echo $inserted!=false ? 'class="articleInserted"' : ''; ?> >
                    <td><?php echo $itemTitle; ?></td>
                    <td><?php echo $itemDesc; ?></td>
                    <td><?php echo $dateOk ? $pubDate : '<span class="label label-important">Data mancante</span><br/>'.$pubDate; ?></td>
                    <td><?php echo '<img width="100" src="'.$imageURL.'"/>'; ?></td>
                    <td>
                        <?php echo $this->Html->link(__('View'), $itemLink, array('target' => '_blank', 'class' => 'btn btn-small')); ?>
                        <?php echo $this->Form->create('Article', array('id' => 'importArticle', 'type' => 'post', 'url' => array('controller' => 'articles', 'action' => 'add')));
                        echo $this->Form->hidden('Article.title', array('default' => $itemTitle));
                        echo $this->Form->hidden('Article.link', array('default' => $itemLink));
                        echo $this->Form->hidden('Article.description', array('default' => $itemDesc));
                        echo $this->Form->hidden('Article.pubDate', array('default' => $pubDate));
                        echo $this->Form->hidden('Article.image', array('default' => $imageURL));
                        echo $this->Form->hidden('Article.category_id', array('default' => $category['Category']['id']));
                        echo $this->Form->hidden('Article.confirm', array('default' => 'toConfirm'));

                        echo $this->Form->submit(__('Import'), array('class' => 'btn btn-small'));
                        echo $this->Form->end(); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Debug Feed XML</h3>
        <div class="accordion" id="accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Debug feed XML structure
                    </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <pre><?php print_r($feedData); ?></pre>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

<?php endif; ?>


<?php if (!empty($category['User'])): ?>

    <div class="related">
        <h3><?php echo __('Authorized Users'); ?></h3>

        <table cellpadding = "0" cellspacing = "0">
        <tr>
            <th><?php echo __('Username'); ?></th>
            <th><?php echo __('Role'); ?></th>
        </tr>
        <?php
            $i = 0;
            foreach ($category['User'] as $user): ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

<?php endif; ?>

<?php if (!empty($category['ChildCategory'])): ?>

    <div class="related">
        <h3><?php echo __('Child Categories'); ?></h3>
        <table cellpadding = "0" cellspacing = "0">
        <tr>
            <th><?php echo __('Name'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php
            $i = 0;
            foreach ($category['ChildCategory'] as $childCategory): ?>
            <tr>
                <td><?php echo $childCategory['name']; ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('controller' => 'categories', 'action' => 'view', $childCategory['id']), array('class' => 'btn btn-small')); ?>
                    <?php echo $this->Html->link(__('Edit'), array('controller' => 'categories', 'action' => 'edit', $childCategory['id']), array('class' => 'btn btn-small')); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'categories', 'action' => 'delete', $childCategory['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $childCategory['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

<?php endif; ?>

</div>