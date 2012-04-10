<?php 

// To acquire an API key, visit the Google APIs Console (https://code.google.com/apis/console/). In the Services pane, activate the Google Translate API; if the Terms of Service appear, read and accept them.  Next, go to the API Access pane. The API key is near the bottom of that pane, in the section titled "Simple API Access."

$google_key = 'YOURKEYHERE';

// Specify the language you want to translate into for a full list see: http://code.google.com/apis/language/translate/v2/using_rest.html#language-params
$language = 'fr';

mysql_connect("127.0.0.1", "root", "root") or die(mysql_error());
mysql_select_db("translate") or die(mysql_error());

$result = mysql_query("SELECT * FROM translate") or die(mysql_error());  


while($row = mysql_fetch_array( $result )) {

	$lookup = file_get_contents('https://www.googleapis.com/language/translate/v2?key='.$google_key.'&source=en&target='.$language.'&q='.urlencode($row["english"]));
	$append = json_decode($lookup);

	$translation = str_replace("&amp;", '&', str_replace("&quot;", '"', str_replace("&#39;", "'", $append->data->translations[0]->translatedText)));

	echo $row["english"]." -> ".$translation."\n";
	mysql_query("SET NAMES 'utf8'");
	$update = mysql_query("UPDATE translate SET translation='".addslashes($translation)."' WHERE id='".$row["id"]."'") or die(mysql_error());

}



?>