<h1><?php echo lang('docs_search_results') ?></h1>
<div class="well">
    <?php echo form_open(current_url(), 'class="form-inline"'); ?>
        <input type="text" name="search_terms" class="form-control" style="width: 85%" value="<?php echo set_value('search_terms', $search_terms) ?>" />
        <input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('docs_search'); ?>" />
    <?php echo form_close(); ?>
</div>
<p><?php echo isset($results) ? count($results) : 0; ?> results found in <?php echo $search_time; ?> seconds.</p>
<?php if (empty($results) || ! is_array($results)) : ?>
<div class="alert alert-info">
    <?php echo sprintf(lang('docs_no_results'), $search_terms); ?>
</div>
<?php
else :
    foreach ($results as $result) :
?>
<div class="search-result">
    <p class="result-header">
        <?php echo anchor(site_url($result['url']), $result['title']); ?>
    </p>
    <p class="result-url"><?php echo $result['url']; ?></p>
    <p class="result-excerpt">
        <?php echo $result['extract']; ?>
    </p>
</div>
<?php
    endforeach;
endif;
