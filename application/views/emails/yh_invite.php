<p>Hey <?php echo $username; ?>,</p>

<p>Here's a link for you to register at the new board we've got!</p>

<p><a href="<?php echo $this->config->item('base_url_pm'); ?>auth/register/<?php echo $invite_id; ?>"><?php echo $this->config->item('base_url_pm'); ?>auth/register/<?php echo $invite_id; ?></a></p>

<p>cheers!,</p>

<p>Love,<br />
The guys at <?php echo $this->config->item('email_signature'); ?><br />
<?php echo $this->config->item('base_url_pm'); ?></p>