<?
//$time = date('m/d/Y h:i:s a');
$dayofweek = date('w');
$princemilledge = 'https://routes.uga.edu/simple/routes/2615/direction/14791/stops/';
$healthsciences = 'https://routes.uga.edu/simple/routes/2611/direction/14736/stops/';
$weekender = 'https://routes.uga.edu/simple/routes/2620/direction/15992/stops/';
$healthsciences_stop = '2737363/pattern';
//echo give_data("https://routes.uga.edu/simple/routes/2611/direction/14736/stops/2737363/pattern");
//give_data("https://routes.uga.edu/simple/routes/2615/direction/14791/stops/2737363/pattern");
//sunday
$hour = date('H');
if($dayofweek==0){
    if($hour>=12&&$hour<21){
        echo give_data($weekender.$healthsciences_stop);
    }
}
else if($dayofweek==6){
    if($hour>=10&&$hour<7){
        echo give_data($weekender.$healthsciences_stop);
    }
}
//weekdays and working hours
else if($hour>6&&$hour<19){
    echo give_data($healthsciences.$healthsciences_stop);
}
//nights and saturday night
else if(($hour<3||$hour>=19)&&$dayofweek<=6&&$dayofweek!=0){
    echo give_data($princemilledge.$healthsciences_stop);
}
else if($hour<7){
    echo "<h2 style=\"font-size:40px\"; class=\"textcenter\"> (good night)</h2>";
}
else {
    echo "<h2 style=\"font-size:70px\"; >you're walkin' bro</h2>";
}
//POST: string with all bus data and htmlformatting
function give_data($url){
    //$str = get_web_page("https://routes.uga.edu/simple/routes/2611/direction/14736/stops/2737363/pattern");
    $str = get_web_page($url);
    // '<!--'.$x.'--->';
    $htmlstr = "";
    if (strpos($str,"is currently arriving.")>0)
    //if (FALSE)
    {
        $htmlstr = $htmlstr. '<h1 class="textcenter bushere"style="color:red;"> bus is here</h1>';
    }
    else if (strpos($str,'arrives')>1){
        $str = substr($str,strpos($str,"Bus"));
        $minutes = substr($str,strpos($str,"in")+3,2);
        $time = substr($str,strpos($str,"minutes at ")+11,4);
        $minutes = intval($minutes);
        $htmlstr = $htmlstr. '<h2 class="subtitle">Arrives in </h2>';
        if ($minutes>=10){
            $htmlstr = $htmlstr. '<h2 class="textcenter minutes"style="color:green;">'.$minutes.'</h2><h2 class="subtitle"> minutes </h2>';
        }
        else if ($minutes<10&&$minutes>5){
            $htmlstr = $htmlstr. '<h2 class="textcenter minutes"style="color:yellow;">'.$minutes.'</h2><h2 class="subtitle"> minutes </h2>';
        }
        else if ($minutes<=5&&$minutes!=1){
            $htmlstr = $htmlstr. '<h2 class="textcenter minutes"style="color:red;">'.$minutes.'</h2> <h2 class="subtitle"> minutes </h2>';
        }
        else if ($minutes==1){
            $htmlstr = $htmlstr. '<h2 class="textcenter minutes"style="color:red;">'.$minutes.'</h2> <h2 class="subtitle"> minute </h2>';
        }
        
        //$minutes = substr($minutes,70);
        //echo substr($minutes,0,strpos($minutes,'.'));

    }
    else{
        $htmlstr = $htmlstr. '<h2 class="subtitle"> Bus is currently unavailable </h2>';
    }
    return $htmlstr;
}
function get_web_page( $url )
{
    $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(

        CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
        CURLOPT_POST           =>false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $content;
} 
?>