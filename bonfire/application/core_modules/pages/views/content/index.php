<?php echo $this->load->view('content/sub_nav', null, true); ?>

<?php if (isset($pages) && is_array($pages) && count($pages)) :?>

	<table cellspacing="0">
		<thead>
			<tr>
				<th>Name</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($pages as $page) : ?>
			<tr>
				<td><?php echo anchor('admin/content/pages/edit/'. $page->page_id, $page->page_title); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

<?php else : ?>
<div class="notification information">
	<p>No pages found.</p>
</div>
<?php endif; ?>