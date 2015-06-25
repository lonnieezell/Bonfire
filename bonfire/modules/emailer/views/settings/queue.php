<style>
th.id { width: 2em; }
th.to { width: 10em; }
th.attempts { width: 6em; }
td.attempts { text-align: center !important; }
th.sent { width: 3em; }
td.sent { text-align: center !important; }
th.preview { width: 6em; }
td.preview { text-align: center !important; }
</style>
<div class="admin-box">
    <div class="row">
        <div class="column size1of3">
            <p><strong><?php echo lang('emailer_total_in_queue'); ?></strong> <?php echo $total_in_queue ? $total_in_queue : '0'; ?></p>
        </div>
        <div class="column size1of3">
            <p><strong><?php echo lang('emailer_total_sent'); ?></strong> <?php echo $total_sent ? $total_sent : '0'; ?></p>
        </div>
        <div class="column size1of3 last-column text-right">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-inline')); ?>
                <input type="submit" name="force_process" class="btn btn-primary" value="<?php e(lang('emailer_force_process')); ?>" />
                <input type="submit" name="insert_test" class="btn btn-warning" value="<?php e(lang('emailer_insert_test')); ?>" />
            <?php echo form_close(); ?>
        </div>
    </div>
    <?php if (empty($emails) || ! is_array($emails)) : ?>
    <div class="alert alert-warning">
        <p><?php echo lang('emailer_stat_no_queue'); ?></p>
    </div>
    <?php
    else :
        $numColumns = 7;
        echo form_open($this->uri->uri_string());
    ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="column-check"><input class="check-all" type="checkbox" /></th>
                    <th class="id"><?php echo lang('emailer_id'); ?></th>
                    <th class="to"><?php echo lang('emailer_to'); ?></th>
                    <th><?php echo lang('emailer_subject'); ?></th>
                    <th class="attempts"># <?php echo lang('emailer_attempts'); ?></th>
                    <th class="sent"><?php echo lang('emailer_sent'); ?></th>
                    <th class="preview"><?php echo lang('bf_action_preview'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $numColumns; ?>">
                        <?php echo lang('bf_with_selected') . '&nbsp;'; ?>
                        <button type="submit" name="delete" id="delete-me" class="btn btn-danger" onclick="return confirm('<?php e(js_escape(lang('emailer_delete_confirm'))); ?>')">
                            <span class="icon-white icon-trash"></span> <?php echo lang('bf_action_delete'); ?>
                        </button>
                    </td>
                </tr>
                <?php if ($this->pagination->create_links()) : ?>
                <tr>
                    <td colspan="<?php echo $numColumns; ?>" class="text-left"><?php echo $this->pagination->create_links(); ?></td>
                </tr>
                <?php endif; ?>
            </tfoot>
            <tbody>
                <?php foreach ($emails as $email) :?>
                <tr>
                    <td class='column-check'><input type="checkbox" name="checked[]" value="<?php echo $email->id; ?>" /></td>
                    <td class='id'><?php echo $email->id; ?></td>
                    <td class='to'><?php e($email->to_email); ?></td>
                    <td><?php e($email->subject); ?></td>
                    <td class="attempts"><?php echo $email->attempts; ?></td>
                    <td class="sent"><?php echo $email->success ? lang('bf_yes') : lang('bf_no'); ?></td>
                    <td class="preview"><?php echo anchor(SITE_AREA . "/settings/emailer/preview/{$email->id}", lang('bf_action_preview'), array('target'=>'_blank')); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        echo form_close();
    endif;
        ?>
</div>
<?php if (isset($email_debug)) : ?>
<h3><?php echo lang('emailer_queue_debug_heading'); ?></h3>
<div class="notification attention">
    <p><?php echo lang('emailer_queue_debug_error'); ?></p>
</div>
<div class="box">
    <?php echo $email_debug; ?>
</div>
<?php endif;
