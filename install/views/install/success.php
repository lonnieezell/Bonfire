<?php $this->load->view('header'); ?>

<?php echo lang('in_intro'); ?>

<?php if(isset($rebase)):?>
<p><?php echo lang('in_success_rebase_msg').$rebase;?></p>
<?php endif;?>

<?php echo lang('in_success_msg'); ?> <?php echo anchor('../', 'Bonfire'); ?>

<?php $this->load->view('footer'); ?>
