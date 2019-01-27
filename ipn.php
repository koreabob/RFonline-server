<?php


###############################################################################
#                        RFServer in Korea, Made by Sweet                     #
###############################################################################


include('config.php');
include('functions/functions.php');




// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}


// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";

$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
//$header .= "POST /pp/ HTTP/1.0\r\n";
//$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//$header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
//$fp = fsockopen ('www.belahost.com', 80, $errno, $errstr, 30);
$headers = "From: raiden@pvplords.com";



// assign posted variables to local variables
// note: additional IPN variables also available -- see IPN documentation
/*$item_name = $_POST['item_name'];
$receiver_email = $_POST['receiver_email'];
$item_number = $_POST['item_number'];
$invoice = $_POST['invoice'];
$payment_status = $_POST['payment_status'];
$payment_gross = $_POST['payment_gross'];
$txn_id = $_POST['txn_id'];
$payer_email = $_POST['payer_email'];
*/
$txn_id = $_POST['txn_id'];
$txn_id = ereg_replace( ";$", "", $txn_id);
$txn_id = ereg_replace( "\\\\", "", $txn_id);
$payer_email = $_POST['payer_email'];
$payer_email = ereg_replace( ";$", "", $payer_email);
$payer_email = ereg_replace( "\\\\", "", $payer_email);
$payer_id = $_POST['payer_id'];
$payer_id = ereg_replace( ";$", "", $payer_id);
$$payer_id = ereg_replace( "\\\\", "", $payer_id);
$business = $_POST['business'];
$business = ereg_replace( ";$", "", $business);
$business = ereg_replace( "\\\\", "", $business);
$custom = $_POST['custom'];
$custom = ereg_replace( ";$", "", $custom);
$custom = ereg_replace( "\\\\", "", $custom);


$payment_fee = $_POST['payment_fee'];
$payment_fee = ereg_replace( ";$", "", $payment_fee);
$payment_fee = ereg_replace( "\\\\", "", $payment_fee);
$payment_gross = $_POST['payment_gross'];
$payment_gross = ereg_replace( ";$", "", $payment_gross);
$payment_gross = ereg_replace( "\\\\", "", $payment_gross);

if($payment_gross == "5.00"){$credits="2500";}
elseif($payment_gross == "10.00"){$credits="6000";}
elseif($payment_gross == "15.00"){$credits="9500";}
elseif($payment_gross == "20.00"){$credits="13000";}
elseif($payment_gross == "25.00"){$credits="16500";}
elseif($payment_gross == "30.00"){$credits="20000";}
elseif($payment_gross == "35.00"){$credits="23500";}
elseif($payment_gross == "40.00"){$credits="27000";}
elseif($payment_gross == "45.00"){$credits="30500";}
elseif($payment_gross == "50.00"){$credits="34000";}
elseif($payment_gross == "55.00"){$credits="37500";}
elseif($payment_gross == "60.00"){$credits="41000";}
elseif($payment_gross == "65.00"){$credits="44500";}
elseif($payment_gross == "70.00"){$credits="48000";}
elseif($payment_gross == "75.00"){$credits="51500";}
elseif($payment_gross == "80.00"){$credits="55000";}
elseif($payment_gross == "85.00"){$credits="58500";}
elseif($payment_gross == "90.00"){$credits="62000";}
elseif($payment_gross == "95.00"){$credits="65500";}
elseif($payment_gross == "100.00"){$credits="69000";}
else{$credits="0";}


if (!$fp) {
	echo "$errstr ($errno)";
} else {

  fputs ($fp, $header . $req);
  while (!feof($fp)) {

    $res = fgets ($fp, 1024);

    if ($res == "VERIFIED") {
	
    
      if($_POST[payment_status]=="Completed") {

	$time = time();

	connectcashdb();

	$trancount = mssql_result(mssql_query("SELECT count(*) FROM tbl_PayPal WHERE tranid = '$txn_id'"),0,0);

	if (($trancount == 0) && ($business == 'raiden@pvplords.com')) {

		connectcashdb();
		mssql_query("INSERT INTO tbl_PayPal (tranid, amount, fee, userid, name, credits, time, payerid, payeremail, verified) VALUES ('$txn_id', '$payment_gross', '$payment_fee', '$custom', '$custom', '$credits', '$time', '$payer_id', '$payer_email', '1')");
		connectcoindb();
		mssql_query("UPDATE dbo.donationPoints SET dcoins = (dcoins + $credits) WHERE userId = '$custom'");
	} else {
		if ($business != 'raiden@pvplords.com') {
			connectcashdb();
			mssql_query("INSERT INTO tbl_PayPal (dupetran, amount, fee, userid, name, credits, time, payerid, payeremail, verified) VALUES ('$txn_id', '$payment_gross', '$payment_fee', '$custom', '$custom', '$credits', '$time', '$payer_id', '$payer_email', '3')");
		} else {
			connectcashdb();
			mssql_query("INSERT INTO tbl_PayPal (dupetran, amount, fee, userid, name, credits, time, payerid, payeremail, verified) VALUES ('$txn_id', '$payment_gross', '$payment_fee', '$custom', '$custom', '$credits', '$time', '$payer_id', '$payer_email', '2')");
		}
		//echo "Cannot refresh transaction!";
	}
	
      }
      // check the payment_status is Completed
      // check that txn_id has not been previously processed
      // check that receiver_email is an email address in your PayPal account
      // process payment

    } else if (strcmp ($res, "INVALID") == 0) {

	connectcashdb();
	$time = time();
	mssql_query("INSERT INTO tbl_PayPal (tranid, amount, fee, userid, name, credits, time, payerid, payeremail, verified) VALUES ('$tnx_id', '$payment_gross', '$payment_fee', '$custom', '$custom', '$credits', '$time', '$payer_id', '$payer_email', '0')");
    }
  }
  fclose ($fp);
}

?>