<?php
/*
    Script  : EXTRACTION DES URLs QUI CONTIENENT DES BREVETS WO
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
		$lines = file('allurls.txt');
		$fp1=fopen("WOurls.txt", "w");
		foreach ($lines as $lineNumber => $target)
		{
		if(preg_match("#WO#", $target ))
		fwrite($fp1,$target);
		}
?>