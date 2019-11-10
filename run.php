<?php  
include 'curl.php';

function generate_user() {

	$fakename = curl('https://fakenametool.net/random-name-generator/random/id_ID/indonesia/1');
	preg_match('/<span>(.*?)<\/span>/s', $fakename, $name);
	$domain = ['1secmail.com', '1secmail.net', '1secmail.org'];
	$random = array_rand($domain);
	$email = strtolower(str_replace(' ',  '', $name[1])).rand(0000,1111).'@'.$domain[$random];
	return $email;
}

function register ($proxy, $referral) {
	$explode = explode('=', $referral);

	$headers =  array(
		'Host: brag.gg',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:70.0) Gecko/20100101 Firefox/70.0',
		'Referer: https://brag.gg/register.php',
		'Cookie: __cfduid=d030b73f89b7fbed85de54b86e48b71bc1573209322; _ga=GA1.2.1811578915.1573209328; _gid=GA1.2.1145622569.1573209328; _brrid='.str_replace('&secret', '', $explode[1]).'; _brscd='.$explode[2].'',
		'Content-Type: application/x-www-form-urlencoded'
	);

	$email = generate_user();
	$user = explode('@', $email);

	$register = curl('https://brag.gg/register.php', 'username='.$user[0].'&email='.$email.'&password=misaka123', $headers, $proxy);
	$cookies = getcookies($register);

	if (stripos($register, 'location: home.php')) {
		echo "\nSuccess register\n";
		$headers_verif = array(
			'Authority: brag.gg',
			'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1',
			'Referer: https://brag.gg/profile.php',
			'Cookie: __cfduid=d637c9c40bd50ae9a80eaff7baa53a3751573210270; _ga=GA1.2.1090419847.1573210275; _gid=GA1.2.2099627925.1573210275; _brscd=BY3146418834; __test; _bruid='.$cookies['_bruid'].'; _brrtk='.$cookies['_brrtk'].'; _gat_gtag_UA_40154448_42=1',
		);
		echo "Verif email address | ".$email."\n";
		$verif = curl('https://brag.gg/profile.php?verifymail=1', null, $headers_verif);

		if (stripos($verif, 'Email address will be verified after you')) {
			echo "Try to verif email";
			for ($i=0; $i < 3 ; $i++) { 
				sleep(1);
				echo ".";
			}
			echo "\n";
			$temp = curl('https://www.1secmail.com/mailbox',  'action=getMessages&login='.$user[0].'&domain='.$user[1]);

			if (stripos($temp, 'profile@brag.gg')) {
				$id = fetch_value($temp, '<td><a href="/mailbox/?action=readMessage&id=','&login=');
				$mail =  curl('https://www.1secmail.com/mailbox/?action=mailBody&id='.$id.'&login='.$user[0].'&domain='.$user[1]);
				$verif_link = fetch_value($mail, '<small>','</small>');

				$final = curl($verif_link);

				if (stripos($final, 'The email has been verified!')) {
					echo "Success verif email | ".$email."\n\n";
				} else {
					echo "Failed to verif email\n";
				}
			}
		} else {
			echo "Something wrong\n";
		}

	} else {
		echo "Failed to register user or proxy die | ".$proxy."\n";
	}
}




// $proxy = '1.10.187.237:8080';
echo "Referral brag.gg\n";
echo "Created by yudha tira pamungkas\n\n";

echo "Name file proxy (ex: socks.txt): ";
$namefile = trim(fgets(STDIN));
echo "Link referral (ex: https://brag.gg/?invite=12683&secret=BY3146418834): ";
$referral = trim(fgets(STDIN));
$file = file_get_contents($namefile) or die ("File Not Found\n");
$socks = explode("\r\n",$file);
$total = count($socks);
echo "Total Socks: ".$total."\n";
 
foreach ($socks as $value) {
    register($value, $referral);
}



?>