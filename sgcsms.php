<?php
/*
Plugin Name: SMS Gateway Center Bulk SMS Sender
Plugin URI: http://www.smsgatewaycenter.com
Description: Wp plugin to send bulk SMS from wp sites. Send Bulk SMS from any wp site. This is a plugin for www.smsgatewaycenter.com. You will have to be a registered member to use this plugin.
Version: 1.2
Author: SMS Gateway Center
Author URI: http://www.smsgatewaycenter.com
License: GPL3
Text Domain: SGCSMS
*/

	/*
	Copyright 2010-2014  SMS Gateway Center  (E-mail: contact@smsgatewaycenter.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; If not, see <http://www.gnu.org/licenses/>.
	*/

	$sgcsmsfolder = plugin_dir_path( __FILE__ );
	$sgcoption = $_REQUEST["sgcoption"];
	$settingsfile = $sgcsmsfolder."settings.php";
	if( file_exists( $settingsfile ) ){
		include ($settingsfile);
	} else {
	}

	add_action('admin_menu', 'smsgatewaycenterdotcomsms');
	function smsgatewaycenterdotcomsms(){
		global $sgcoption;
		add_menu_page('SMSGatewayCenter.com', 'SGCSMS', '', 'sgcsms', 'bulksmssender', plugins_url( 'favicon.png', __FILE__ ), '10');
		add_submenu_page('sgcsms', 'Send Bulk SMS', 'Send Bulk SMS', '1', 'sgcsms_send','bulksmssender');
		add_submenu_page('sgcsms', 'Delivery Report', 'Delivery Report', '1', 'sgcsms_dlr','sgcdeliveryreport');
		add_submenu_page('sgcsms', 'Short Code Report', 'Short Code Report', '1', 'sgcsms_shortcode','sgcshortcodereport');
		add_submenu_page('sgcsms', 'Check Balance', 'Check Balance', '1', 'sgcsms_checkbalance','sgc_checkbalance');
		add_submenu_page('sgcsms', 'Change Password', 'Change Password', '1', 'sgcsms_changepass','sgc_change_password');
		add_submenu_page('sgcsms', 'SMS Account Settings', 'Settings', '1', 'sgcsms_settings','sgcsettings');
	}
	
	function bulksmssender() {
		global $sgcoption, $username, $password;
		$smscontent = $_REQUEST["smscontent"];
		$to = $_REQUEST["To"];
		$mask = $_REQUEST["mask"];
		switch ($sgcoption){
			case sendbulksms:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>Bulk SMS Sender - SMS Gateway Center</h1>";
				
				if ($smscontent == "") {
					echo "<p>Error!<br>Text not entered<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
					die;
				} else {
				}

				
				if ($to == "") {
					echo "<p>Error!<br>Mobile Numbers not entered<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
					die;
				} else {
				}

				
				if ($mask == "") {
					echo "<p>Error!<br>Sender Name not entered<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
					die;
				} else {
				}

					//complete url request for sending bulk sms to bulk recipients, restrict number of mobile numbers to 150
					//http://www.smsgatewaycenter.com/library/send_sms_2.php?UserName=nehadndoff&Password=password&Type=Bulk&To=9999999999,9999999998,9999999997&Mask=Senderid&Message=Hello%20World
					$url = "http://www.smsgatewaycenter.com/library/send_sms_2.php";

					$param['UserName'] = $username;
					$param['Password'] = urlencode($password);
					$param['Type'] = "Bulk";
					$param['To'] = $to;
					$param['Mask'] = $mask;
					$param['Message'] = $smscontent;


					//$postfields = array ("UserName" => "$username","Password" => "urlencode($password)","Message" => "urlencode($smscontent)","To" => "$to_x","Type" => "Individual","Mask" => "$mask");
					
					if (!$ch = curl_init()) {
						echo "Could not initialize cURL session.";
						exit;
					}

					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
					$curl_scraped_page = curl_exec($ch); 
					curl_close($ch);
					echo $curl_scraped_page."<br><br>";
					echo "<p><a href=\"javascript:history.back(-1)\">Send New Message</a></p>";
				break;
			default:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>Bulk SMS Sender - SMS Gateway Center</h1>"."<form method=post action=\"";
				echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
				echo "&sgcoption=sendbulksms\">"."<p><b>Sender Name:</b><br><input type=\"text\" name=\"mask\"></p>"."<p><b>To / Mobile Numbers:</b><br><textarea rows=\"4\" cols=\"25\" name=\"To\"></textarea>"."<blockquote><small>"."- Separate numbers with ',' <i>Example: 9999999991,919999999992, 09999999993</i>.<br>"."- Maximum Mobile numbers: <i>Use maximum 150 numbers at once.</i>"."</small></blockquote></p>"."<p><b>Message:</b><br><textarea rows=\"4\" cols=\"25\" name=\"smscontent\"></textarea></p>"."<p><input type=submit class=button name=submit value=\"Send Bulk SMS\"></p>"."</form>";
		}

	}

	function sgc_change_password() {
		global $sgcoption, $username, $password, $settingsfile;
		$newpass = $_REQUEST["newpass"];
		$confirmpass = $_REQUEST["confirmpass"];
		switch ($sgcoption){
			case sgcpasschange:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>Change Password - SMS Gateway Center</h1>";
				
				if ($newpass == "") {
					echo "<p>Error!<br>New Password cannot be blank<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
					die;
				} 
				elseif  ($confirmpass == "") {
					echo "<p>Error!<br>Confirm Password cannot be blank<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
					die;
				} 
				else {
					if($newpass == $confirmpass)
					{
						$url = "http://www.smsgatewaycenter.com/library/wp/change_password.php";

						$param['Username'] = $username;
						$param['OldPassword'] = urlencode($password);
						$param['NewPassword'] = urlencode($newpass);

						if (!$ch = curl_init()) {
							echo "Could not initialize cURL session.";
							exit;
						}
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
						$curl_scraped_page = curl_exec($ch); 
						curl_close($ch);
						list ($status, $cpmessage) = split('\|', $curl_scraped_page);
						if ($status == "SUCCESS " || $status == "SUCCESS" ) {
							$f=fopen("$settingsfile","w");
							fwrite($f,"<?php\n\$username=\"$username\";\n\$password=\"$newpass\";\n?>");
							fclose($f);
						}
						echo $curl_scraped_page."<br><br>";
						echo "<p><a href=\"javascript:history.back(-1)\">Go back</a></p>";
					}
					else {
						echo "<p>Error!<br>New Password and Confirm Password do not match<br><a href=\"javascript:history.back(-1)\">Go Back</a></p>";
						die;
					}
				}

				break;
			default:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>Bulk SMS Sender - SMS Gateway Center</h1>"."<form method=post action=\"";
				echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
				echo "&sgcoption=sgcpasschange\">"."<p><b>New Password:</b><br><input type=\"password\" name=\"newpass\"></p>"."<p>New Password:</b><br><input type=\"password\" name=\"confirmpass\"></p>"."<p><input type=submit class=button name=submit value=\"Change Password\"></p>"."</form>";
		}

	}
	
	function sgcsettings(){
		global $sgcoption, $settingsfile, $username, $password;
		switch ($sgcoption) {
			case createfiles:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>SMSGatewayCenter.com Account Settings</h1>";
				$username = $_REQUEST['username'];
				$password = $_REQUEST['password'];
				$f=fopen("$settingsfile","w");
				fwrite($f,"<?php\n\$username=\"$username\";\n\$password=\"$password\";\n?>");
				fclose($f);
				echo "<p>Your Settings Successfully Saved!</p>";
				break;
			default:
				echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
				echo "<h1>SMSGatewayCenter.com Account Settings</h1>"."<form method=post action=\"";
				echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
				echo "&sgcoption=createfiles\">"."<p><b>Username</b><br><input type=\"text\" name=\"username\" value=\"$username\">"."<blockquote><small>"."Your registered username on SMSGatewaycenter.com <a href=\"http://www.smsgatewaycenter.com/\" target=\"_blank\">get it here</a>."."</small></blockquote></p>"."<p><b>Password</b><br><input type=\"password\" name=\"password\" value=\"$password\">"."<blockquote><small>"."Your password which you use to login at SMSGatewayCenter.com <a href=\"http://www.smsgatewaycenter.com/\" target=\"_blank\">SMSGatewayCenter.com</a>."."</small></blockquote></p>"."<p><input type=submit name=submit value=\"Save Settings\"></p>"."</form>";
		}

	}

	function sgcdeliveryreport(){
		global $sgcoption, $username, $password;
		//http://www.smsgatewaycenter.com/library/wp/deliveryreport_wp.php?UserName=username&Password=Password&DateFrom=2014-12-21&DateTo=2014-12-22
		echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
		echo "<h1>Delivery Report - SMS Gateway Center</h1>";
		$url = "http://www.smsgatewaycenter.com/library/wp/deliveryreport_wp.php";

		$param['UserName'] = $username;
		$param['Password'] = urlencode($password);
		$param['DateFrom'] = date("Y-m-d");
		$param['DateTo'] = date("Y-m-d", current_time( 'timestamp', 0 ));
				
		if (!$ch = curl_init()){
			echo "Could not initialize cURL session.";
			exit;
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$curl_scraped_page = curl_exec($ch); 
		curl_close($ch);
		echo $curl_scraped_page."<br><br>";
	}
	
	function sgcshortcodereport(){
		global $sgcoption, $sgcsmsfolder, $username, $password;
		//http://www.smsgatewaycenter.com/library/wp/shortcode_report_wp.php?UserName=username&Password=Password&DateFrom=2014-12-21&DateTo=2014-12-22
		echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
		echo "<h1>Short Code Report - SMS Gateway Center</h1>";
		$url = "http://www.smsgatewaycenter.com/library/wp/shortcode_report_wp.php";

		$param['UserName'] = $username;
		$param['Password'] = urlencode($password);
		$param['DateFrom'] = date("Y-m-d", strtotime("-1 month"));
		$param['DateTo'] = date("Y-m-d", current_time( 'timestamp', 0 ));
				
		if (!$ch = curl_init()){
			echo "Could not initialize cURL session.";
			exit;
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$curl_scraped_page = curl_exec($ch); 
		curl_close($ch);
		echo $curl_scraped_page."<br><br>";
	}
	
	function sgc_checkbalance(){
		global $sgcoption, $sgcsmsfolder, $username, $password;
		echo "<img style=\"margin:30px;clear:both\" src='".plugins_url( 'admin-logo.png', __FILE__ )."' alt=\"SMS Gateway Center\" border=\"0\" />";
		echo "<h1>Check SMS Balance - SMS Gateway Center</h1>";
		$url = "http://www.smsgatewaycenter.com/library/checkbalance.php";

		$param['Username'] = $username;
		$param['Password'] = urlencode($password);
				
		if (!$ch = curl_init()){
			echo "Could not initialize cURL session.";
			exit;
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$curl_scraped_page = curl_exec($ch); 
		curl_close($ch);
		list ($balance, $expiry) = split('\|', $curl_scraped_page);
		list($balancen, $seperator) = split('\ ', $balance);
		if ($balancen == "Balance " || $balancen == "Balance" ) {
			echo "<table class='wp-list-table widefat fixed posts'><thead><tr>
					<th scope='col' id='mobile' class='manage-column column-title'><span>Balance</span></th>
					<th scope='col' id='message' class='manage-column column-message'>Expiry</th></tr></thead><tbody id='the-list'>";
					preg_match( "/Balance : (\d+)/", $balance, $balancematches);
					$showbalance = $balancematches[1];
			echo "<tr><td><b>".$showbalance."</b> SMS Credits</td><td>".str_replace('Expire Date : ', '', $expiry)."</td></tr></tbody></table>";
		} else {
			echo $curl_scraped_page."<br><br>";
		}
	}
	?>