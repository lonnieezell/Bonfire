<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="ajax-form"'); ?>

	<input type="hidden" name="rte_type" value="<?php echo isset($page) ? $page->rte_type : '' ?>" />
	<input type="hidden" name="revision" value="<?php echo isset($page) ? $page->revision : 1 ?>" />

	<!-- Tab Area -->
	<div class="tabs">
		
		<ul>
			<li><a href="#content-tab">Content</a></li>
			<li><a href="#options-tab">Options</a></li>
		</ul>
		
		<!-- Page Content -->
		<div id="content-tab">
			<!-- Title -->
			<input type="text" name="page_title" class="big" value="<?php echo isset($page) ? $page->page_title : '' ?>" placeholder="Page Title..." />
			
			<div style="padding: 1em 0 0.25em 0">
				<p class="small"><b>Alias:</b> <?php echo site_url(); ?><span><?php echo isset($page) ? $page->alias : '' ?></span></p>
			</div>
			
			<textarea name="body" id="page_body" rows="18" style="width: 96%" placeholder="Content..."><?php echo isset($page) ? $page->body : ''; ?></textarea>
			
			<select name="rte_type">
				<option value="html" <?php echo (isset($page) && $page->rte_type == 'html') ? 'selected="selected"' : ''; ?>>HTML</option>
				<option value="markdown" <?php echo (isset($page) && $page->rte_type == 'markdown') ? 'selected="selected"' : ''; ?>>Markdown</option>
				<option value="textile" <?php echo (isset($page) && $page->rte_type == 'textile') ? 'selected="selected"' : ''; ?>>Textile</option>
			</select>			
		</div>
		
		
		<!-- Page Settings -->
		<div id="options-tab">
			<!-- Container? -->
			<div>
				<label>Container?</label>
				<input type="checkbox" name="is_folder" value="1" <?php echo isset($page) && $page->is_folder == '1' ? 'checked="checked"' : set_checkbox('is_folder', '1'); ?> />
			</div>
			<!-- Rich Text? -->
			<div>
				<label>Rich Text?</label>
				<input type="checkbox" name="rich_text" value="1" <?php echo (isset($page) && $page->rich_text == '1') || (!isset($page) && config_item('pages.default_rich_text') == '1') ? 'checked="checked"' : set_checkbox('rich_text', '1'); ?> />
			</div>
			
			<!-- Searchable? -->
			<div>
				<label>Searchable?</label>
				<input type="checkbox" name="searchable" value="1" <?php echo (isset($page) && $page->searchable == '1') || (!isset($page) && config_item('pages.default_searchable')) ? 'checked="checked"' : set_checkbox('searchable', '1'); ?> />
			</div>
			<!-- Cachable? -->
			<div>
				<label>Cacheable?</label>
				<input type="checkbox" name="cacheable" value="1" <?php echo (isset($page) && $page->cacheable == '1') || (!isset($page) && config_item('pages.default_cacheable')) ? 'checked="checked"' : set_checkbox('cacheable', '1'); ?> />
			</div>
			<!-- Deleted? -->
			<div>
				<label>Deleted?</label>
				<input type="checkbox" name="deleted" value="1" <?php echo isset($page) && $page->deleted == '1' ? 'checked="checked"' : set_checkbox('deleted', '1'); ?> />
			</div>
		
		
			<br/>
			<?php if (isset($page)) : ?>
			<div class="box delete rounded">
				<a class="button" id="delete-me" href="<?php echo site_url('admin/content/pages/delete/'. $page->page_id); ?>" onclick="return confirm('Are you sure you want to delete this page?')">Delete this Page</a>
				
				<h3>Delete this Page</h3>
				
				<p>Deleting this page is a permanent action and cannot be undone.</p>
			</div>
			<?php endif; ?>
		
		
		</div>
		
	</div>
	
	<div class="submits">
		<input type="submit" name="submit" value="Save Page" />
	</div>

<?php echo form_close(); ?>

<script>
	// Tabs
	$('.tabs').tabs();
	
	$('#page_body').markItUp(mySettings);
</script>