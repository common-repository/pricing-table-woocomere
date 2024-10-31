<?php 
/*
	Plugin Name: Pricing Table Woocommerce
	Plugin URI: http://thelifecode.net
	Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from comment and trackback spam</strong>. It keeps your site protected from spam even while you sleep. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://akismet.com/get/">Sign up for an Akismet API key</a>, and 3) Go to your Akismet configuration page, and save your API key.
	Version: 1.0
	Author: Phihai1910
	Author URI: http://thelifecode.net
	License: GPLv2 or later
	Text Domain: Woocommerce
 */	
add_action( 'init', array ( 'Plugin_Pricing_Table', 'init_fontend' ) );
register_activation_hook(__FILE__, array('Plugin_Pricing_Table', 'activate'));
register_deactivation_hook(__FILE__, array('Plugin_Pricing_Table', 'deactivate'));
class Plugin_Pricing_Table
{
    /**
     * Creates a new instance.
     *
     * @wp-hook init
     * @see    __construct()
     * @return void
     */
	protected $plugin_dir;
	protected $plugin_url;
	protected $version = 1.05;
	protected $db_version = 1000;
	protected $option;
	protected $option_handle = 'pricing_table_Woocommerce';
	protected $table = array();
    public static function init()
    {
        new self;        
	
    }
    /**
     * Register the action. May do more magic things.
     */
    public function __construct()
    {
		global $wpdb;
		$this->plugin_dir = plugin_dir_path(__FILE__);
		$this->plugin_url = plugin_dir_url(__FILE__);
		add_shortcode('pricing_table_product',array(&$this,'create_pricing_table_product'));	
		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'),99);
		add_action('wp_head', array(&$this, 'pluginname_ajaxurl'));		
		$this->option = get_option($this->option_handle);
		$this->table = array(
			'pricing_table_name' => $wpdb->prefix.'Woocommerce_pricing_table_name',
			'pricing_table_detail' => $wpdb->prefix.'Woocommerce_pricing_table_detail'
		);
    }
	public function admin_init()
	{
		if(isset($_REQUEST['page']) && $_REQUEST['page'] = 'pricing-table-page'){						

			wp_register_style($this->plugin_handle.'-admin-css',$this->plugin_url.'styles/admin.css', 'all' ); 
  			wp_enqueue_style($this->plugin_handle.'-admin-css');

  			wp_register_style($this->plugin_handle.'-admin-css-ui', $this->plugin_url.'styles/jquery-ui.min.css', 'all' ); 
  			wp_enqueue_style($this->plugin_handle.'-admin-css-ui');

  			wp_register_style($this->plugin_handle.'-boostrap-css',$this->plugin_url.'css/bootstrap.min.css', 'all' ); 
  			wp_enqueue_style($this->plugin_handle.'-boostrap-css');

  			wp_register_style($this->plugin_handle.'-boostrap-theme-css',$this->plugin_url.'css/bootstrap-theme.min.css', 'all' ); 
  			wp_enqueue_style($this->plugin_handle.'-boostrap-theme-css');   			  			

			wp_register_script($this->plugin_handle.'jquery-custom',$this->plugin_url.'js/custom.js', array('jquery-ui-sortable') );
            wp_enqueue_script($this->plugin_handle.'jquery-custom'); 			  					  					
																																	
		}		
		add_action( 'wp_ajax_add_to_cart_pricing_table', array(&$this, 'add_to_cart_pricing_table' ));
		add_action('wp_ajax_nopriv_add_to_cart_pricing_table', array(&$this,'add_to_cart_pricing_table' ));
		add_action( 'wp_ajax_delete_tables_data', array(&$this, 'delete_tables_data' ));
		add_action('wp_ajax_nopriv_delete_tables_data', array(&$this,'delete_tables_data' ));
		add_action( 'wp_ajax_create_table_pricing', array(&$this, 'create_table_pricing' ));
		add_action('wp_ajax_nopriv_create_table_pricing', array(&$this,'create_table_pricing' ));
		add_action( 'wp_ajax_pricing_tables_data', array(&$this, 'pricing_tables_data' ));
		add_action('wp_ajax_nopriv_pricing_tables_data', array(&$this,'pricing_tables_data' ));
		add_action( 'wp_ajax_show_table_pricing', array(&$this, 'show_table_pricing' ));
		add_action('wp_ajax_nopriv_show_table_pricing', array(&$this,'show_table_pricing' ));
				
	}
	public function init_fontend()
	{
		$pricing_table_class = new Plugin_Pricing_Table();
		if(!is_admin()){
			wp_register_style('styles-css-pricing',$pricing_table_class->plugin_url.'styles/styles.css', 'all' ); 
  			wp_enqueue_style('styles-css-pricing');  			

			wp_register_script('jquery-addtocart',$pricing_table_class->plugin_url.'js/addtocart.js', array('jquery'));
            wp_enqueue_script('jquery-addtocart');							
		}	
	}
	public function activate()
	{
		$pricing_table_class = new Plugin_Pricing_Table();
		// Check and update db
		$pricing_table_class->update_db();
	}
	
	public function deactivate()
	{
		
	}
	
	public function update_db()
	{
		global $wpdb;
		// Check db version
		$version = get_option($this->option_handle.'_db');
		if ($version == '')
		{
			// Create db
			$filename = $this->db_version.'.php';
			if ( file_exists($this->plugin_dir.'/db/'.$filename) )
			{
				include($this->plugin_dir.'/db/'.$filename);
				foreach ( (array)$query_create as $query )
				{
					if ( $query )
						$wpdb->query($query);
				}				
				
				$version = $this->db_version;
			}
		}
		else
		{
			if ( $this->db_version > $version )
			{
				// Update may available, check on db files
				foreach ( (array)scandir($this->plugin_dir.'/db') as $filename )
				{
					if ( ! is_file($this->plugin_dir.'/db/'.$filename) )
						continue;
					$file_ver = preg_replace('/[^0-9]/', '', $filename);
					if ( $file_ver && $file_ver <= $version )
						continue;
					// This update will be applied
					include($this->plugin_dir.'/db/'.$filename);
					foreach ( (array)$query_update as $query )
					{
						if ( $query )
							$wpdb->query($query);
					}
					$version = $file_ver;
				}
			}
		}
		update_option($this->option_handle.'_db', $version);
	}
	public function check_db_update()
	{
		$version = get_option($this->option_handle.'_db');
		if ( $version )
		{
			return $this->db_version > $version;
		}
		return false;
	}
	public function admin_menu()
	{
		 add_submenu_page( 'woocommerce', 'Pricing Table', 'Pricing Table', 'manage_options', 'pricing-table-page', array(&$this,'pricing_table_page_callback') );
	}
	function pluginname_ajaxurl() {
		?>
			<script type="text/javascript">
				var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			</script>
		<?php
	}
	function pricing_table_page_callback() {
		echo '<h3>Pricing Tables</h3>';
		include ('include/pricing_table.php');			
		//include ('include/delete-table.php');		
	}
public function create_table_pricing(){
	global $wpdb;
	$array_table=array();
	parse_str($_REQUEST['fromdata'],$array_table);	
	$name_table=$array_table['table-name-pricing'];
	$table_shortcode_pricing =str_replace(' ', '-',$name_table);
	$shortcode_name='';
	//echo 'name: '.$table_name_pricing;
	if (isset($name_table) && isset($table_shortcode_pricing) && $name_table!='') {
		$query = "SELECT * FROM  {$this->table['pricing_table_name']} where Name='{$name_table}' "; 
		 $results= $wpdb->get_results($query); 
		 if (count($results)!=0) {
			 foreach ($results as $value) {		 	
			 	$query = "SELECT * FROM  {$this->table['pricing_table_name']} where ShortCode='{$value->ShortCode}' "; 
			 	$results_shortcode= $wpdb->get_row($query); 
			 	if (count($results_shortcode)!=0) {		 		
			 		$shortcode_name=$results_shortcode->ShortCode.'-1';
			 	}
			 	else
			 		break;		 	
			 }
		 }
		 else
			$shortcode_name=$table_shortcode_pricing;
		$wpdb->insert( 
			$this->table['pricing_table_name'], 
			array( 
				'Name' => $name_table, 
				'ShortCode' => $shortcode_name
			), 
			array( 
				'%s', 
				'%s' 
			) 
		);
		$query_1 = "SELECT * FROM  {$this->table['pricing_table_name']} where ShortCode='{$shortcode_name}' "; 
			 	$show_input= $wpdb->get_row($query_1); 
		echo '<input type="hidden" Name="id-table-pricing" value="'.$show_input->ID.'" />';
		echo '<p class="table-name" style="display:none;">'.$show_input->Name.'</p>';	
		echo '<p class="shortcode-name" style="display:none;">'.$show_input->ShortCode.'</p>';			
		//add_action( 'admin_notices', 'my_admin_error_notice' ); 		
	}
	else
	{
		return;
		//echo "<script>alert('Name table not empty')</script>";
	}
	die();
}
	public function pricing_tables_data(){
		global $wpdb;
		$array_table=array();
		$id_name=$_POST['id_name'];
		$chek=0;
		parse_str($_REQUEST['fromdata'],$array_table);	
		$count=count($array_table['row-label-pricing']);
		$query = "SELECT * FROM  {$this->table['pricing_table_detail']} where IDName='{$id_name}' "; 
		$results= $wpdb->get_results($query); 		
		
		if (count($results)>0) {
		 	if (isset($id_name)) {
				$wpdb->delete( 
					$this->table['pricing_table_detail'], 
					array( 'IDName' => $id_name ), array( '%d' ) 
				);				
			}
			else echo "<p class='error'>Error not field</p>";	
		}
			
		if ($count>0) {			
			for ($i=0; $i <$count ; $i++) { 
				if (isset($id_name)) {														
					if ($array_table['chk-description-detail'][$i]=='1') {
						$chek=1;						
					}
					if ($array_table['chk-description-detail'][$i]=='0') {
						$chek=0;
					}
					$wpdb->insert( 
						$this->table['pricing_table_detail'], 
						array( 
							'ProductID' => $array_table['product-id'][$i], 
							'Label' => $array_table['row-label-pricing'][$i],
							'Original'=>$array_table['Original-label-pricing'][$i],
							'Description'=>$chek,
							'IDName'=>$id_name,
						), 
						array('%d','%s','%s','%d','%d') 
					);
				}			
			}
			echo "<p class='success'>Save table success</p>";
		}
		else echo "<p class='error'>Error not field</p>";
		die();	
	}
	public function show_table_pricing()
	{
		global $wpdb;
		$ID_table=$_REQUEST['datas'];
		//echo $ID_table;
		$query_shortcode = "SELECT * FROM  {$this->table['pricing_table_name']} where ID='{$ID_table}' ";
		$results_sd= $wpdb->get_row($query_shortcode);
		if (count($results_sd)>0) {
			//echo "<p style='display:none;' class='id-delete-table-pricing' id='".$ID_table."'></p>";
			echo '<input type="hidden" Name="id-table-pricing" value="'.$results_sd->ID.'" />';
			echo '<p style="display:none;" class="show-table-name-pricing-'.$results_sd->ID.'">'.$results_sd->Name.'</p>';
			echo '<p style="display:none;" class="show-table-shortcode-pricing-'.$results_sd->ID.'">'.$results_sd->ShortCode.'</p>';
		}		
		$query = "SELECT * FROM  {$this->table['pricing_table_detail']} where IDName='{$ID_table}' order by ID ASC "; 
	 	$results= $wpdb->get_results($query);
	 	if (isset($ID_table)) {
	 	 	if (count($results)>0) {
		 		foreach ($results as $value) {
		 			?>
		 			<div class="portlet col-xs-12 ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
						<div class="portlet-header ui-sortable-handle ui-widget-header ui-corner-all"><span class="ui-icon ui-icon-minusthick portlet-toggle ct"></span><?php echo $value->Original; ?></div>
						<div class="portlet-content">
							<label>Row Label</label>
							<input type="text" name="row-label-pricing[]" class="form-control" value="<?php echo $value->Label; ?>">
							<input type="hidden" name="product-id[]" value="<?php echo $value->ProductID; ?>">
							<label>Original Label</label>
							<input type="hidden" name="Original-label-pricing[]" value="<?php echo trim($value->Original); ?>">
							<input type="text" name="Original-label-pricing[]" value="<?php echo trim($value->Original); ?>" class="form-control" disabled>
								<div class="checkbox">
								<a href="#delete" class="delete-product-pricing">Delete</a>
								<label>Include Short Description</label>
								<input type="hidden" name="chk-description-detail[]" id="add-chk" />
								<?php 
								if ($value->Description==1) {
									echo '<input type="checkbox" id="chk-id-pr" checked=""/>';
								} 
								if ($value->Description==0) {
									echo '<input type="checkbox" id="chk-id-pr"/> ';
								}
								?>								
								</div>
						</div>
					</div>
		 			<?php
		 		}
	 		
	 		}
	 		//else return;
	 	} 
	 	else return;
	 	die();
	}
	// function delete table pricing
	public function delete_tables_data(){
		global $wpdb;
		$id_table_name=addslashes($_REQUEST['ID']);		
		//echo $id_table_name;
		if (isset($id_table_name)) {		
			$wpdb->delete( 
							$this->table['pricing_table_detail'], 
							array( 'IDName' => $id_table_name ), array( '%d' ) 
						);
			$wpdb->delete( 
							$this->table['pricing_table_name'], 
							array( 'ID' => $id_table_name ), array( '%d' ) 
						);
			echo "<p class='success'>Delete table success</p>";
		}
		else echo "<p class='error'>Delete not success</p>";
		die();
	}
    /**
     * Prints 'foo' multiple $times.
     *
     * Usage:
     *    <code>do_action( 'plugin_action_demo', 50 );</code>
     *
     * @wp-hook plugin_action_demo
     * @param int $times
     * @return void
     */
    public function print_foo( $times = 1 )
    {
        print str_repeat( ' foo ', (int) $times );
    }
    function my_admin_error_notice() {		
	        echo"<div class='message-notice'> <p>Success</p></div>"; 
	}
	public function add_to_cart_pricing_table(){
		global $woocommerce;		
		$cart = WC()->instance()->cart;
		$id =addslashes($_REQUEST['id_product']);
		$products = new WC_Product($id);		
		$quantity = $_REQUEST['quantity'];				
		$get_cart=$cart->get_cart();			
		$variation_id=0;
		$variation=array();
		$cookie_quantity_name="quantity-".$id;						
	  	$stock = $products->get_total_stock();		  	 
		$cart_id = $cart->generate_cart_id($id,$variation_id, $variation);		
		$view_cart=$woocommerce->cart->get_cart_url();
		echo "<input type='hidden' value='".$view_cart."' class='viewcart-pricing-hidden' />";
		$cart_item_id = $cart->find_product_in_cart($cart_id);	
		if (!is_numeric($quantity)) {
					echo "<p class='error'>Product price or quantity not empty and only number...!</p>";			
					die();
				}	
					
		foreach($get_cart as $cart_item_key => $cart_item)
		{
			
			if($cart_item_key==$item){
				$variation_id = $cart_item['variation_id'];
				$variation  = $cart_item['variation'];
				
			}
		}
		if ($products->price!=null && !empty($quantity) ) {
			if (!empty($stock)) {										
				if($cart_item_id){	
					if (isset($_COOKIE[$cookie_quantity_name])) {
						$number=$_COOKIE[$cookie_quantity_name]+$quantity;						
						if ($number<=$stock) {
							setcookie($cookie_quantity_name,$number, time() + (86400 * 30), "/");
							$cart->set_quantity($cart_item_id,$number);
							echo "<p class='success'>Success <a href='".$woocommerce->cart->get_cart_url()."'>View Cart</a></p>";					
						}
						else
						{
							setcookie($cookie_quantity_name,$_COOKIE[$cookie_quantity_name], time() + (86400 * 30), "/");
							echo "<p class='error'>Add to cart not success!... quantity <= (stock:".$stock.")</p>";
						}							
					}																										   				
				}
				else
				{									   
				    setcookie($cookie_quantity_name, null,time() - (86400 * 30),"/");					
					if ($quantity<=$stock) {
						setcookie($cookie_quantity_name,$quantity, time() + (86400 * 30), "/");
						$cart->add_to_cart( $id, $quantity, $variation_id, $variation  );
						echo "<p class='success'>Success <a href='".$woocommerce->cart->get_cart_url()."'>View Cart</a></p>";
					}
					else
					{						
						echo "<p class='error'>Add to cart not success!... quantity <= (stock:".$stock.")</p>";
					}					
				}								
			}
			else{
				if($cart_item_id){	
					if (isset($_COOKIE[$cookie_quantity_name])) {
						$number=$_COOKIE[$cookie_quantity_name]+$quantity;												
						setcookie($cookie_quantity_name,$number, time() + (86400 * 30), "/");
						$cart->set_quantity($cart_item_id,$number);	
						echo "<p class='success'>Success <a href='".$woocommerce->cart->get_cart_url()."'>View Cart</a></p>";																				
					}																										   				
				}
				else
				{									   
				    setcookie($cookie_quantity_name, null,time() - (86400 * 30),"/");										
					setcookie($cookie_quantity_name,$quantity, time() + (86400 * 30), "/");
					$cart->add_to_cart( $id, $quantity, $variation_id, $variation  );
					echo "<p class='success'>Success <a href='".$woocommerce->cart->get_cart_url()."'>View Cart</a></p>";									
				}
				//echo "<p class='error'>Add to cart not success!</p>";
			}		
				
		}
		else			
			echo "<p class='error'>Product price or quantity not empty and only number...!</p>";			
		die();
	}
	/*create shortcode new table pricing*/
	 public function create_pricing_table_product( $atts ) {
		global $wpdb,$post;	
	    $a = shortcode_atts( array(
	        'name' => '', 
	        'title' =>'',
	    ), $atts );
	     $name=$a['name'];
	     $title=$a['title'];
	     $query = "SELECT * FROM  ".$wpdb->prefix."Woocommerce_pricing_table_name where ShortCode='".$name."' "; 
			$results= $wpdb->get_row($query); 
			$query_detail="SELECT * FROM  ".$wpdb->prefix."Woocommerce_pricing_table_detail where IDName=".$results->ID." order by ID asc ";
			$get_content= $wpdb->get_results($query_detail);	
			ob_start();		
			?>
			<div class='table-pricing-show-product'>	
				<div class="message-addtocart mobile" id="message-mobile">
					<div class="content-popup">
						<h4>Message Box</h4>
						<p class="msg-mobile"></p>
						<div class="link-href">
							<a href="#" class="to-cart">View cart</a>
							<p class="shooping">Continue Shopping</p>
						</div>
					</div>
				</div>		
				<div id="message-addtocart" class='message-addtocart <?php echo $name; ?>'></div>
				<?php if (!empty($title)) {
					echo "<center><h4>".$title."</h4></center>";
				} 
				else
					echo "<center><h4>Pricing Table New Pricing Table</h4></center>";
				?>
				<hr/>
				<div class="content-pricing-table-addtocart">
			<?php			
			foreach ($get_content as $value) {	
					$id=$value->ProductID;		
					$product = new WC_Product($id);
					$order = new WC_Order($id);					
					$post=new WP_Post($order);
					?>					
						<div class="table-pricing-shortcode">				
							<div class="label-show-name">
								<?php 
									if (empty($value->Label)) {
										echo "<h5>".$value->Original."</h5>";
									}
									else
										echo "<h5>".$value->Label."</h5>";									 
								foreach ($post as $des) {
									$arrg=array('[',']','=','"','#','_');
									$description_show=str_replace($arrg,' ',$des->post_excerpt);							
									
									if($value->Description== '1'){ ?>
									<div class="description"><?php echo $description_show; ?></div>
									<?php 
									}
								} ?>
							</div>
							<div class="label-pricing-price">
								<p><?php echo get_woocommerce_currency_symbol(); echo $product->price; ?></p>							
							</div>
							<div class="label-pricing-quantity">
								<p quantity="<?php echo $id; ?>" class="except-quantity"><span>-</span></p>
								<input maxlength="3" id="<?php echo $id; ?>" onblur="if (this.value == '') {this.value = 'QTY';}" onfocus="if (this.value == 'QTY') {this.value = '';}" value="QTY" class="" type="text" name="Quantity-pricing" />
								<p quantity="<?php echo $id; ?>" class="plus-quantity"><span>+</span></p>
							</div>
							<div class="label-pricing-addtocart"><button type="button" value="<?php echo $id; ?>" name="addtocart-pricing" data-name="<?php echo $name; ?>" >Add To Cart</button></div>
						</div>												
					<?php														
					}										
					?>
					</div>
				</div>
					<?php
					$html=ob_get_contents();
					ob_end_clean();	
		return $html;          
	}
}
?>