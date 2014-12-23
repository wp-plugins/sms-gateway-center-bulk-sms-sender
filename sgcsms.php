<?php
/*
Plugin Name: SMS Gateway Center Bulk SMS Sender
Plugin URI: http://www.smsgatewaycenter.com
Description: Wp plugin to send bulk SMS from wp sites. Send Bulk SMS from any wp site. This is a plugin for www.smsgatewaycenter.com. You will have to be a registered member to use this plugin.
Version: 1.1
Author: Raghhav Sammrat
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
	?>