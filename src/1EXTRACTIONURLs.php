<?php
/*
    Script  : EXTRACTION DE TOUT LES URLs QUI CORRESPOND A NOTRE EQUATION DE RECHERCHE
    Author  : Adnan AHOUZI
    version : 1.0

    */

    /*
    --------------------------------------------------------------------
    Usage: Gratuit pour un usage personnel
    --------------------------------------------------------------------

    Requirements: PHP/CURL 

    */
?>

<?php
    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }
?>

<?php   
    // Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );
         
        $ch = curl_init();  // Initialising cURL
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }
?>

<?php

   $a=0;
   $continue =1;
   $url = "http://patentscope.wipo.int/search/en/result.jsf?currentNavigationRow=1&prevCurrentNavigationRow=1&query=FP:%28%22renewable%20energy%22%29&office=&sortOption=Pub%20Date%20Desc&prevFilter=&maxRec=2769&viewOption=All";    // Assigning the URL we want to scrape to the variable $url
   while ($continue < 289) {
   $results_page = curl($url); // Downloading the results page using our curl() funtion
   $results_page = scrape_between($results_page, "<tbody id=\"resultTable:tb\">", "</tbody>"); // Scraping out only the middle section of the results page that contains our results  
   $separate_results = explode("<tr class=\"rich-table-row \">", $results_page);   // Expploding the results into separate parts into an array 
    // For each separate result, scrape the URL
    foreach ($separate_results as $separate_result) {
        if ($separate_result != "" AND $a % 2 == 0 ) 
		{
    $results_urls[] = "http://patentscope.wipo.int/search/en/" . scrape_between($separate_result, "href=\"", ">"); // Scraping the page ID number and appending to the IMDb URL - Adding this URL to our URL array
        }
    $a = $a +1;
    }
	$continue = $continue + 1;
	$url = 'http://patentscope.wipo.int/search/en/result.jsf?currentNavigationRow=' . $continue . '&prevCurrentNavigationRow=1&query=FP:%28%22renewable%20energy%22%29&office=&sortOption=Pub%20Date%20Desc&prevFilter=&maxRec=2769&viewOption=All';   
	}
	$fp=fopen("allurls.txt", "w"); //ouverture du fichier en mode écriture, création du fichier s'il n'existe pas.
     for ($i = 0; $i < 2882; $i++) {
	 fwrite($fp,$results_urls[$i]."\r\n");
}
?>


