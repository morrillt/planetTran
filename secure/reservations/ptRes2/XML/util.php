<?php



/*

D I S C L A I M E R

WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.

Authorize.Net provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.

Authorize.Net owns and retains all right, title and interest in and to the Automated Recurring Billing intellectual property.

*/



include_once ("vars.php");



//function to send xml request to Api.

//There is more than one way to send https requests in PHP.

function send_xml_request($content)

{

	global $g_apihost, $g_apipath;

	$response = send_request_via_fsockopen($g_apihost,$g_apipath,$content);
    //todo: change back to ssl for live
	//$response = send_request_via_curl($g_apihost,$g_apipath,$content);

	return $response;

}



//function to send xml request via fsockopen

//It is a good idea to check the http status code.

function send_request_via_fsockopen($host,$path,$content)

{

	$posturl = "ssl://" . $host;
	$header = "Host: $host\r\n";
	$header .= "User-Agent: PHP Script\r\n";
	$header .= "Content-Type: text/xml\r\n";
	$header .= "Content-Length: ".strlen($content)."\r\n";
	$header .= "Connection: close\r\n\r\n";

	$fp = fsockopen($posturl, 443, $errno, $errstr, 30);

	if (!$fp)

	{

		$body = false;

	}

	else

	{

		error_reporting(E_ERROR);

		fputs($fp, "POST $path  HTTP/1.1\r\n");

		fputs($fp, $header.$content);

		fwrite($fp, $out);

		$response = "";

		while (!feof($fp))

		{

			$response = $response . fgets($fp, 128);

		}

		fclose($fp);

		error_reporting(E_ALL ^ E_NOTICE);



		$len = strlen($response);

		$bodypos = strpos($response, "\r\n\r\n");

		if ($bodypos <= 0)

		{

			$bodypos = strpos($response, "\n\n");

		}

		while ($bodypos < $len && $response[$bodypos] != '<')

		{

			$bodypos++;

		}

		$body = substr($response, $bodypos);

	}

	return $body;

}



//function to send xml request via curl

function send_request_via_curl($host,$path,$content)

{

	$posturl = "http://" . $host . $path; // HTTPS FOR LIVE!

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $posturl);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));

	curl_setopt($ch, CURLOPT_HEADER, 0);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	curl_exec($ch);

	$response = curl_multi_getcontent($ch);

	return $response;

}





//function to parse the api response

//The code uses SimpleXML. http://us.php.net/manual/en/book.simplexml.php

//There are also other ways to parse xml in PHP depending on the version and what is installed.

function parse_api_response($content)

{

	global $saturn;

	$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);

	if (!$saturn && "Ok" != $parsedresponse->messages->resultCode) {

		//CmnFns::diagnose($parsedresponse);

		echo "The operation failed with the following errors:<br>";

		foreach ($parsedresponse->messages->message as $msg) {

			echo "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . "<br>";

		}

		echo "<br>";

	}

	return $parsedresponse;

}



function MerchantAuthenticationBlock() {

	global $g_loginname, $g_transactionkey;

	return

        "<merchantAuthentication>".

        "<name>" . $g_loginname . "</name>".

        "<transactionKey>" . $g_transactionkey . "</transactionKey>".

        "</merchantAuthentication>";

}



function parse_api_response_json($content)

{

	global $saturn;

	$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);

	if (!$saturn && "Ok" != $parsedresponse->messages->resultCode) {

		//CmnFns::diagnose($parsedresponse);

            	//echo "The operation failed with the following errors:<br>";

                foreach ($parsedresponse->messages->message as $msg) {

			//echo "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . "<br>";

                    //errorecho "[" . htmlspecialchars($msg->code).htmlspecialchars($msg->text);

		}

		//echo "<br>";

	}

	return $parsedresponse;

}

function parse_api_response_nowrite($content)

{

	global $saturn;

	$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);


	return $parsedresponse;

}

?>