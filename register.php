<?php
// Ajax call for checking User name
if(isset($_GET['q']) && $_GET['q'] != "") {	
	global $wpdb;
	
	$usernm = $_GET['q'];
	$getrec = $wpdb->get_var( $wpdb->prepare( 
		"
			SELECT user_login 
			FROM $wpdb->users 
			WHERE user_login = %s
		", 
		$usernm
	) );

	 if (!empty($getrec)) {
        echo '<span style="color:red">Sorry, username already exists</span>';
	 } else {
		 echo '<span style="color:green">Available</span>';
		 }
	exit(0);
	}
// Ajax call for checking Email
if(isset($_GET['e']) && $_GET['e'] != "") {	
	global $wpdb;
	
	$email = $_GET['e'];
	$getrec = $wpdb->get_var( $wpdb->prepare( 
		"
			SELECT user_email 
			FROM $wpdb->users 
			WHERE user_email = %s
		", 
		$email
	) );

	 if (!empty($getrec)) {
        echo '<span style="color:red">Sorry, email already exists</span>';
	 } else {
		 echo '<span style="color:green">Available</span>';
		 }
	exit(0);
	}
/*
  Plugin Name: Virtue WP Registrar 
  Plugin URI: http://www.virtuenetz.com
  Description: Registrer As a New User.
  Version: 1.0
  Author: Awais Zafar
  Author URI: http://www.virtuenetz.com
 */


function virtu_custom_registration_function() {
    if (isset($_POST['submit'])) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio'],
		$_POST['password_repeat'],
        $_POST['zipCode'],
        $_POST['phoneNumber'],
        $_POST['email']
        );
        
        // sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio,$role, $phoneNumber, $zipCode, $password_repeat;
        $username   =   sanitize_user($_POST['username']);
        $password   =   esc_attr($_POST['password']);
        $email      =   sanitize_email($_POST['email']);
        $website    =   esc_url($_POST['website']);
        $first_name =   sanitize_text_field($_POST['fname']);
        $last_name  =   sanitize_text_field($_POST['lname']);
        $nickname   =   sanitize_text_field($_POST['nickname']);
        $phoneNumber   =   sanitize_text_field($_POST['phoneNumber']);
        $zipCode   		=   sanitize_text_field($_POST['zipCode']);
        $bio        =   esc_textarea($_POST['bio']);
        $role       =   $_POST['role'];
        $password_repeat       =   esc_attr($_POST['password_repeat']);
        $phoneNumber = esc_attr($_POST['phoneNumber']);
        $email = esc_attr($_POST['email']);
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio,
        $zipCode,
        $phoneNumber,
        $email
        );
    }

    registration_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio,
		$phoneNumber,
		$zipCode,
		$password_repeat,
        $phoneNumber,
        $email
        );
}

function registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $phoneNumber, $zipCode, $password_repeat,$phoneNumber,$email ) {
	
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
}	
if (strlen(strstr($agent, 'Firefox')) > 0) {
    $typepass = 'password';
} else {
	$typepass = 'text';	
}	
	
	
echo '	
<script>
function chkuser() { 
var str = document.getElementById("username").value;
//var surl = <?php echo base_url(); ?>;
//alert(surl);
if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "?q=" + str, true);
        xmlhttp.send();
    }
}
function chkemail() { 
var str = document.getElementById("email").value;
if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint2").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "?e=" + str, true);
        xmlhttp.send();
    }
}
</script>
	<style type="text/css">
		sup {
			color: red;
		}
	</style>
	<div class="custom-action-block-holder">
		<div class="custom-login-block">
			<strong>Already have an account?</strong>
			<a href="' .get_site_url().'/login">Log in</a>
		</div>
		<div class="custom-contact-block">
			<strong>Questions?</strong>
			<a href="' .get_site_url().'/contact-us">Contact Us</a>
		</div>
	</div>
	<br>
	<br>
	<div class="registration_form">
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="registration_form myForm">
			
			<div>
				<label for="bio">Sign Up As <sup class="required">*</sup></label>
				<select name="role" class="form-control signup_as">
					<option value="Parent">Parent</option>
					<option value="cNanny">Nanny</option>
				</select>
			</div>
			
			<br>
			<div>
				<label for="username">Username <sup class="required">*</sup></label>
				<input class="form-control" type="text" id="username" name="username" value="' . (isset($_POST['username']) ? $username : null) . '" required="" onchange="chkuser()"><div id="txtHint">&nbsp;</div>
				<label id="username-error" class="error" for="username" style="display: none;">Please enter your username</label>
			</div>
			
			<div>
				<label for="firstname">First Name <sup class="required">*</sup></label>
				<input class="form-control" type="text" name="fname" value="' . (isset($_POST['fname']) ? $first_name : null) . '" required="">
				<label id="username-error" class="error" for="fname" style="display: none;">Please enter your first name</label>
			</div>
			
			<div>
				<label for="website">Last Name <sup class="required">*</sup></label>
				<input class="form-control" type="text" name="lname" value="' . (isset($_POST['lname']) ? $last_name : null) . '" required="">
				<label id="lname-error" class="error" for="lname" style="display: none;">Please enter your last name</label>
			</div>
			
			<div>
				<label for="email">Email <sup class="required">*</sup></label>
				<input id="email" class="form-control" type="email" name="email" value="' . (isset($_POST['email']) ? $email : null) . '" onchange="chkemail()"><div id="txtHint2">&nbsp;</div>
				<label id="email-error" class="error" for="email" style="display: none;">Please enter a valid e-mail address</label>
			</div>
			
			<div class="nanny-phone" style="display: none">
				<label>Phone Number <sup class="required">*</sup></label>
				<input class="form-control" type="text" placeholder="+16175551212" maxlength="13" name="phoneNumber" value="' . (isset($_POST['phoneNumber']) ? $phone_number : null) . '">
				<label id="phoneNumber-error" class="error" for="phoneNumber" style="display: none;">Please enter a valid phone number</label>
			</div>
			
			<div>
				<label>Zip Code <sup class="required">*</sup> <span class="color-gray">(where you live)</span></label>
				<input class="form-control" type="text" name="zipCode" value="' . (isset($_POST['zipCode']) ? $zipCode : null) . '" required="">
				<label id="zipCode-error" class="error" for="zipCode" style="display: none;">Please enter your 5 digit zip code</label>
			</div>
			
			<div>
				<label for="password">Password <sup class="required">*</sup></label>
				<div class="password_must color-gray">
					Your password must:
					<ul id="password_validations">
						
					</ul>
				</div><style>#password, #password_repeat{-webkit-text-security: disc;}</style>
				<input data-validate-complexity="true" data-confirmation-field="#passwordConfirmation" id="password" class="form-control" type="'.$typepass.'" name="password" value="" required="">
				<label id="password-error" class="error" for="password" style="display: none;">Please enter your 5 digit zip code</label>
			</div>
			<div>
				<label for="password">Repeat Password <sup class="required">*</sup></label>
				<input id="password_repeat" class="form-control" type="'.$typepass.'" name="password_repeat" value="" required="">
				<label id="password_repeat-error" class="error" style="display: none;">Passwords do not match!</label>
			</div>
			<p class="agreement-custom-class">By signing up, you confirm that you agree to our <a href="'.get_permalink(49).'">terms and conditions</a> and <a href="'.get_permalink(32).'">privacy policy</a>.
			</p>
			<br>
			<div class="buttonarea">
				<input type="submit" name="submit" value="Sign up" class="btn green-btn submit-button" style="font-size: 20px;">
			</div>
			<br>
		</form>
	</div>
	'.do_action('facebook_login_button').'';
}

function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $password_repeat,$zipCode,$phoneNumber,$email )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( strlen( $username ) < 4 ) {
        $reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
    }

    if ( username_exists( $username ) )
        $reg_errors->add('user_name', 'Sorry, username unavailble');

    if ( !validate_username( $username ) ) {
        $reg_errors->add('username_invalid', 'Sorry, the username you entered is not valid');
    }

    if ( strlen( $password ) < 8 ) {
        $reg_errors->add('password', 'Password length must be greater than 8');
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add('email_invalid', 'Email is not valid');
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add('email', 'Email already in use');
    }
    
    if ( $password_repeat != $password ) {
            $reg_errors->add('Repeat Password', 'Password did not match.');
    }


    if(preg_match('/[A-Z]/', $password_repeat)){
        
    }else{
        $reg_errors->add('Repeat Password', 'Password should must have upper case letter.');
    }

    if (preg_match('/[0-9]/', $password_repeat)){

    }else{
        $reg_errors->add('Repeat Password', 'Password should must least one number.');
    } 

    if (preg_match('/[\'^£$%&*()}{@#!~?><>,|=_+¬-]/', $password_repeat)){
        
    }else{
        $reg_errors->add('Repeat Password', 'Password should must contain any special character.');

    }


    if ( is_wp_error( $reg_errors ) ) {

        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div class="error-box">';
            echo '<strong>ERROR</strong>: ';
            echo $error . '<br/>';
            echo '</div>';
        }
    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role,$zipCode,$phoneNumber;
   // print_r($role);
	
	
	
if($role == 'Parent' && count($reg_errors->get_error_messages()) < 1) {
 
$to = $email;
$subject = 'Cnanny Registration';
$body = 'Hello,<br>
Thank you for joining the cNanny community where you will save time and money while reducing risk, uncertainty and stress in your search for an experienced and reliable nanny as we only feature fully vetted qualified nanny candidates. Compassionate care for your child is our #1 priority and therefore, all candidates have completed a rigorous screening and vetting process, which includes a psychometric and aptitude assessment. We are adding new candidates on a daily basis so check back often.<br><br> 

Sincerely,<br>
<span style="font-size:18px;font-family:Bradley Hand ITC">Sara Duke</span><br> 
CEO and Founder<br>
<img src="'.site_url().'/wp-content/themes/cnanny/images/email_logo.jpg" width="120" />
';
 
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$headers[] = 'From: Cnanny Registration <contact@cnanny.com>';
 
wp_mail( $to, $subject, $body, $headers );

}

/*add_filter( 'wp_mail_content_type', 'set_html_content_type' );

wp_mail( 'jalal@virtuenetz.com', 'subject', '<h1>Html Email</h1>' );

function set_html_content_type() {
return 'text/html';
}	*/
	
	
	
	
	
    //die();
    if ( count($reg_errors->get_error_messages()) < 1 ) {
        $userdata = array(
        'user_login'    =>  $username,
        'user_email'    =>  $email,
        'user_pass'     =>  $password,
        'user_url'      =>  $website,
        'first_name'    =>  $first_name,
        'last_name'     =>  $last_name,
        'nickname'      =>  $nickname,
        'description'   =>  $bio,
        );
        $user = wp_insert_user( $userdata );
        //print_r($user);
        ///print_r($role);
        wp_update_user( array ('ID' => $user, 'role' => $role) ) ;
        update_user_meta($user,'vn_zip_code',$zipCode);
        update_user_meta($user,'vn_ph_no',$phoneNumber);
        print_r(get_user_meta($user,'vn_ph_no',true));
        auto_login_new_user($user);

    }
}

function auto_login_new_user( $user_id ) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        wp_redirect( get_permalink(10) );
        exit;
    }

//add_action( 'user_register', 'auto_login_new_user' );
// Register a new shortcode: [cr_custom_registration]
add_shortcode('cr_custom_registration', 'custom_registration_shortcode');

// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    virtu_custom_registration_function();
    return ob_get_clean();
}


add_role('Parent', __(
    'Parent'),
    array(
        'read'              => true, // Allows a user to read
        'create_posts'      => true, // Allows user to create new posts
        'edit_posts'        => true, // Allows user to edit their own posts
        'publish_posts'     => true, // Allows the user to publish posts
        )
);

add_action('init','testfuntion');
function testfuntion(){
    //wp_update_user( array ('ID' => 4, 'role' => 'Parent') ) ;
}
