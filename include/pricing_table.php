<!-- <form method="post" action="<?php //echo get_admin_url();?>admin.php?page=pricing-table-page" > -->
<div class="container price_table">
	<div class="row">
		<div class="col-md-12 messagebox-flag" id="messagebox-flag"></div>
		<div class="col-md-2">
			<p>Select a pricing table to edit</p>
		</div>
		<div class="col-md-4">
			<?php include('load-product-dropdown.php'); ?>
		</div>
		<div class="col-md-6">
			<button class="btn btn-default select-table-pricing" type="button">Select</button>
			<span>or <a href="<?php echo get_admin_url();?>admin.php?page=pricing-table-page&active=true" class="create-new-table">Create new table</a></span>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-3 product-left-pricing">
			<h4>Product</h4>
			<div class="checkbox-table-pricing">
				<?php include('load_product.php');?>
			    <hr/>
			    <div style="float:right;">
			    	<button class="btn btn-default" type="button" id="add_to_table">Add to Table</button>
			    </div>
			</div>
		</div>
		<div class="col-md-9 product-right-pricing">
		<form class="form-create-table-pricing" id="form-create-table-pricing" action="" method="post">
			<div class="row">
				<div class="col-xs-2">Table name:</div>
				<div class="col-xs-4">
					<input type="text" name="table-name-pricing" class="form-control" placeholder="Enter table name here">
				</div>
				<div class="col-xs-6">
					<input type='hidden' name='page' value='pricing-table-page' />					
					<input type="submit" value="Create Table" name="create_table" class="btn btn-default create-table" style="float:right;" >
				</div>
			</div>
			<p></p>
			<div class="row">
				<div class="col-xs-2">Shortcode name:</div>
				<div class="col-xs-4">
					<input type="text" name="shortcode-name" class="form-control" disabled>
				</div>
			</div>
			<hr/>
		</form>				
		<form class="form-save-table-pricing" id="form-save-table-pricing" method="post" action="">
			<div class="title-table">	
				<div class="row title-table-show">
					<div class="col-md-2">Table name:</div>
					<div class="col-md-4">
						<input type="text" name="table-name-pricing" class="form-control" placeholder="Enter table name here">
					</div>
					<div class="col-md-6">
						<input type='hidden' name='page' value='pricing-table-page' />					
						<input class="btn btn-default save-table" type="submit" value="Save Table" name="save-table" style="float:right;">
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-md-2">Shortcode name:</div>
					<div class="col-md-4">
						<input type="text" name="shortcode-name" class="form-control" disabled>						
					</div>
					<div class="col-md-6">
						<label class="lb-show-shortcode"></label>
					</div>									
				</div>	
				<hr/>		
				<div class="row title-table-pricing">
					<div class="col-md-12">
						<h4>Table Structure</h4>
						<p>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>
					</div>					
				</div>
				<div class="col-md-12">
					<div class="column resutlt_add">					  		  			
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<a class="delete-table-pricing">Delete Table</a>
					</div>
					<div class="col-md-6">
						<input class="btn btn-default save-table" type="submit" value="Save Table" name="save-table" style="float:right;">
					</div>
				</div>
			</div>
		</form>			
		</div>
	</div>
</div>
<!-- </form> -->
<div class="hidden-show-table-name" style="display:none;"></div>