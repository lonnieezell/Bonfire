<div class="v-split">
	<!-- List -->
	<div class="vertical-panel">
		
		<div class="panel-header">
			<!-- Search Form -->
			<input type="search" id="page-search" value="" placeholder="search..." style="display: inline; width: 50%;" />
			
			<select id="page-filter" style="display: inline; max-width: 40%;">
				<option value="0">Show...</option>
				<option value="published">Published Only</option>
				<option value="draft">Drafts Only</option>
			</select>
		</div>
	
	
		<?php if (isset($pages) && is_array($pages)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="page-list">
			<?php foreach ($pages as $page) : ?>
				<div class="list-item" data-id="<?php echo $page->page_id ?>" data-status="<?php echo $page->published ? 'published' : 'draft' ?>">
					<img src="<?php echo Template::theme_url('images/page.png') ?>" />
				
					<p>
						<b><?php echo $page->page_title ?></b><br/>
						<span class="small"><?php echo word_limiter($page->body, 7) ?></span>
					</p>
				</div>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>
		
		<?php else : ?>
		
			<div class="notification information">
				<p>No pages found.</p>
			</div>
		
		<?php endif; ?>
	
	</div>	<!-- /vertical-panel -->
	
	<!-- Editor -->
	<div id="content">
		<div class="scrollable" id="ajax-content">
			<div class="inner">
			
				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url('admin/settings/pages/create'); ?>">Create New Page</a>
				
					<h3>Create A New Page</h3>
					
					<p>Pages are powerful, flexible, wiki-like. You know you want another.</p>
				</div>
			
			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->