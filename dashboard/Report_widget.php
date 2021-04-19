<?php

add_action('wp_dashboard_setup', 'itasks_priority_report');
function itasks_priority_report(){
	wp_add_dashboard_widget('itasks_dashboard_report',__('Top 5 Priority Tasks','itasks'),'itasks_priority_report_handler');
}
function itasks_priority_report_handler(){
	$totalDone = itasksGetTasksCountDone();
	$totalPending = itasksGetTasksCountPending();
	$totalOngoing = itasksGetTasksCountOngoing();
	$totalOverdue = itasksGetTasksCountOverdue();
?>
<ul class="subsubsub">
	<li class="all"><a href="admin.php?page=itasks-list&tasks_status=Done">Done
		<span class="count">
			(<span class="all-count"><?php echo $totalDone;?></span>)
		</span></a>|
	</li>
	<li class="mine"><a href="admin.php?page=itasks-list&tasks_status=Pending">Pending
		<span class="count">
			(<span class="all-count"><?php echo $totalPending;?></span>)
		</span></a>|
	</li>
	<li><a href="admin.php?page=itasks-list&tasks_status=Ongoing">Ongoing
		<span class="count">
			(<span class="all-count"><?php echo $totalOngoing;?></span>)
		</span></a>|
	</li>
	<li><a href="admin.php?page=itasks-list&tasks_status=Overdue">Overdue
		<span class="count">
		(<span class="all-count"><?php echo $totalOverdue;?></span>)
		</span></a>
	</li>
</ul>
<?php
	global $wpdb;
	$tasksPriority=$wpdb->get_results("SELECT id, tasks, tasks_end_date,DATEDIFF(tasks_end_date, NOW()) as days FROM {$wpdb->prefix}itasks_tasks WHERE tasks_status !='Done' ORDER BY days ASC LIMIT 5;");
	//var_dump($tasksPriority);
	echo "<table class='wp-list-table widefat fixed striped'>";
	echo "<thead><th>Tasks</th><th>End Date</th><th>Days Remaining</th></thead><tbody>";
	foreach($tasksPriority as $rows){
		echo sprintf("<tr><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>",
			admin_url('admin.php?page=itasks-list&tasks_id='.$rows->id),
			$rows->tasks,
			$rows->tasks_end_date,
			$rows->days
		);
		//echo "<tr><td><a href=".admin_url('admin.php?page=itasks-list&tasks_id='".$rows->id.").">".$rows->tasks."'</a></td><td>'".$rows->tasks_end_date."'</td><td>'".$rows->days."'</td></tr>";
	}
	echo "</tbody></table>";
}

?>
