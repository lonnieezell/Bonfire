<h1><?php echo lang('docs_search_results') ?></h1>

<p>Keyword: <?php echo $search_terms;  ?></p>

<?php if (isset($results) && is_array($results) && count($results)) : ?>

    <?php foreach ($results as $result) : ?>
    <div class="search-result">
        <p class="result-header">
            <a href="#"><?php echo $result['title'] ?></a>
        </p>
        <p class="result-excerpt">
            <?php echo $result['extract']; ?>
        </p>
    </div>
    <?php endforeach; ?>

<?php else: ?>

<?php endif; ?>

<pre><?php print_r($results); ?></pre>