<?php

class ELBP_Users_Orgs_Importer {
	
	var $tmpUploadPath;
	var $adminPage;
	
	public function __construct() {
		
		$this->adminPage = basename(__FILE__);
		$this->tmpUploadPath = WP_CONTENT_DIR . '/uploads/tmp/';
		$this->logoUploadPath = WP_CONTENT_DIR . '/logo_import/';
		
		add_action('admin_menu' , array($this,'add_csv_import_page')); 
		
	}
	
    /*
     * Add Menu Page
    */		
	public function add_csv_import_page() {
		add_submenu_page('users.php', 'Import Users & Organisations from CSV', 'CSV Import', 'edit_posts', basename(__FILE__), array($this,'csv_import_page_content'));
	}
	
    /*
     * Custom Admin Menu Page Content
    */		
	public function csv_import_page_content() {  
	
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div><h2>Import Organisations &amp; Users from CSV</h2>
			
			<?php if(!isset($_REQUEST['action'])): ?>
			<p>Import organisations and create users assigned to them from uploading and importing your CSV below:</p>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->adminPage; ?>" method="post" id="csv_user_import_form" enctype="multipart/form-data">
				<label for="csv_file"><strong>Choose CSV:</strong></label>
				<input type="file" name="csv_file" id="csv_file" /> <br /> <br />
				<button class="button button-primary" id="user_import_go">Run</button>
				<input type="hidden" name="action" value="import_users_csv" />
			</form>	
			<?php else:	
				// Run Import 
				$this->_importUsersFromCsv($_FILES['csv_file']);
			?>
			<?php endif; ?>
		</div>	
	<?php	
	}	

    /*
     * Import Users from CSV
    */		
	public function _importUsersFromCsv($csv) {
		
		require_once(ELBP_LIB.'parsecsv/parsecsv.lib.php');
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		ini_set('display_errors',1);

		$tmp_dir = $this->tmpUploadPath;
		$filepath = $tmp_dir.$csv['name'];

		/* Check if File has been uploaded  */
		if( !$csv['name'] ) {
			echo '<p><strong>Error Uploading File:</strong> No File Selected</p><a href="'.admin_url('users.php').'?page='.$this->adminPage.'" class="button">Try Again</a>';
			return false;
		}
		
		/* Check File Extension */
		if( pathinfo($csv['name'], PATHINFO_EXTENSION) != 'csv' ) {
			echo '<p><strong>Error Uploading File:</strong> File format not allowed</p><a href="'.admin_url('users.php').'?page='.$this->adminPage.'" class="button">Try Again</a>';
			return false;
		}

		if( move_uploaded_file( $csv['tmp_name'], $filepath) ) {
		
			/* Get Absolute File Path */
			$realpath = realpath($filepath);
			
			/* Set permissions on newly uploaded file */
			chmod($realpath, 0777);
			
			/* Initiate CSV Class & Load Uploaded CSV */
			$csv = new parseCSV();
			$csv->auto($realpath);	
			
			/* Store Data in our own array */
			$data = array();
			
			/* Add Column Titles */
			/*foreach ($csv->titles as $value) {
			    $data[] = $value;
			}*/
			
			/* Map Data to Column titles */
			foreach ($csv->data as $key => $row) {				
			    $data[$key] = $row;				
			    /*foreach ($row as $value) {
			    	@$data[$key][$row] = $value;
			    }		*/		
			}

			/* AJAX Progress Bar */
			$total_records = count($data);
			$progress = 0;		
			$not_imported = array();
			$i=0;
			$notimporterrors = array();
			/*echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit;*/
			

			/* And finally add our data to the DB */
			foreach($data as $row) {
				
				$i++;
				
				if(@$row['email_address'] == '' || !is_email(@$row['email_address']) ) {
					@$not_imported[] = array('email'=>$row['email_address'], 'reason'=>'Invalid email');
					continue;
				}
				
				$pass = wp_generate_password();
				
				// data
				$org_name = $row['name'];
				$website = parse_url($row['website']);
				$telephone = $row['telephone'];
				$address = ucwords(trim($row['address']));
				$city = ucwords(trim($row['city']));
				$state = ucwords(trim($row['state']));
				$postcode = strtoupper(trim($row['postcode']));
				$content = $row['description'];
				$logo = $row['logo'];
				
				$full_address = array(
					$address,
					$city,
					$state,
					$postcode
				);
				
				$map_cords = google_reverse_geocode_address($full_address);
				
				// fix not http:// prefix urls
				if (empty($website['scheme'])) {
					$website = 'http://'.$website['host'].$website['path'];
				}
				
				// import any new taxonomy values...
				$locations_eligible = $row['locations_eligible'];
				$support_types = $row['support_types'];
				$support_topics = $row['support_topics'];
				$keywords = $row['keywords'];
				
				$taxonomies_terms = array(
					'organisations_support'	=>	explode(',', $support_types),
					'organisations_locations'	=>	explode(',', $locations_eligible),
					'support_topics'	=>	explode(',', $support_topics),
					'keywords'	=>	explode(',', $keywords)
				);
				
				$terms_to_assign = array(); // store id's to assign to post later
				
				foreach($taxonomies_terms as $taxonomy => $terms) {
					if(empty($terms) || count($terms) == 0) continue;
					foreach($terms as $term) {
						
						$term = rtrim($term);
						if(empty($term)) continue;
						
						$term_check = term_exists($term, $taxonomy);
						if( $term_check !== 0 && $term_check !== null ) {
							$terms_to_assign[$taxonomy][] = (int) $term_check['term_id'];
						} else {
							// add the new term
							$new_term = wp_insert_term($term, $taxonomy);
							$terms_to_assign[$taxonomy][] = (int) $new_term['term_id'];
						}
						
					}
				}
				
			/*echo '<pre>';
			print_r($terms_to_assign);		
			echo '</pre>';

			exit;*/
							
				
				// insert user
				$userdata = array(
					'user_login'	=>	$row['email_address'],
					'user_pass'		=>	$pass,
					'user_email'	=>	$row['email_address'],
					'role'			=>	'organisation_owner'
				);
				
				$userid = wp_insert_user($userdata);
				
				// add user meta
				if( !is_wp_error($userid) ) {
				
					add_user_meta( $userid, 'csv_imported', 1 );
					add_user_meta( $userid, 'approved', 1 );
					
					// Add organisation 
					$post_id = wp_insert_post(array(
  						'post_title'    => $org_name,
  						'post_content'  => $content,
  						'post_status'   => 'publish',
  						'post_author'   => $userid,
  						'post_type'		=>	'organisations'						
					));
					
					if(is_wp_error($post_id)) {
						$notimporterrors[] = $post_id;
					}
					
					add_user_meta( $userid, 'user_organisation_id', $post_id );
					
					// add meta
					add_post_meta($post_id, 'organisation_website', $website);
					add_post_meta($post_id, 'organisation_phone_number', $telephone);
					add_post_meta($post_id, 'organisation_email', $row['email_address']);
					add_post_meta($post_id, 'organisation_phone_number', $telephone);
					add_post_meta( $post_id, 'csv_imported', 1 );
					
					add_post_meta( $post_id, 'location_event', array('address'=>implode(', ', $full_address), 'lat'=>$map_cords['lat'], 'lng'=>$map_cords['lng'], 'zoom'=>14) );

					
					// assign tax / terms
					foreach($terms_to_assign as $taxonomy => $terms) {
						wp_set_object_terms($post_id, $terms, $taxonomy);
					}
					
					// logoings
					if( $logo != '' ):
						if(file_exists($this->logoUploadPath.$logo)):
							$filetype = wp_check_filetype( basename( $this->logoUploadPath.$logo ), null );
							$wp_upload_dir = wp_upload_dir();
							
							// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $this->logoUploadPath.$logo ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $logo ) ),
								'post_content'   => '',
								'post_status'    => 'inherit'
							);
							
							// Insert the attachment.
							$attach_id = wp_insert_attachment( $attachment, $logo, $post_id );
																
							// Generate the metadata for the attachment, and update the database record.
							$attach_data = wp_generate_attachment_metadata( $attach_id, $this->logoUploadPath.$logo );
							wp_update_attachment_metadata( $attach_id, $attach_data );
							
							set_post_thumbnail( $post_id, $attach_id );
						endif;
					endif;	
				
				} else {
					$not_imported[] = array('email'=>$row['Email Address'], 'reason'=>$userid->get_error_message());
				}
	
			}
			
			/* Delete the uploaded file */
			unlink($realpath);
			
			echo '
			<p><strong>Import Complete</strong></p>
			<p><strong>Total Records:</strong> '.count($data).'</p>
			<p><strong>Not Imported:</strong> '.count($not_imported).'</p>';
			if( count($not_imported>1)): 
			echo '<ul>';
				foreach($not_imported as $not):
				echo '<li>'.$not['email'].' - '.$not['reason'].'</li>';
				endforeach;
			echo '</ul>';
			endif; 
			echo '<pre>';
			var_dump($notimporterrors);
			echo '</pre>';
			echo '
			<a href="'.admin_url('users.php').'" class="button">Manage Users</a>			
			';
			
			#$this->_clearImportProgress();		
				
			return true;
		
		} else {
			echo '<p><strong>Error Uploading File:</strong> '.$csv['name'].'</p><p><strong>Possible reason:</strong> the file is to large.</p><a href="'.admin_url('users.php').'?page='.$this->adminPage.'" class="button">Try Again</a>';
			return false;
		}
		
	}
	
	
}

$ELBP_USER_ORGS_IMPORTER = new ELBP_Users_Orgs_Importer();
	
?>