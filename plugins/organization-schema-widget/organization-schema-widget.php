<?php
/**
 * Plugin Name: 	Organization Schema Widget
 * Plugin URI: 		http://www.rickrduncan.com/wp-plugins/organization-schema-widget
 * Description: 	A widget to display business contact info using HTML5 microdata markup from Schema.org. 
 * Author: 			B3Marketing, LLC
 * Author URI: 		http://www.rickrduncan.com
 * Version: 		1.4.1
 * License: 		GPL v2
 * Usage: 			Activate the plugin, go to Appearance -> Widgets and locate B3M - Organization Schema. Drag widget where 
 *					you want to use it, fill out fields, click on Save and you're done!
 */
 
 /*  Copyright 2013  Rick R. Duncan  (email : b3marketingllc@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//* Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


//* Plugin path
$rvam_osw_path = plugin_basename(__FILE__);


//* Getting plugin ready for translation. oswtd is shorthand for 'Organization Schema Widget Text Domain'
load_plugin_textdomain( 'oswtd', false, basename( dirname( __FILE__ ) ) . '/languages' );


//* Register our default settings
function rvam_osw_register_settings() {
	add_option( 'rvam_use_plugin_stylesheet', '1');
	register_setting( 'default', 'rvam_use_plugin_stylesheet' ); 
} 
add_action( 'admin_init', 'rvam_osw_register_settings' );


//* Function to register and enqueue our stylesheet
function rvam_osw_register_stylesheet() {
	wp_register_style( 'schema-widget-stylesheet', plugins_url( '/css/style.css', __FILE__ ), array(), '20131110', 'all' ); 
	wp_enqueue_style( 'schema-widget-stylesheet' );
}

//* Add http to URL variable if user forgot
function addhttp( $url ) {

	if (empty($url)) return;
    
    if ( !preg_match("~^(?:f|ht)tps?://~i", $url ) ) {
        
        $url = "http://" . $url;
    }
    
    return $url;
    
}


//* Load our scripts ONLY when on the widgets page.  
function rvam_osw_load_scripts($hook) {
	if( $hook != 'widgets.php' ) 
		return;
	wp_register_style( 'schema-widget-admin-stylesheet', plugins_url( '/css/admin-style.css', __FILE__ ), array(), '04222014', 'all' ); 
	wp_enqueue_style( 'schema-widget-admin-stylesheet' );
}
add_action('admin_enqueue_scripts', 'rvam_osw_load_scripts');


class rvam_organization_schema_widget extends WP_Widget {
	
	//* Constructor
	function rvam_organization_schema_widget() {
		parent::WP_Widget(
			false, 
			$name = __( 'B3M - Organization Schema', 'oswtd' ) ,
			array( 'description' => __( 'Display company information for organizations using schema.org', 'oswtd' ), )
		);
	}

	//* Widget form creation
	function form($instance) {
		
		$defaults = array('title' 			=> '', 
					'txt_before' 			=> '', 
					'schema_type' 			=> '',
					'name' 					=> '', 
					'show_name' 			=> '0',
					'lbl_url'				=> 'URL',
					'url' 					=> '', 
					'show_url' 				=> '0',
					'lbl_email'				=> 'Email',
					'email' 				=> '', 
					'show_email' 			=> '0',					
					'description'			=> '',
					'show_description'		=> '0',
					'address'				=> '',
					'show_address'			=> '0',
					'po_box'				=> '',
					'show_po_box'			=> '0',
					'city'					=> '',
					'show_city'				=> '0',
					'state_region'			=> '',
					'show_state_region'		=> '0',					
					'postal_code'			=> '',		
					'show_postal_code'		=> '0',	
					'country'				=> '',
					'show_country'			=> '0',				
					'lbl_phone'				=> 'Phone',
					'phone' 				=> '', 
					'show_phone'			=> '0',
					'lbl_fax'				=> 'Fax',	
					'fax'					=> '',
					'show_fax'				=> '0',
					'open_hours_1'			=> '',
					'open_hours_1_mo'		=> '',
					'open_hours_1_tu'		=> '',
					'open_hours_1_we'		=> '',
					'open_hours_1_th'		=> '',
					'open_hours_1_fr'		=> '',																				
					'open_hours_1_open'		=> '',
					'open_hours_1_close'	=> '',					
					'open_hours_2'			=> '',
					'open_hours_2_sa'		=> '',					
					'open_hours_2_open'		=> '',
					'open_hours_2_close'	=> '',									
					'open_hours_3'			=> '',
					'open_hours_3_su'		=> '0',					
					'open_hours_3_open'		=> '',
					'open_hours_3_close'	=> '',					
					'display_hours'			=> '0',										
					'txt_after' 			=> ''
					);
					 
		$instance = wp_parse_args((array) $instance, $defaults);
		
		$title 					= esc_attr($instance['title']);	
		$txt_before 			= esc_attr($instance['txt_before']);
		$schema_type 			= esc_attr($instance['schema_type']);
		$name 					= esc_attr($instance['name']);
		$show_name 				= esc_attr($instance['show_name']);
		$lbl_url 				= esc_attr($instance['lbl_url']);
		$url 					= esc_attr($instance['url']);
		$show_url 				= esc_attr($instance['show_url']);
		$lbl_email 				= esc_attr($instance['lbl_email']);
		$email 					= esc_attr($instance['email']);
		$show_email 			= esc_attr($instance['show_email']);		
		$description 			= esc_attr($instance['description']);
		$show_description 		= esc_attr($instance['show_description']);
		$address 				= esc_attr($instance['address']);
		$show_address 			= esc_attr($instance['show_address']);
		$po_box 				= esc_attr($instance['po_box']);
		$show_po_box 			= esc_attr($instance['show_po_box']);	
		$city 					= esc_attr($instance['city']);
		$show_city 				= esc_attr($instance['show_city']);
		$state_region 			= esc_attr($instance['state_region']);
		$show_state_region 		= esc_attr($instance['show_state_region']);	
		$postal_code 			= esc_attr($instance['postal_code']);
		$show_postal_code 		= esc_attr($instance['show_postal_code']);		
		$country 				= esc_attr($instance['country']);
		$show_country 			= esc_attr($instance['show_country']);
		$lbl_phone 				= esc_attr($instance['lbl_phone']);			
		$phone 					= esc_attr($instance['phone']);		
		$show_phone 			= esc_attr($instance['show_phone']);		
		$lbl_fax 				= esc_attr($instance['lbl_fax']);
		$fax 					= esc_attr($instance['fax']);		
		$show_fax				= esc_attr($instance['show_fax']);
		$open_hours_1			= esc_attr($instance['open_hours_1']);
		$open_hours_1_mo		= esc_attr($instance['open_hours_1_mo']);
		$open_hours_1_tu		= esc_attr($instance['open_hours_1_tu']);
		$open_hours_1_we		= esc_attr($instance['open_hours_1_we']);
		$open_hours_1_th		= esc_attr($instance['open_hours_1_th']);
		$open_hours_1_fr		= esc_attr($instance['open_hours_1_fr']);
		$open_hours_1_open		= esc_attr($instance['open_hours_1_open']);
		$open_hours_1_close		= esc_attr($instance['open_hours_1_close']);			
		$open_hours_2			= esc_attr($instance['open_hours_2']);
		$open_hours_2_sa		= esc_attr($instance['open_hours_2_sa']);		
		$open_hours_2_open		= esc_attr($instance['open_hours_2_open']);
		$open_hours_2_close		= esc_attr($instance['open_hours_2_close']);		
		$open_hours_3			= esc_attr($instance['open_hours_3']);
		$open_hours_3_su		= esc_attr($instance['open_hours_3_su']);		
		$open_hours_3_open		= esc_attr($instance['open_hours_3_open']);
		$open_hours_3_close		= esc_attr($instance['open_hours_3_close']);				
		$display_hours			= esc_attr($instance['display_hours']);						
		$txt_after 				= esc_attr($instance['txt_after']);				
			
		?>
		<p><a href="http://www.schema.org/Organization" target="_blank">schema.org/Organization</a></p>
		<p><?php _e( 'All fields are optional.<br /> Fields left empty will not be shown. If you choose "Do not display…" structured data for the field will be visible to bots but not to users.', 'oswtd' )?></p>
	
		<p><!-- Start widget title -->
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p><!-- Custom HTML written out BEFORE the Schema data -->
		<label for="<?php echo $this->get_field_id('txt_before'); ?>"><?php _e( 'Text/HTML Before:', 'oswtd' ); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('txt_before'); ?>" name="<?php echo $this->get_field_name('txt_before'); ?>"><?php echo $instance['txt_before']; ?></textarea>
		</p>
		
		<?php
		//* NEW: Complete list of Business Types (since version 1.3.0)
		//$options = array('General', 'Local Business', 'Corporation', 'School', 'Government', 'NGO', 'Performing Group', 'Sports Team');
		$schema_types = array(
			'Organization'					=> 'Organization',
			'Airline'						=> '-- Airline',
			'Corporation'					=> '-- Corporation',	
			'EducationalOrganization'		=> '-- Educational',
			'GovernmentOrganization'		=> '-- Government',
			'LocalBusiness'               	=> '-- Local Business',
			'NGO'							=> '-- NGO',
			'PerformingGroup'				=> '-- Performing Group',
			'SportsTeam'					=> '-- Sports Team',
			'AnimalShelter'               	=> 'Animal Shelter',
			'AutomotiveBusiness'          	=> 'Automotive Business',
			'AutoBodyShop'                	=> '-- Auto Body Shop',
			'AutoDealer'                  	=> '-- Auto Dealer',
			'AutoPartsStore'              	=> '-- Auto Parts Store',
			'AutoRental'                  	=> '-- Auto Rental',
			'AutoRepair'                  	=> '-- Auto Repair',
			'AutoWash'                    	=> '-- Auto Wash',
			'GasStation'                  	=> '-- Gas Station',
			'MotorcycleDealer'            	=> '-- Motorcycle Dealer',
			'MotorcycleRepair'            	=> '-- Motorcycle Repair',
			'ChildCare'                   	=> 'Child Care',
			'DryCleaningOrLaundry'        	=> 'Dry Cleaning Or Laundry',
			'EmergencyService'            	=> 'Emergency Service',
			'FireStation'                 	=> '-- Fire Station',
			'Hospital'                    	=> '-- Hospital',
			'PoliceStation'               	=> '-- Police Station',			
			'EmploymentAgency'            	=> 'Employment Agency',
			'EntertainmentBusiness'       	=> 'Entertainment Business',
			'AdultEntertainment'          	=> '-- Adult Entertainment',
			'AmusementPark'               	=> '-- Amusement Park',
			'ArtGallery'                  	=> '-- Art Gallery',
			'Casino'                      	=> '-- Casino',
			'ComedyClub'                  	=> '-- Comedy Club',
			'MovieTheater'                	=> '-- Movie Theater',
			'NightClub'                   	=> '-- Night Club',
			'FinancialService'            	=> 'Financial Service',
			'AccountingService'           	=> '-- Accounting Service',
			'AutomatedTeller'             	=> '-- Automated Teller',
			'BankOrCreditUnion'           	=> '-- Bank Or CreditUnion',
			'InsuranceAgency'             	=> '-- Insurance Agency',
			'FoodEstablishment'           	=> 'Food Establishment',
			'Bakery'                      	=> '-- Bakery',
			'BarOrPub'                    	=> '-- Bar Or Pub',
			'Brewery'                     	=> '-- Brewery',
			'CafeOrCoffeeShop'            	=> '-- Cafe Or Coffee Shop',
			'FastFoodRestaurant'          	=> '-- Fast Food Restaurant',
			'IceCreamShop'                	=> '-- Ice Cream Shop',
			'Restaurant'                  	=> '-- Restaurant',
			'Winery'                      	=> '-- Winery',
			'GovernmentOffice'            	=> 'Government Office',
			'PostOffice'                  	=> '-- Post Office',
			'HealthAndBeautyBusiness'     	=> 'Health And Beauty Business',
			'BeautySalon'                 	=> '-- Beauty Salon',
			'DaySpa'                      	=> '-- Day Spa',
			'HairSalon'                   	=> '-- Hair Salon',
			'HealthClub'                  	=> '-- Health Club',
			'NailSalon'                   	=> '-- Nail Salon',
			'TattooParlor'                	=> '-- Tattoo Parlor',
			'HomeAndConstructionBusiness' 	=> 'Home And Construction Business',
			'Electrician'                 	=> '-- Electrician',
			'GeneralContractor'           	=> '-- General Contractor',
			'HVACBusiness'                	=> '-- HVAC Business',
			'HousePainter'                	=> '-- House Painter',
			'Locksmith'                   	=> '-- Locksmith',
			'MovingCompany'               	=> '-- Moving Company',
			'Plumber'                     	=> '-- Plumber',
			'RoofingContractor'           	=> '-- Roofing Contractor',
			'InternetCafe'                	=> 'Internet Cafe',
			'Library'                     	=> 'Library',
			'LodgingBusiness'             	=> 'Lodging Business',
			'BedAndBreakfast'             	=> '-- Bed And Breakfast',
			'Hostel'                      	=> '-- Hostel',
			'Hotel'                       	=> '-- Hotel',
			'Motel'                       	=> '-- Motel',
			'MedicalOrganization'         	=> 'Medical Organization',
			'Dentist'                     	=> '-- Dentist',
			'DiagnosticLab'               	=> '-- Diagnostic Lab',
			'Hospital'                    	=> '-- Hospital',
			'MedicalClinic'               	=> '-- Medical Clinic',
			'Optician'                    	=> '-- Optician',
			'Pharmacy'                    	=> '-- Pharmacy',
			'Physician'                   	=> '-- Physician',
			'VeterinaryCare'              	=> '-- Veterinary Care',								
			'ProfessionalService'         	=> 'Professional Service',
			'AccountingService'           	=> '-- Accounting Service',
			'Attorney'                    	=> '-- Attorney',
			'Dentist'                     	=> '-- Dentist',
			'Electrician'                 	=> '-- Electrician',
			'GeneralContractor'           	=> '-- General Contractor',
			'HousePainter'                	=> '-- House Painter',
			'Locksmith'                   	=> '-- Locksmith',
			'Notary'                      	=> '-- Notary',
			'Plumber'                     	=> '-- Plumber',
			'RoofingContractor'           	=> '-- Roofing Contractor',
			'RadioStation'                	=> 'Radio Station',
			'RealEstateAgent'             	=> 'Real Estate Agent',
			'RecyclingCenter'             	=> 'Recycling Center',
			'SelfStorage'                 	=> 'Self Storage',
			'ShoppingCenter'              	=> 'Shopping Center',
			'SportsActivityLocation'      	=> 'Sports Activity Location',
			'BowlingAlley'                	=> '-- Bowling Alley',
			'ExerciseGym'                 	=> '-- Exercise Gym',
			'GolfCourse'                  	=> '-- Golf Course',
			'HealthClub'                  	=> '-- Health Club',
			'PublicSwimmingPool'          	=> '-- Public Swimming Pool',
			'SkiResort'                   	=> '-- Ski Resort',
			'SportsClub'                  	=> '-- Sports Club',
			'StadiumOrArena'              	=> '-- Stadium Or Arena',
			'TennisComplex'               	=> '-- Tennis Complex',
			'Store'                       	=> 'Store',
			'AutoPartsStore'              	=> '-- Auto Parts Store',
			'BikeStore'                   	=> '-- Bike Store',
			'BookStore'                   	=> '-- Book Store',
			'ClothingStore'               	=> '-- Clothing Store',
			'ComputerStore'               	=> '-- Computer Store',
			'ConvenienceStore'            	=> '-- Convenience Store',
			'DepartmentStore'             	=> '-- Department Store',
			'ElectronicsStore'            	=> '-- Electronics Store',
			'Florist'                     	=> '-- Florist',
			'FurnitureStore'              	=> '-- Furniture Store',
			'GardenStore'                 	=> '-- Garden Store',
			'GroceryStore'                	=> '-- Grocery Store',
			'HardwareStore'               	=> '-- Hardware Store',
			'HobbyShop'                   	=> '-- Hobby Shop',
			'HomeGoodsStore'              	=> '-- Home Goods Store',
			'JewelryStore'                	=> '-- Jewelry Store',
			'LiquorStore'                 	=> '-- Liquor Store',
			'MensClothingStore'           	=> '-- Mens Clothing Store',
			'MobilePhoneStore'            	=> '-- Mobile Phone Store',
			'MovieRentalStore'            	=> '-- Movie Rental Store',
			'MusicStore'                  	=> '-- Music Store',
			'OfficeEquipmentStore'        	=> '-- Office Equipment Store',
			'OutletStore'                 	=> '-- Outlet Store',
			'PawnShop'                    	=> '-- Pawn Shop',
			'PetStore'                    	=> '-- Pet Store',
			'ShoeStore'                   	=> '-- Shoe Store',
			'SportingGoodsStore'          	=> '-- Sporting Goods Store',
			'TireShop'                    	=> '-- Tire Shop',
			'ToyStore'                    	=> '-- Toy Store',
			'WholesaleStore'              	=> '-- Wholesale Store',
			'TelevisionStation'           	=> 'Television Station',
			'TouristInformationCenter'    	=> 'Tourist Information Center',
			'TravelAgency'                	=> 'Travel Agency',
		);		
		?>

		<p><!-- Organization schema type -->
		<label for="<?php echo $this->get_field_id('schema_type'); ?>"><?php _e('Organization Schema Type', 'oswtd'); ?></label>
		<select id="<?php echo $this->get_field_id('schema_type'); ?>" name="<?php echo $this->get_field_name('schema_type'); ?>" size="1">
			<?php foreach ( $schema_types as $key => $value ) {
				echo '<option value="' . $key . '"' . ($key == $schema_type ? ' selected' : '') . '>' . $value . '</option>';
			} ?>
		</select>		
		</p>

		<p><!-- Name -->
		<label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo $instance['name']; ?>" />
		<input class="osw-checkbox" id="<?php echo $this->get_field_id('show_name'); ?>" name="<?php echo $this->get_field_name('show_name'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_name']); ?> /> <?php _e( 'Do not display name', 'oswtd' ); ?>
		</p>

		<p><!-- Description -->
		<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $instance['description']; ?>" />
		<input id="<?php echo $this->get_field_id('show_description'); ?>" name="<?php echo $this->get_field_name('show_description'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_description']); ?> /> <?php _e( 'Do not display Description', 'oswtd' ); ?>
		</p>
		
		<p><!-- Address -->
		<label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $instance['address']; ?>" />
		<input id="<?php echo $this->get_field_id('show_address'); ?>" name="<?php echo $this->get_field_name('show_address'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_address']); ?> /> <?php _e( 'Do not display Address', 'oswtd' ); ?>
		</p>
		
		<p><!-- P.O. BOX -->
		<label for="<?php echo $this->get_field_id('po_box'); ?>"><?php _e('P.O. BOX', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('po_box'); ?>" name="<?php echo $this->get_field_name('po_box'); ?>" type="text" value="<?php echo $instance['po_box']; ?>" />
		<input id="<?php echo $this->get_field_id('show_po_box'); ?>" name="<?php echo $this->get_field_name('show_po_box'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_po_box']); ?> /> <?php _e( 'Do not display P.O. BOX', 'oswtd' ); ?>
		</p>
		
		<p><!-- City -->
		<label for="<?php echo $this->get_field_id('city'); ?>"><?php _e('City', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php echo $instance['city']; ?>" />
		<input id="<?php echo $this->get_field_id('show_city'); ?>" name="<?php echo $this->get_field_name('show_city'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_city']); ?> /> <?php _e( 'Do not display City', 'oswtd' ); ?>
		</p>
		
		<p><!-- State / Region -->
		<label for="<?php echo $this->get_field_id('state_region'); ?>"><?php _e('State/Region', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('state_region'); ?>" name="<?php echo $this->get_field_name('state_region'); ?>" type="text" value="<?php echo $instance['state_region']; ?>" />
		<input id="<?php echo $this->get_field_id('show_state_region'); ?>" name="<?php echo $this->get_field_name('show_state_region'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_state_region']); ?> /> <?php _e( 'Do not display State/Region', 'oswtd' ); ?>
		</p>
		
		<p><!-- Postal Code -->
		<label for="<?php echo $this->get_field_id('postal_code'); ?>"><?php _e('Postal Code', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('postal_code'); ?>" name="<?php echo $this->get_field_name('postal_code'); ?>" type="text" value="<?php echo $instance['postal_code']; ?>" />
		<input id="<?php echo $this->get_field_id('show_postal_code'); ?>" name="<?php echo $this->get_field_name('show_postal_code'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_postal_code']); ?> /> <?php _e( 'Do not display Postal Code', 'oswtd' ); ?>
		</p>

		<p><!-- Country -->
		<label for="<?php echo $this->get_field_id('country'); ?>"><?php _e('Country', 'oswtd'); ?> (<a href="http://schema.org/addressCountry" target="_blank">help</a>)</label>
		<input class="widefat" id="<?php echo $this->get_field_id('country'); ?>" name="<?php echo $this->get_field_name('country'); ?>" type="text" value="<?php echo $instance['country']; ?>" />
		<input id="<?php echo $this->get_field_id('show_country'); ?>" name="<?php echo $this->get_field_name('show_country'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_country']); ?> /> <?php _e( 'Do not display Country', 'oswtd' ); ?>
		</p>

		<p><!-- Label for Phone Number -->
		<label for="<?php echo $this->get_field_id('lbl_phone'); ?>"><?php _e('Label for Phone Number', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('lbl_phone'); ?>" name="<?php echo $this->get_field_name('lbl_phone'); ?>" type="text" value="<?php echo $instance['lbl_phone']; ?>" /><?php _e( '(E.g. - Phone, Telephone, Mobile, etc.)', 'oswtd' ); ?>
		</p>	
		
		<p><!-- Phone -->
		<label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone Number', 'oswtd'); ?></label>		
		<input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo $instance['phone']; ?>" />
		<input id="<?php echo $this->get_field_id('show_phone'); ?>" name="<?php echo $this->get_field_name('show_phone'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_phone']); ?> /> <?php _e( 'Do not display Phone Number', 'oswtd' ); ?>
		</p>
		
		<p><!-- Label for Fax Number -->
		<label for="<?php echo $this->get_field_id('lbl_fax'); ?>"><?php _e('Label for Fax Number', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('lbl_fax'); ?>" name="<?php echo $this->get_field_name('lbl_fax'); ?>" type="text" value="<?php echo $instance['lbl_fax']; ?>" /><?php _e( '(E.g. - Fax, Facsimile, etc.)', 'oswtd' ); ?>
		</p>		
		
		<p><!-- Fax -->
		<label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax Number', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo $instance['fax']; ?>" />
		<input id="<?php echo $this->get_field_id('show_fax'); ?>" name="<?php echo $this->get_field_name('show_fax'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_fax']); ?> /> <?php _e( 'Do not display Fax Number', 'oswtd' ); ?>
		</p>

		<p><!-- Label for Email -->
		<label for="<?php echo $this->get_field_id('lbl_email'); ?>"><?php _e('Label for Email', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('lbl_email'); ?>" name="<?php echo $this->get_field_name('lbl_email'); ?>" type="text" value="<?php echo $instance['lbl_email']; ?>" /><?php _e( '(E.g. - Email, e-mail, etc.)', 'oswtd' ); ?>
		</p>

		<p><!-- Email -->
		<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $instance['email']; ?>" />
		<input id="<?php echo $this->get_field_id('show_email'); ?>" name="<?php echo $this->get_field_name('show_email'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_email']); ?> /> <?php _e( 'Do not display Email', 'oswtd' ); ?>
		</p>

		<p><!-- Label for URL -->
		<label for="<?php echo $this->get_field_id('lbl_url'); ?>"><?php _e('Label for URL', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('lbl_url'); ?>" name="<?php echo $this->get_field_name('lbl_url'); ?>" type="text" value="<?php echo $instance['lbl_url']; ?>" /><?php _e( '(E.g. - URL, Url, Website, etc.)', 'oswtd' ); ?>
		</p>

		<p><!-- URL -->
		<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL', 'oswtd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $instance['url']; ?>" />
		<input id="<?php echo $this->get_field_id('show_url'); ?>" name="<?php echo $this->get_field_name('show_url'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['show_url']); ?> /> <?php _e( 'Do not display URL', 'oswtd' ); ?>
		</p>
		
		<p>
		<span class="alert">Must use 24 hour time!</span><br /> 
		<em><strong>(3pm is specified as 15:00)</strong></em>
		</p>
		<p><!-- Open Hours 1 Mon-Fri -->
		<label for="<?php echo $this->get_field_id('open_hours_1'); ?>"><?php _e( 'Business Hours (Weekdays):', 'oswtd' ); ?></label>
		<br />
		M <input id="<?php echo $this->get_field_id('open_hours_1_mo'); ?>" name="<?php echo $this->get_field_name('open_hours_1_mo'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_1_mo ); ?> />
		T <input id="<?php echo $this->get_field_id('open_hours_1_tu'); ?>" name="<?php echo $this->get_field_name('open_hours_1_tu'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_1_tu ); ?> />
		W <input id="<?php echo $this->get_field_id('open_hours_1_we'); ?>" name="<?php echo $this->get_field_name('open_hours_1_we'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_1_we ); ?> />
		T <input id="<?php echo $this->get_field_id('open_hours_1_th'); ?>" name="<?php echo $this->get_field_name('open_hours_1_th'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_1_th ); ?> />
		F <input id="<?php echo $this->get_field_id('open_hours_1_fr'); ?>" name="<?php echo $this->get_field_name('open_hours_1_fr'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_1_fr ); ?> />					
		<br />
		Open: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_1_open'); ?>" name="<?php echo $this->get_field_name('open_hours_1_open'); ?>" type="text" value="<?php echo $open_hours_1_open; ?>" />
		Close: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_1_close'); ?>" name="<?php echo $this->get_field_name('open_hours_1_close'); ?>" type="text" value="<?php echo $open_hours_1_close; ?>" />			
		</p>

		<p><!-- Open Hours 2 Sat -->
		<label for="<?php echo $this->get_field_id('open_hours_2'); ?>"><?php _e( 'Business Hours (Saturday):', 'oswtd' ); ?></label>
		<br />
		Sat <input id="<?php echo $this->get_field_id('open_hours_2_sa'); ?>" name="<?php echo $this->get_field_name('open_hours_2_sa'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_2_sa ); ?> />
		<br />
		Open: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_2_open'); ?>" name="<?php echo $this->get_field_name('open_hours_2_open'); ?>" type="text" value="<?php echo $open_hours_2_open; ?>" />
		Close: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_2_close'); ?>" name="<?php echo $this->get_field_name('open_hours_2_close'); ?>" type="text" value="<?php echo $open_hours_2_close; ?>" />			
		</p>		
		
		<p><!-- Open Hours 3 Sun -->
		<label for="<?php echo $this->get_field_id('open_hours_3'); ?>"><?php _e( 'Business Hours (Sunday):', 'oswtd' ); ?></label>
		<br />
		Sun <input id="<?php echo $this->get_field_id('open_hours_3_su'); ?>" name="<?php echo $this->get_field_name('open_hours_3_su'); ?>" type="checkbox" value="1" <?php checked( '1', $open_hours_3_su ); ?> />
		<br />
		Open: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_3_open'); ?>" name="<?php echo $this->get_field_name('open_hours_3_open'); ?>" type="text" value="<?php echo $open_hours_3_open; ?>" />
		Close: <input class="widefat" style="width:60px;" id="<?php echo $this->get_field_id('open_hours_3_close'); ?>" name="<?php echo $this->get_field_name('open_hours_3_close'); ?>" type="text" value="<?php echo $open_hours_3_close; ?>" />			
		</p>			

		<p>
		<input id="<?php echo $this->get_field_id('display_hours'); ?>" name="<?php echo $this->get_field_name('display_hours'); ?>" type="checkbox" value="1" <?php checked( '1', $display_hours ); ?> /> <?php _e( 'Do not display Hours', 'oswtd' ); ?><br />
		</p>

		<p><!-- Custom HTML written out AFTER the Schema data -->
			<label for="<?php echo $this->get_field_id('txt_after'); ?>"><?php _e( 'Text/HTML After:', 'oswtd' ); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('txt_after'); ?>" name="<?php echo $this->get_field_name('txt_after'); ?>"><?php echo $instance['txt_after']; ?></textarea>		
		</p>
		<?php
	}

	//* Update widget
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		//* Fields
		$instance['title'] 					= strip_tags($new_instance['title']);
		$instance['txt_before'] 			= $new_instance['txt_before'];
		$instance['schema_type'] 			= $new_instance['schema_type'];
		$instance['name'] 					= strip_tags($new_instance['name']);
		$instance['show_name'] 				= $new_instance['show_name'];
		$instance['lbl_url']				= strip_tags($new_instance['lbl_url']);
		
		
		//* test to see if user included a prefix of http://
		$instance['url'] 					= addhttp(strip_tags($new_instance['url']));
		
		
		$instance['show_url'] 				= $new_instance['show_url'];		
		$instance['lbl_email']				= strip_tags($new_instance['lbl_email']);
		$instance['email'] 					= strip_tags($new_instance['email']);
		$instance['show_email'] 			= $new_instance['show_email'];	
		$instance['description'] 			= strip_tags($new_instance['description']);
		$instance['show_description'] 		= $new_instance['show_description'];		
		$instance['address'] 				= strip_tags($new_instance['address']);
		$instance['show_address'] 			= $new_instance['show_address'];
		$instance['po_box'] 				= strip_tags($new_instance['po_box']);
		$instance['show_po_box'] 			= $new_instance['show_po_box'];	
		$instance['city'] 					= strip_tags($new_instance['city']);
		$instance['show_city'] 				= $new_instance['show_city'];
		$instance['state_region'] 			= strip_tags($new_instance['state_region']);
		$instance['show_state_region'] 		= $new_instance['show_state_region'];
		$instance['postal_code'] 			= strip_tags($new_instance['postal_code']);
		$instance['show_postal_code'] 		= $new_instance['show_postal_code'];
		$instance['country'] 				= strip_tags($new_instance['country']);
		$instance['show_country'] 			= $new_instance['show_country'];
		$instance['lbl_phone']				= strip_tags($new_instance['lbl_phone']);
		$instance['phone'] 					= strip_tags($new_instance['phone']);
		$instance['show_phone'] 			= $new_instance['show_phone'];
		$instance['lbl_fax'] 				= strip_tags($new_instance['lbl_fax']);		
		$instance['fax'] 					= strip_tags($new_instance['fax']);
		$instance['show_fax'] 				= $new_instance['show_fax'];
		$instance['open_hours_1'] 			= strip_tags($new_instance['open_hours_1']);
		$instance['open_hours_1_mo'] 		= strip_tags($new_instance['open_hours_1_mo']);		
		$instance['open_hours_1_tu'] 		= strip_tags($new_instance['open_hours_1_tu']);	
		$instance['open_hours_1_we'] 		= strip_tags($new_instance['open_hours_1_we']);	
		$instance['open_hours_1_th'] 		= strip_tags($new_instance['open_hours_1_th']);	
		$instance['open_hours_1_fr'] 		= strip_tags($new_instance['open_hours_1_fr']);					
		$instance['open_hours_1_open'] 		= strip_tags(date("H:i",strtotime($new_instance['open_hours_1_open'])));		
		$instance['open_hours_1_close'] 	= strip_tags(date("H:i",strtotime($new_instance['open_hours_1_close'])));				
		$instance['open_hours_2'] 			= strip_tags($new_instance['open_hours_2']);
		$instance['open_hours_2_sa'] 		= strip_tags($new_instance['open_hours_2_sa']);
		$instance['open_hours_2_open'] 		= strip_tags(date("H:i",strtotime($new_instance['open_hours_2_open'])));		
		$instance['open_hours_2_close'] 	= strip_tags(date("H:i",strtotime($new_instance['open_hours_2_close'])));				
		$instance['open_hours_3'] 			= strip_tags($new_instance['open_hours_3']);
		$instance['open_hours_3_su'] 		= strip_tags($new_instance['open_hours_3_su']);		
		$instance['open_hours_3_open'] 		= strip_tags(date("H:i",strtotime($new_instance['open_hours_3_open'])));		
		$instance['open_hours_3_close'] 	= strip_tags(date("H:i",strtotime($new_instance['open_hours_3_close'])));
		$instance['display_hours'] 			= $new_instance['display_hours'];				
		$instance['txt_after'] 				= $new_instance['txt_after'];

		return $instance;
	}
	
	//* Display widget
	function widget($args, $instance) {
		extract( $args );
		
		//* Only include our stylesheet when user has used our plugin
		//* Also, we only use the stylesheet as long as user didn't un-check the option		
		$rvam_use_plugin_stylesheet = get_option('rvam_use_plugin_stylesheet');
		if( !empty($rvam_use_plugin_stylesheet) && $rvam_use_plugin_stylesheet == 1 ) {
			rvam_osw_register_stylesheet();
		}
		
		echo $before_widget;
		
		//* these are the widget options
		$title 					= apply_filters('widget_title', $instance['title']);
		$txt_before 			= $instance['txt_before'];		
		$schema_type 			= $instance['schema_type'];
		$name 					= $instance['name'];
		$show_name 				= $instance['show_name'];
		$lbl_url 				= isset($instance['lbl_url']) ? $instance['lbl_url'] : '';
		$url 					= $instance['url'];
		$show_url 				= $instance['show_url'];
		$lbl_email 				= isset($instance['lbl_email']) ? $instance['lbl_email'] : '';		
		$email 					= $instance['email'];
		$show_email 			= $instance['show_email'];
		$description 			= $instance['description'];
		$show_description 		= $instance['show_description'];		
		$address 				= $instance['address'];
		$show_address 			= $instance['show_address'];	
		$po_box 				= $instance['po_box'];			
		$show_po_box 			= $instance['show_po_box'];			
		$city 					= $instance['city'];			
		$show_city 				= $instance['show_city'];			
		$state_region			= $instance['state_region'];			
		$show_state_region 		= $instance['show_state_region'];		
		$postal_code			= $instance['postal_code'];			
		$show_postal_code 		= $instance['show_postal_code'];	
		$country				= $instance['country'];			
		$show_country 			= $instance['show_country'];	
		$lbl_phone				= $instance['lbl_phone'];
		$phone 					= $instance['phone'];
		$show_phone				= $instance['show_phone'];
		$lbl_fax				= $instance['lbl_fax'];
		$fax 					= $instance['fax'];
		$show_fax 				= $instance['show_fax'];	
		$open_hours_1			= $instance['open_hours_1'];
		$open_hours_1_mo		= $instance['open_hours_1_mo'];
		$open_hours_1_tu		= $instance['open_hours_1_tu'];
		$open_hours_1_we		= $instance['open_hours_1_we'];
		$open_hours_1_th		= $instance['open_hours_1_th'];
		$open_hours_1_fr		= $instance['open_hours_1_fr'];
		$open_hours_1_open 		= $instance['open_hours_1_open'];
		$open_hours_1_close 	= $instance['open_hours_1_close'];		
		$open_hours_2			= $instance['open_hours_2'];
		$open_hours_2_sa		= $instance['open_hours_2_sa'];
		$open_hours_2_open 		= $instance['open_hours_2_open'];
		$open_hours_2_close 	= $instance['open_hours_2_close'];	
		$open_hours_3			= $instance['open_hours_3'];
		$open_hours_3_su		= $instance['open_hours_3_su'];		
		$open_hours_3_open 		= $instance['open_hours_3_open'];
		$open_hours_3_close 	= $instance['open_hours_3_close'];					
		$display_hours			= $instance['display_hours'];								
		$txt_after 				= $instance['txt_after'];

		//* Display the widget
		echo '<div class="widget-text rvam-osw-wrapper">';

		//* Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		
		//* Determine which schema type to create
		if ( $schema_type ) {
			echo '<div itemscope itemtype="http://schema.org/' . $schema_type . '">';
		} 
		else {
			echo '<div itemscope itemtype="http://schema.org/Organization">';
		}
		
		//* If user enter data into the 'before' text box, display it now
		if ( $txt_before != '' ) {
			echo '<span class="osw-data before">'.$txt_before.'</span>';
		}
		
		if ( $name != '' ) {
			if ( !$show_name ) {
				echo '<span itemprop="name" class="osw-data name"><span class="osw-label name">' . $name . '</span></span>';
			} 
			else {
				echo '<meta itemprop="name" content="' . $name . '" />';
			}
		}
		
		if ( $description != '' ) {
			if ( !$show_description ) {
				echo '<span itemprop="description" class="osw-data description">' . $description . '</span>';
			} 
			else {
				echo '<meta itemprop="description" content="' . $description . '" />';
			}
		}
		
		//* If any part of address is filled out, add PostalAddress schema
		if ( $address != '' || $po_box != '' || $city != '' || $state_region != '' || $postal_code != '' || $country != '' ) {
			
			//* String variable to hold our PostalAddress
			$postal_address = '';
			
			$postal_address .= '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
			
			if ( $address != '' ) {
				if ( !$show_address ) {
					$postal_address .= '<span itemprop="streetAddress" class="osw-data address">' . $address . '</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="streetAddress" content="' . $address . '" />';
				}			
			}
			
			if ( $po_box != '' ) {
				if ( !$show_po_box ) {
					$postal_address .= '<span class="osw-label po_box">PO Box </span><span itemprop="postOfficeBoxNumber" class="osw-data po_box">' . $po_box . '</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="postOfficeBoxNumber" content="' . $po_box . '" />';
				}			
			}
	
			if ( $city != '' ) {
				if ( !$show_city ) {
					$postal_address .= '<span itemprop="addressLocality" class="osw-data city">' . $city . '</span>';
					$postal_address .= '<span class="osw-data city_comma">,</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="addressLocality" content="' . $city . '" />';
				}			
			}

			if ( $state_region != '' ) {
				if ( !$show_state_region ) {
					$postal_address .= '<span itemprop="addressRegion" class="osw-data state_region">' . $state_region . '</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="addressRegion" content="' . $state_region . '" />';
				}		
			}

			if ( $postal_code != '' ) {
				if ( !$show_postal_code ) {
					$postal_address .= '<span itemprop="postalCode" class="osw-data postal_code">' . $postal_code . '</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="postalCode" content="' . $postal_code . '" />';
				}			
			}
	
			if ( $country != '' ) {
				if ( !$show_country ) {
					$postal_address .= '<span itemprop="addressCountry" class="osw-data country">' . $country . '</span>';
				} 
				else {
					$postal_address .= '<meta itemprop="addressCountry" content="' . $country . '" />';
				}			
			}
				
			$postal_address .= '</div>';
			
			//* echo out the PostalAddress
			echo $postal_address;
			
			
		}
		
		if ( $phone != '' ) {
			if ( !$show_phone ) {
				echo '<span class="osw-label phone">' . $lbl_phone . '</span><span itemprop="telephone" class="osw-data phone">' . $phone . '</span>';
			} 
			else {
				echo '<meta itemprop="telephone" content="' . $phone . '" />';
			}
		}

		if ( $fax != '' ) {
			if ( !$show_fax ) {
				echo '<span class="osw-label fax">' . $lbl_fax . '</span><span itemprop="faxNumber" class="osw-data fax">' . $fax . '</span>';
			} 
			else {
				echo '<meta itemprop="faxNumber" content="' . $fax . '" />';
			}
		}

		if ( $email != '' ) {
			if ( !$show_email ) {
				echo '<span class="osw-label email">' . $lbl_email . '</span><a itemprop="email" class="osw-data email" href="mailto:' . $email . '">' . $email . '</a>';
			} 
			else {
				echo '<meta itemprop="email" content="' . $email . '" />';
			}
		}

		if ( $url != '' ) {
		
			//* create the display version of URL after removing the protocol
			$display_url = $url;
			$display_url = preg_replace('#^https?://#', '', $display_url);
		
			if ( !$show_url ) {
				echo '<span class="osw-label url">' . $lbl_url . '</span><a itemprop="url" class="osw-data url" href="' . $url . '">' . $display_url . '</a>';
			} 
			else {
				echo '<meta itemprop="url" content="' . $url . '" />';
			}		
		}
		
		
		//* Variable to know if any day of week has been checked
		$dow_checked = 'false';
		
		
		//* Display OpeningHours
		if ( $open_hours_1_mo || $open_hours_1_tu || $open_hours_1_we || $open_hours_1_th || $open_hours_1_fr ) {		
			// string variable to hold day of week items selected (Monday - Friday)
			$open_hours_dow = '';
			if ($open_hours_1_mo) $open_hours_dow .= 'Mo, ';
			if ($open_hours_1_tu) $open_hours_dow .= 'Tu, ';
			if ($open_hours_1_we) $open_hours_dow .= 'We, ';
			if ($open_hours_1_th) $open_hours_dow .= 'Th, ';
			if ($open_hours_1_fr) $open_hours_dow .= 'Fr';
			$dow_checked = 'true';
			echo '<meta itemprop="openingHours" content="' . rtrim(str_replace(' ', '', $open_hours_dow),',') . ' ' . $open_hours_1_open . '-' . $open_hours_1_close . '" />';
		}
		
		if ( $open_hours_2_sa ) {	
			$dow_checked = 'true';	
			echo '<meta itemprop="openingHours" content="Sa ' . $open_hours_2_open . '-' . $open_hours_2_close . '" />';
		}		

		if ( $open_hours_3_su ) {	
			$dow_checked = 'true';	
			echo '<meta itemprop="openingHours" content="Su ' . $open_hours_3_open . '-' . $open_hours_3_close . '" />';
		}
		
		if ( $dow_checked == 'true' && !$display_hours ) { //user clicked 'Do not display hours'	
			
			echo '<div class="osw-opening-hours"><span class="osw-label hours">' . __( 'Hours: ', 'osw' ) . '</span>';
			
			if ( $open_hours_1_mo || $open_hours_1_tu || $open_hours_1_we || $open_hours_1_th || $open_hours_1_fr ) {
				echo '<span class="osw-data m-f">' . rtrim(trim($open_hours_dow),',') . ': ' . date("g:i a",strtotime($open_hours_1_open)) . ' - ' . date("g:i a",strtotime($open_hours_1_close)) .'</span>';
			}
			
			if ( $open_hours_2_sa ) {
				echo '<span class="osw-data sat">Sat: ' . date("g:i a",strtotime($open_hours_2_open)) . ' - ' . date("g:i a",strtotime($open_hours_2_close)) .'</span>';
			}
			
			if ( $open_hours_3_su ) {
				echo '<span class="osw-data sun">Sun: ' . date("g:i a",strtotime($open_hours_3_open)) . ' - ' . date("g:i a",strtotime($open_hours_3_close)) .'</span>';
			}
			
			echo '</div>';
		}
				
		//* If user entered data into the 'after' text box, display it now
		if ( $txt_after ) {
			echo '<span class="osw-data after">'.$txt_after.'</span>';
		}

		echo '</div>'; // end schema type


		echo '</div>'; // widget-text end
		
		echo $after_widget;
	}
}

//* Register our widget
add_action('widgets_init', create_function('', 'return register_widget("rvam_organization_schema_widget");' ) );


//* Display settings link on plugin page
add_filter ('plugin_action_links', 'rvam_osw_plugin_action_links', 10, 2);
function rvam_osw_plugin_action_links($links, $file) {
	global $rvam_osw_path;
	if ($file == $rvam_osw_path) {
		$rvam_links = '<a href="' . get_admin_url() . 'options-general.php?page=rvam-osw-options">' . __('Settings') .'</a>';
		array_unshift($links, $rvam_links);
	}
	return $links;
}

 
//* Add a link to the sidebar pointing to our options page 
function rvam_osw_register_options_page() {
	add_options_page('OSW Options', 'OSW Options', 'manage_options', 'rvam-osw-options', 'rvam_osw_options_page');
}
add_action('admin_menu', 'rvam_osw_register_options_page');

//* Build our options page 
function rvam_osw_options_page() { ?>
<div class="wrap" style="max-width:65%;">
	<?php screen_icon(); ?>
	<h2>Organization Schema Widget Options</h2>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'default' ); ?>
			<table class="form-table">
				<tr>
					<td colspan="2">This plugin comes with a stylesheet containing several predefined styles. If you want to disable the built-in 
					stylesheet and use your own, simply un-check the box below.
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="rvam_use_plugin_stylesheet">Use Plugin Stylesheet?</label></th>
					<td><input id="rvam_use_plugin_stylesheet" name="rvam_use_plugin_stylesheet" type="checkbox" value="1" <?php checked( '1', get_option('rvam_use_plugin_stylesheet')); ?> /> YES</td>				
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
