<?php echo $this->load->view('content/sub_nav', null, true); ?>

<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string()); ?>

	<!-- Tab Area -->
	<div class="tabs">
		
		<ul>
			<li><a href="#content-tab">Page Content</a></li>
			<li><a href="#setting-tab">Options</a></li>
		</ul>
		
		<!-- Page Content -->
		<div id="content-tab">
			
			<!-- ID -->
			<?php if (isset($page)) : ?>
			
			<?php endif; ?>
			
			<!-- Title -->
			<input type="text" name="page_title" id="page_title" class="big" style="width: 80%; display: inline-block;" placeholder="Enter Title here" value="<?php echo isset($page) ? $page->page_title : set_value('page_title') ?>" />
			
			<input type="checkbox" name="published" value="1" style="margin-left: 2em" /> Published?<br/>
			
			<label style="width: auto">Page Alias:</label> <?php echo site_url(); ?><span id="alias-span"><?php echo isset($page) ? $page->alias : set_value('alias') ?></span>
			<input type="text" name="page_alias" id="alias-input" style="display: ; width: 25%;" value="<?php echo isset($page) ? $page->alias : set_value('alias') ?>" />
			
			<a href="#" id="details-toggle" class="align-right">Show Details</a>
			
			<br />
			
			<div id="page-info" style="display: none">
				<!-- Long Title -->
				<div>
					<label>Long Title</label>
					<input type="text" name="long_title" value="<?php echo isset($page) ? $page->long_title : set_value('long_title') ?>" />
				</div>
				<!-- Description -->
				<div>
					<label>Description</label>
					<input type="text" name="description" value="<?php echo isset($page) ? $page->description : set_value('description') ?>" />
				</div>
				<!-- Alias -->
				<div>
					<label>Page Alias</label>
					<input type="text" name="alias" id="alias" value="<?php echo isset($page) ? $page->alias : set_value('alias') ?>" />
				</div>
				<!-- Summary -->
				<div>
					<label>Summary</label>
					<textarea name="summary" rows="3"><?php echo isset($page) ? $page->summary : set_value('summary') ?></textarea>
				</div>
				<!-- Parent -->
				<div>
					<label>Parent Page</label>
					<select name="parent_id">
						<option value="0">None</option>
					</select>
				</div>
			
			</div>	<!-- /page-info -->
			
			<textarea name="body" id="body" class="horizontal" rows="20"><?php echo isset($page) ? $page->body : set_value('body') ; ?></textarea>
			
		</div>
		
		
		<!-- Page Settings -->
		<div id="setting-tab">
			<!-- Container? -->
			<div>
				<label>Container?</label>
				<input type="checkbox" name="is_folder" value="1" <?php echo isset($page) && $page->is_folder == '1' ? 'checked="checked"' : set_checkbox('is_folder', '1'); ?> />
			</div>
			<!-- Rich Text? -->
			<div>
				<label>Rich Text?</label>
				<input type="checkbox" name="rich_text" value="1" <?php echo isset($page) && $page->rich_text == '1' ? 'checked="checked"' : set_checkbox('rich_text', '1'); ?> />
			</div>
			
			<!-- Searchable? -->
			<div>
				<label>Searchable?</label>
				<input type="checkbox" name="searchable" value="1" <?php echo isset($page) && $page->searchable == '1' ? 'checked="checked"' : set_checkbox('searchable', '1'); ?> />
			</div>
			<!-- Cachable? -->
			<div>
				<label>Cacheable?</label>
				<input type="checkbox" name="cacheable" value="1" <?php echo isset($page) && $page->cacheable == '1' ? 'checked="checked"' : set_checkbox('cacheable', '1'); ?> />
			</div>
			<!-- Deleted? -->
			<div>
				<label>Deleted?</label>
				<input type="checkbox" name="deleted" value="1" <?php echo isset($page) && $page->deleted == '1' ? 'checked="checked"' : set_checkbox('deleted', '1'); ?> />
			</div>
		</div>
	</div>
	
	<div class="submits">
		<input type="submit" name="submit" value="Save Page" />
	</div>

<?php echo form_close(); ?>

<script>
head.ready(function() {
	// Tabs
	$('.tabs').tabs();
	
	// Details toggle
	$('#details-toggle').click(function(){
		var status = $(this).text();
		
		if (status == 'Show Details')
		{
			$('#page-info').slideDown();
			$(this).text('Hide Details');
		}
		else
		{
			$('#page-info').slideUp();
			$(this).text('Show Details');
		}
	
		return false;
	});
});
</script>