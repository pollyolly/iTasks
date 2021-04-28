<?php

function itasks_settings_form_handler(){
	$message = '';
	$allowAttachment = isset($_REQUEST['allow_attachment']) ? absint($_REQUEST['allow_attachment']) : 0;
	$allowExport = isset($_REQUEST['allow_export']) ? absint($_REQUEST['allow_export']) : 0;
	$allowBackup = isset($_REQUEST['allow_backup']) ? absint($_REQUEST['allow_backup']) : 0;
	$messagesArr = array();
	$args = array(
		'allow_attachment'=>$allowAttachment,
		'allow_export'=>$allowExport,
		'allow_backup'=>$allowBackup
	);
	if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'itasks-form-nonce')) {
		$messagesArr = itasksSettingsValidate($args);
		if($messagesArr === true) {
			update_option('allow_attachment', $allowAttachment);
			update_option('allow_export', $allowExport);
			update_option('allow_backup', $allowBackup);
			$message = 'Successfuly updated!';
		}
	}
	add_meta_box('itasks_settings_form_id', 'Form', 'itasks_settings_form_metabox', 'itasks_settings_form', 'normal', 'default');
?>
<div class="wrap">
	<h2>Settings
	        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=itasks-list');?>">Back to list</a>
	</h2>
<?php
	if(!empty($message)){
		echo sprintf('<div id="message" class="updated"><p>%s</p></div>', __($message,'itasks'));
		//echo "<div id='message' class='updated'><p>".__($message,'itasks')."</p></div>";
	}
	if(is_array($messagesArr)){
		foreach($messagesArr as $notices){
			echo "<div id='message' class='error'><p>".__($notices,'itasks')."</p></div>";
		}
	}
?>
	<form method="POST">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('itasks-form-nonce')?>"/>
		<div class="metabox-holder" id="poststuff">
			<div id="post-body" style="max-width:500px;">
				<div id="post-body-content">
				<?php do_meta_boxes('itasks_settings_form', 'normal', $args); ?>
				<input type="submit" value="Save Settings" id="submit" class="button-primary" name="submit">	
				</div>
			</div>
		</div>
	</form>
</div>
<?php

}

function itasks_settings_form_metabox($args){
	$selectValues = array('1'=>'Allow', '2'=>'Not Allow');
?>
<table cellspacing="2" cellpadding="5" class="form-table">
        <tbody>
	<tr>
	    <td>Attachment</td>
	    <td>
		<select name="allow_attachment" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('allow_attachment') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
                </select>
	    </td>
	</tr>
	<tr>
	    <td>Export</td>
	    <td>
		<select name="allow_export" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('allow_export') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
                </select>
	    </td>
	</tr>
	<tr>
	    <td>Backup</td>
	    <td>
		<select name="allow_backup" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('allow_backup') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
		</select>
		<p>/var/tmp/<?php foreach(scandir('/var/tmp') as $dirs){if($dirs=='itasks_backup.sql'){echo $dirs;}}?></p>
	    </td>
	</tr>

        </tbody>
    </table>
<?php
}
function itasksSettingsValidate($args=array()){
	$messages = array();
        if (empty($args['allow_attachment']))  $messages[] = __('Attachment field is empty!','itasks');
        if (empty($args['allow_export'])) $messages[] = __('Export field is empty!','itasks');
        if (empty($args['allow_backup']))  $messages[] = __('Backup field is empty!','itasks');
        if (empty($messages)) return true; //True if Messages are empty
        return $messages;
}
