<?php 
	global $wpdb;
	$query = "SELECT * FROM  {$this->table['pricing_table_name']}"; 
	 $results= $wpdb->get_results($query); 
	 echo '<select class="form-control"> ';
	 foreach ($results as $value) {
	 	echo '<option value="'.$value->ID.'" class="option-table-name-'.$value->ID.'">'.$value->Name.'</option>';
	 }
 ?>		  
</select>