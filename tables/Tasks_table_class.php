<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
	    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Tasks_table_class extends \WP_List_Table {

	function __construct() {
           parent::__construct( array(
               'singular' => 'Task',
	        'plural'   => 'Tasks',
	   ) );
 	}
	function get_table_classes() {
        	return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
 	}
	function no_items() {
        _e( 'No tasks found', 'itasks' );
	}
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
	        case 'tasks':
	 	       return $item->tasks;
	        case 'tasks_status':
                       return $item->tasks_status;
                case 'tasks_priority':
			return $item->tasks_priority;
		case 'tasks_start_date':
                       return $item->tasks_start_date;
		case 'tasks_end_date':
			return $item->tasks_end_date;
		case 'tasks_remarks':
			return $item->tasks_remarks;
		case 'tasks_file_link':
			return (get_site_option('allow_attachment')==1) ? $item->tasks_file_link : '';
               default:
	               return isset( $item->$column_name ) ? $item->$column_name : '';
         }
    	}
    	function get_columns() {
	        $columns = array(
        	        'cb'           => '<input type="checkbox" />',
                	'tasks'      => __( 'Tasks', 'itasks' ),
	                'tasks_status'      => __( 'Status', 'itasks' ),
			'tasks_priority'      => __( 'Priority', 'itasks' ),
        	        'tasks_start_date'      => __( 'Start Date', 'itasks' ),
        	        'tasks_end_date'      => __( 'End Date', 'itasks' ),
			'tasks_remarks'      => __( 'Remarks', 'itasks' ),
			'tasks_file_link'    => (get_site_option('allow_attachment')==1) ?  __('Attachments','itasks') : ''
	        );
        	return $columns;
     	}
	function column_tasks( $item ) {
	        $actions           = array();
		$actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', 
			admin_url( 'admin.php?page=itasks-form&action=edit&itasks_id='.$item->id.
			'&itasks_task='.$item->tasks.
			'&itasks_status='.$item->tasks_status.
			'&itasks_priority='.$item->tasks_priority.
			'&itasks_startdate='.$item->tasks_start_date.
			'&itasks_enddate='.$item->tasks_end_date.
			'&itasks_remarks='.$item->tasks_remarks.
			'&nonce='.wp_create_nonce('itasks-form-nonce')), 
			$item->id, __( 'Edit this item', 'itasks' ), __( 'Edit', 'itasks' ) );
		$actions['delete'] = sprintf( '<a href="%s">%s</a>', 
			admin_url( 'admin.php?page=itasks-list&action=delete&tasks_id=' . $item->id ),__( 'Delete', 'itasks' ) );
		return sprintf( '<a href="%s"><strong>%s</strong></a> %s', 
			admin_url( 'admin.php?page=itasks-form&action=view&id=' . $item->id ),
			$item->tasks, 
			$this->row_actions( $actions ) );
	}
	function column_cb( $item ) {
        	return sprintf(
	            '<input type="checkbox" name="tasks_id[]" value="%d" />', $item->id
        	);
	}
	function get_sortable_columns() {
        	$sortable_columns = array(
			'tasks' => array( 'tasks', true ),
			'tasks_start_date' => array( 'tasks_start_date', true ),
			'tasks_end_date' => array( 'tasks_end_date', true ),
        	);
        	return $sortable_columns;
    	}

	function get_bulk_actions() {
        	$actions = array(
			'delete'  => __( 'Delete', 'itasks' ),
			'set_done'  => __( 'Status: Set to Done', 'itasks' ),
			'set_pending'  => __( 'Status: Set to Pending', 'itasks' ),
			'set_ongoing'  => __( 'Status: Set to Ongoing', 'itasks' ),
			'set_overdue'  => __( 'Status: Set to Overdue', 'itasks' ),
			'set_high'  => __( 'Priority: Set to High', 'itasks' ),
			'set_medium'  => __( 'Priority: Set to Medium', 'itasks' ),
			'set_least'  => __( 'Priority: Set to Least', 'itasks' ),

	        );
        	return $actions;
	}
	function process_bulk_action(){
		global $wpdb;
		$table_name = "{$wpdb->prefix}itasks_tasks";
		$idss = isset($_REQUEST['tasks_id']) ? $_REQUEST['tasks_id'] : array();
		//Delete
		if (is_array($idss) && !empty($idss)) {
			$valid_ids = array_map('esc_attr', $idss); //array_map(function,array); esc_attr wordpress html escape function
			$ids = implode(',', $valid_ids);
			if ('delete' === $this->current_action()) {
	                	$wpdb->query("DELETE FROM {$table_name} WHERE id IN({$ids})");
			}
			if ('set_done' === $this->current_action()) {
		               	$wpdb->query("UPDATE {$table_name} SET tasks_status='Done' WHERE id IN({$ids})");
			}
			if ('set_pending' === $this->current_action()) {
		               	$wpdb->query("UPDATE {$table_name} SET tasks_status='Pending' WHERE id IN({$ids})");
			}
			if ('set_ongoing' === $this->current_action()) {
				$wpdb->query("UPDATE {$table_name} SET tasks_status='Ongoing' WHERE id IN({$ids})");
			}
			if ('set_overdue' === $this->current_action()) {
				$wpdb->query("UPDATE {$table_name} SET tasks_status='Overdue' WHERE id IN({$ids})");
			}
			if ('set_high' === $this->current_action()) {
				$wpdb->query("UPDATE {$table_name} SET tasks_priority='High' WHERE id IN({$ids})");
			}
			if ('set_medium' === $this->current_action()) {
				$wpdb->query("UPDATE {$table_name} SET tasks_priority='Medium' WHERE id IN({$ids})");
			}
			if ('set_least' === $this->current_action()) {
				$wpdb->query("UPDATE {$table_name} SET tasks_priority='Least' WHERE id IN({$ids})");
			}
		} else {
			$ids = absint($idss);
			if ('delete' === $this->current_action()) {
			        $wpdb->query("DELETE FROM {$table_name} WHERE id IN({$ids})");
			}
		}
	}
	/*function get_views_() {
        	$status_links   = array();
	        $base_link      = admin_url( 'admin.php?page=contact-box' );

        	foreach ($this->counts as $key => $value) {
	            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
        	    $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
	        }

        	return $status_links;
	}*/
	function prepare_items() {
        	$columns               = $this->get_columns();
	        $hidden                = array();
        	$sortable              = $this->get_sortable_columns();
	        $this->_column_headers = array( $columns, $hidden, $sortable );

		if(isset($_REQUEST['page_load'])){
			if(in_array($_REQUEST['page_load'],array(10,25,50))){
				update_option('itasks_per_page', $_REQUEST['page_load']);
			}
		}

		$per_page =   get_site_option('itasks_per_page') ? get_site_option('itasks_per_page') : 10;
	        $current_page = $this->get_pagenum();
        	$offset = ( $current_page -1 ) * $per_page;
		$tasks_status = isset($_REQUEST['tasks_status']) ? sanitize_text_field($_REQUEST['tasks_status']) : '';
		$tasks_priority = isset($_REQUEST['tasks_priority']) ? sanitize_text_field($_REQUEST['tasks_priority']) : '';
		$tasks_search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
		$tasks_id = isset($_REQUEST['tasks_id']) ? absint($_REQUEST['tasks_id']) : '';

		$total_items = 0;
		$this->process_bulk_action();

	        $args = array(
        	    'offset' => $offset,
		    'number' => $per_page,
		);
		$total_items = itasksGetTasksCount();
		if(!empty($tasks_id)){
			$args['tasksid'] = $tasks_id;
			$total_items = itasksGetTasksCountSearchedTasksId($tasks_id);
		}
		if ( !empty( $tasks_search ) ) {
			$args['search'] = $tasks_search;
			$total_items = itasksGetTasksCountSearchedTasks($tasks_search);
		}

		if ( !empty($tasks_status) ){
			$args['tasks_status'] = $tasks_status;
			if($tasks_status ==="Done"){
				$total_items = itasksGetTasksCountDone();
			}
			if($tasks_status ==="Pending"){
				$total_items = itasksGetTasksCountPending();
			}
			if($tasks_status ==="Ongoing"){
				$total_items = itasksGetTasksCountOngoing();
			}
			if($tasks_status ==="Overdue"){
				$total_items = itasksGetTasksCountOverdue();
			}
		}
		if ( !empty($tasks_priority) ){
			$args['tasks_priority'] = $tasks_priority;
			if($tasks_priority === "High"){
				$total_items = itasksGetTasksCountHigh();
			}
			if($tasks_priority === "Medium"){
				$total_items = itasksGetTasksCountMedium();
			}
			if($tasks_priority === "Least"){
				$total_items = itasksGetTasksCountLeast();
			}
		}

	        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
        	    $args['orderby'] = sanitize_text_field($_REQUEST['orderby']);
	            $args['order']   = sanitize_text_field($_REQUEST['order']);
		}
	        $this->items  = itasksGetAllTasks( $args );
        	$this->set_pagination_args( array(
		    'total_items' => $total_items,
		    'per_page'    => $per_page,
		  //  'total_pages' => ceil(icb_contact_get_Topic_count()/$per_page)
        	) );
    	}
}//Class
function itasksGetAllTasks( $args = array() ) {
	global $wpdb;

        $defaults = array(
	        'number'     => 10,
	        'offset'     => 0,
	        'orderby'    => 'id',
	        'order'      => 'DESC',
	    );

        $args      = wp_parse_args( $args, $defaults );
        $cache_key = 'Tasks-all';
	$items     = wp_cache_get( $cache_key, 'itasks' );
	if ( false === $items ) {
		$queryCondition = $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'];
		$query = 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks ORDER BY ' . $queryCondition;
		if(!empty($args['tasksid'])){
			$query = 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks'. ' WHERE id="'.$args['tasksid'].'" ORDER BY ' . $queryCondition;
		}
		if(!empty($args['tasks_status'])){
			$query = 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks'. ' WHERE tasks_status="'.$args['tasks_status'].'" ORDER BY ' . $queryCondition;
		}
		if(!empty($args['tasks_priority'])){
			$query = 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks'. ' WHERE tasks_priority="'.$args['tasks_priority'].'" ORDER BY ' . $queryCondition;
		}
		if(!empty($args['search'])) {
			$query = 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks'. ' WHERE tasks LIKE "%'.$args['search'].'%" ORDER BY ' . $queryCondition;
		} 
		$items = $wpdb->get_results( $query );
	        wp_cache_set( $cache_key, $items, 'itasks' );
        }
	return $items;
}
function itasksGetTasksCount() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks' );
}
function itasksGetTasksCountDone() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_status = "Done"' );
}
function itasksGetTasksCountPending() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_status = "Pending"' );
}
function itasksGetTasksCountOngoing() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_status = "Ongoing"' );
}
function itasksGetTasksCountOverdue() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_status = "Overdue"' );
}
function itasksGetTasksCountHigh() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_priority = "High"' );
}
function itasksGetTasksCountMedium() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_priority = "Medium"' );
}
function itasksGetTasksCountLeast() {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks_priority = "Least"' );
}
function itasksGetTasksCountSearchedTasks($tasks = '') {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE tasks LIKE "%'.$tasks.'%"' );
}
function itasksGetTasksCountSearchedTasksId($tasksid = '') {
	global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'itasks_tasks WHERE id = '.$tasksid );
}
function itasksGetTasks( $id = 0 ) {
	global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'itasks_tasks WHERE id = %d', $id ) );
}
function itasksSubNav($id = 0){
	global $wpdb;
	$totalTasks = itasksGetTasksCount();
	$totalDone = itasksGetTasksCountDone();
	$totalPending = itasksGetTasksCountPending();
	$totalOngoing = itasksGetTasksCountOngoing();
	$totalOverdue = itasksGetTasksCountOverdue();
	$totalHigh = itasksGetTasksCountHigh();
	$totalMedium = itasksGetTasksCountMedium();
	$totalLeast = itasksGetTasksCountLeast();
	$page_load = isset($_REQUEST['page_load']) ? $_REQUEST['page_load'] : '';
	$tasks_status = isset($_REQUEST['tasks_status']) ? $_REQUEST['tasks_status'] : '';
	$tasks_priority = isset($_REQUEST['tasks_priority']) ? $_REQUEST['tasks_priority'] : '';


?>
	<ul class="subsubsub">
		<li class="all">
			<a href="admin.php?page=itasks-list" class="current" aria-current="page">All <span class="count"><?php echo $totalTasks; ?></span></a> |</li>
		<li class="draft">
		<a href="#" class="current">Per Page:</a>
		</li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&page_load=10" <?php echo $page_load ==="10" ? 'class="current"':''; ?> >
			10 <span class="count" aria-current="page">
			</span></a>
		</li>
		<li class="draft">
			<a href="admin.php?page=itasks-list&page_load=25" <?php echo $page_load==="25" ? 'class="current"':'';?> >
			25 <span class="count" aria-current="page">
			</span></a>
		</li>
		<li class="draft">
			<a href="admin.php?page=itasks-list&page_load=50" <?php echo $page_load==="50"  ? 'class="current"':'';?> >
			50 <span class="count" aria-current="page">
			</span></a>
		</li>
		<br>
		<li class="draft">
			<a href="#" class="current">Status:</a>
		</li>
		<li class="draft">
			<a href="admin.php?page=itasks-list&tasks_status=Done" <?php echo $tasks_status ==='Done' ? 'class="current"' :''; ?> >
			Done <span class="count" aria-current="page">
			<?php echo $totalDone; ?>
			</span></a>
		</li>
		<li class="draft">
			<a href="admin.php?page=itasks-list&tasks_status=Pending" <?php echo $tasks_status==='Pending' ? 'class="current"' :''; ?> >
			Pending <span class="count" aria-current="page">
			<?php echo $totalPending; ?>
			</span></a>
		</li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&tasks_status=Ongoing" <?php echo $tasks_status==='Ongoing' ? 'class="current"' :''; ?> >
			Ongoing <span class="count" aria-current="page">
			<?php echo $totalOngoing; ?>
		</span></a></li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&tasks_status=Overdue" <?php echo $tasks_status==='Overdue' ? 'class="current"' :''; ?> >
			Overdue <span class="count" aria-current="page">
			<?php echo $totalOverdue; ?>
		</span></a> |</li>
		<li class="draft">
			<a href="#" class="current">Priority:</a>
		</li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&tasks_priority=High" <?php echo $tasks_priority==='High' ? 'class="current"' :''; ?> >
			High <span class="count" aria-current="page">
			<?php echo $totalHigh; ?>
		</span></a></li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&tasks_priority=Medium" <?php echo $tasks_priority==='Medium' ? 'class="current"' :''; ?> >
			Medium <span class="count" aria-current="page">
			<?php echo $totalMedium; ?>
		</span></a></li>
		<li class="draft">
		<a href="admin.php?page=itasks-list&tasks_priority=Least" <?php echo $tasks_priority==='Least' ? 'class="current"' :''; ?> >
			Least <span class="count" aria-current="page">
			<?php echo $totalLeast; ?>
		</span></a></li>


	</ul>
<?php
}
function itasks_tasks_list_handler(){
?>
<div class="wrap">
	<h2><?php _e( 'Tasks', 'itasks' ); ?> 
		<a href="<?php echo admin_url( 'admin.php?page=itasks-form&action=add' ); ?>" class="add-new-h2">
			<?php _e( 'Add Tasks', 'itasks' ); ?>
		</a>
	</h2>
	<?php
		itasksSubNav();
	?>
        <form method="post">
	        <input type="hidden" name="page" value="ttest_list_table">
<?php 
        $list_table = new Tasks_table_class();
        $list_table->prepare_items();
        $list_table->search_box( 'search', 'search_id' );
	$list_table->display();
        ?>
    </form>
</div>
<?php
}
