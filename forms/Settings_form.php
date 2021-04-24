<?php

function itasks_settings_form_handler(){
	$message = '';
	//$superRole = isset($_REQUEST['super_role']) ? absint($_REQUEST['super_role']) : 0;
	//$adminRole = isset($_REQUEST['admin_role']) ? absint($_REQUEST['admin_role']) : 0;
	$editorRole = isset($_REQUEST['editor_role']) ? absint($_REQUEST['editor_role']) : 0;
	$authorRole = isset($_REQUEST['author_role']) ? absint($_REQUEST['author_role']) : 0;
	$contributorRole = isset($_REQUEST['contributor_role']) ? absint($_REQUEST['contributor_role']) : 0;
	$messagesArr = array();
	$args = array(
		//'super_role'=>$superRole,
		//'admin_role'=>$adminRole,
		'editor_role'=>$editorRole,
		'author_role'=>$authorRole,
		'contributor_role'=>$contributorRole
	);
	if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'itasks-form-nonce')) {
		$messagesArr = itasksSettingsValidate($args);
		if($messagesArr === true) {
			//update_option('super_role', $superRole);
			//update_option('admin_role', $adminRole);
			update_option('editor_role', $editorRole);
			update_option('author_role', $authorRole);
			update_option('contributor_role', $contributorRole);
			$message = 'Successfuly updated!';
		}
	}
	add_meta_box('itasks_settings_form_id', 'Roles', 'itasks_settings_form_metabox', 'itasks_settings_form', 'normal', 'default');
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
        <!-- tr>
	    <td>Super Admin</td>
	    <td>
                <select name="super_role" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('super_role') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
                </select>
	    </td>
	</tr -->
	<!-- tr>
	    <td>Administrator</td>
	    <td>
		<select name="admin_role" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('admin_role') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
                </select>
	    </td>
	</tr -->
	<tr>
	    <td>Editor</td>
	    <td>
		<select name="editor_role" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('editor_role') == $key){
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
	    <td>Author</td>
	    <td>
		<select name="author_role" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('author_role') == $key){
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
	    <td>Contributor</td>
	    <td>
		<select name="contributor_role" style="width:100%;">
                <option value='' >Choose</option>
		<?php
                foreach($selectValues as $key => $text){
	                if(get_site_option('contributor_role') == $key){
		                echo "<option selected value='{$key}'>".__($text,'itasks')."</option>";
                        } else {
                                echo "<option value='{$key}'>".__($text,'itasks')."</option>";
                        }
                }
		?>
                </select>
	    </td>
	</tr>

        </tbody>
    </table>
<?php
}
function itasksSettingsValidate($args=array()){
	$messages = array();
        //if (empty($args['super_role'])) $messages[] = __('Super Admin field is empty!','itasks');
        //if (empty($args['admin_role']))  $messages[] = __('Admin field is empty!','itasks');
        if (empty($args['editor_role']))  $messages[] = __('Editor field is empty!','itasks');
        if (empty($args['author_role'])) $messages[] = __('Author field is empty!','itasks');
        if (empty($args['contributor_role']))  $messages[] = __('Contributor field is empty!','itasks');
        if (empty($messages)) return true; //True if Messages are empty
        return $messages;
}
