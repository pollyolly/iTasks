<?php

function itasks_add_form_handler(){
	global $wpdb;
	$table_name = "{$wpdb->prefix}itasks_tasks";
	$messagesArr = array();
	$message = '';
	$tasksid = 0;
	$itasks_id = isset($_REQUEST['itasks_id']) ? absint($_REQUEST['itasks_id']) : '';
	$itasks_task = isset($_REQUEST['itasks_task']) ? sanitize_text_field($_REQUEST['itasks_task']) : '';
	$itasks_status = isset($_REQUEST['itasks_status']) ? sanitize_text_field($_REQUEST['itasks_status']) : '';
	$itasks_priority = isset($_REQUEST['itasks_priority']) ? sanitize_text_field($_REQUEST['itasks_priority']) : '';
	$itasks_startdate = isset($_REQUEST['itasks_startdate']) ? sanitize_text_field($_REQUEST['itasks_startdate']) : '';
	$itasks_enddate = isset($_REQUEST['itasks_enddate']) ? sanitize_text_field($_REQUEST['itasks_enddate']) : '';
	$itasks_remarks = isset($_REQUEST['itasks_remarks']) ? sanitize_text_field($_REQUEST['itasks_remarks']) : '';
	$args = array(
		'tasks'=>$itasks_task,
		'tasks_status'=>$itasks_status,
		'tasks_priority'=>$itasks_priority,
		'tasks_start_date'=> $itasks_startdate,
		'tasks_end_date'=>$itasks_enddate,
		'tasks_remarks'=>$itasks_remarks
	);
	if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'itasks-form-nonce')) {
		$messagesArr = itasksValidate($args);
		$args['tasks_start_date']= date("Y/m/d", strtotime($itasks_startdate));
		$args['tasks_end_date']= date("Y/m/d", strtotime($itasks_enddate));
		if($messagesArr === true && empty($itasks_id)){
			$result = $wpdb->insert($table_name, $args);
			if($result){
				$tasksid = $wpdb->insert_id;
				$message = 'Successfully saved!';
			} else { $messagesArr = array('Unable to save!'); }
		}
		if(!empty($itasks_id)){
			$result = $wpdb->update($table_name, $args, array('id' => $itasks_id));
			if($result){
				$tasksid = $wpdb->insert_id;
				$message = 'Successfully updated!';
			} else { $messagesArr= array('Unable to update!'); }
		}
		
	}
	$args['taskid'] = $itasks_id;
	add_meta_box('itasks_add_form_id', 'Form', 'itasks_add_form_metabox', 'itasks_add_form', 'normal', 'default');
?>
<div class="wrap">
	<h2>Add Tasks
	        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=itasks-list');?>">Back to list</a>
	</h2>
<?php
	if(!empty($message)){
		echo sprintf('<div id="message" class="updated"><p>%s  <a href="%s">'.__('View Task',"itasks").'</a></p></div>',
			__($message,'itasks'),
			admin_url('admin.php?page=itasks-list&tasks_id='.$itasks_id)
		);
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
	<input type="hidden" name="taskid" value="<?php echo $args['taskid'] ?>"/>
		<div class="metabox-holder" id="poststuff">
			<div id="post-body" style="max-width:500px;">
				<div id="post-body-content">
				<?php do_meta_boxes('itasks_add_form', 'normal', $args); ?>
				<input type="submit" value="Save Task" id="submit" class="button-primary" name="submit">	
				</div>
			</div>
		</div>
	</form>
</div>
<?php

}

function itasks_add_form_metabox($args){
?>
<table cellspacing="2" cellpadding="5" class="form-table">
        <tbody>
        <tr>
	    <td>Task</td>
	    <td>
                <input id="itasks_task" name="itasks_task" type="text" style="width:100%;" value="<?php echo esc_attr($args['tasks'])?>"
                    size="50" class="code" placeholder="Task">
	    </td>
	</tr>
	<tr>
	    <td>Status</td>
	    <td>
		<select name="itasks_status" style="width:100%;">
		<option value='' >Choose Status</option>
<?php
		$selectValues = array('Done','Pending','Ongoing','Overdue');
		foreach($selectValues as $item){
			if($args['tasks_status'] === $item){
				echo "<option selected value='{$item}'>".__($item,'itasks')."</option>";
			} else {
				echo "<option value='{$item}'>".__($item,'itasks')."</option>";
			}
		}
?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td>Priority</td>
	    <td>
		<select name="itasks_priority" style="width:100%;">
		<option value='' >Choose Priority</option>
<?php
		$selectValues = array('High','Medium','Least');
		foreach($selectValues as $item){
			if($args['tasks_priority'] === $item){
				echo "<option selected value='{$item}'>".__($item,'itasks')."</option>";
			} else {
				echo "<option value='{$item}'>".__($item,'itasks')."</option>";
			}
		}
?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td>Start date</td>
	    <td>
                <input id="itasks_startdate" name="itasks_startdate" type="date" style="width:100%;" value="<?php if(!empty($args['tasks_start_date'])) echo esc_attr(date("Y-m-d", strtotime($args['tasks_start_date'])))?>"
                    size="50">
	    </td>
	</tr>
	<tr>
	    <td>End date</td>
	    <td>
                <input id="itasks_enddate" name="itasks_enddate" type="date" style="width:100%;" value="<?php if(!empty($args['tasks_end_date'])) echo esc_attr(date("Y-m-d", strtotime($args['tasks_end_date'])))?>"
                    size="50">
	    </td>
	</tr>
	<tr>
	    <td>Remarks</td>
	    <td>
		<textarea id="itasks_remarks" name="itasks_remarks" rows="4" cols="60"><?php echo esc_attr($args['tasks_remarks'])?></textarea>
	    </td>
	</tr>
        </tbody>
    </table>
<?php
}
function itasksValidate($args=array()){
	$messages = array();
	if (empty($args['tasks'])) $messages[] = __('Tasks field is empty!','itasks');
	if (empty($args['tasks_status']))  $messages[] = __('Status field is empty!','itasks');
	if (empty($args['tasks_priority']))  $messages[] = __('Priority field is empty!','itasks');
	if (empty($args['tasks_start_date'])) $messages[] = __('Start date field is empty!','itasks');
	if (empty($args['tasks_end_date']))  $messages[] = __('End date field is empty!','itasks');
	if (empty($args['tasks_remarks']))  $messages[] = __('Remarks field is empty!','itasks');
        if (empty($messages)) return true; //True if Messages are empty
	return $messages;
}
