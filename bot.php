<?php
# @_ivanmoreno - ivanmoreno.me - Iván Moreno  - hi@ivanmoreno.me

    //DB Config (You need a table named tweets with a single row, id varchar(100))
        $db   = "name db";
        $host = "host db";
        $user = "user db";
        $pass = "password db";

        $db =  new mysqli($host, $user, $pass, $db);

    //Twitter API Settings
        $settings = array('oauth_access_token'        => "oauth_access_token",
                          'oauth_access_token_secret' => "oauth_access_token_secret",
                          'consumer_key'              => "consumer_key",
                          'consumer_secret'           => "consumer_secret");    
 
    //Word searched by the bot
        $key = "someword";

    //Json info
        $json      = json_decode(getTweets($key, 100, $settings));
        $num_items = count($json);

    //Replies and reply that will be used randomly (add as many as you want)
        $replies    = array('Yeah', 'K', 'Fuck u');       
        $replyindex = rand(0, count($replies));     
        $reply      = $replies[$replyindex];

    //Fav tweet or not?
        $rand = rand(0,1);
        if($rand == 1){$fav = true;}else{$fav = false;}    

    //Let's start the bot    
        for($i=0; $i<$num_items; $i++){
            //Get basic info of the tweets
                $idtweet     = $json->statuses[$i]->id_str;
                $screen_name = $json->statuses[$i]->user->screen_name;
            
            if(!TweetExists($idtweet, $db)){
                //Add tweet to db, reply tweet and fav or not    
                AddID($idtweet, $db);
                sendTweet('@'.$screen_name.' '.$reply, $idtweet, $settings);
                if($fav){ doFAV($idtweet, $settings); }
            
            }     
        }     

    ##############FUNCTIONS#####################################

    //Function for getting tweets json
        function getTweets($query,$num_tweets,$settings){ 
            require_once('TwitterAPIExchange.php');
        
            if($num_tweets>100){ $num_tweets = 100; }
       
            $url = 'https://api.twitter.com/1.1/search/tweets.json';
            $getfield = '?q='.$query.'&count='.$num_tweets;
 
            $requestMethod = 'GET';
            $twitter = new TwitterAPIExchange($settings);
            $json =  $twitter->setGetfield($getfield)
                             ->buildOauth($url, $requestMethod)
                             ->performRequest();
            return $json;
        }

    //Function for sending tweet
        function sendTweet($mensaje, $tweet,$settings){
            require_once('TwitterAPIExchange.php');
        
            $url = 'https://api.twitter.com/1.1/statuses/update.json';
        
            $requestMethod = 'POST';
        
            $postfields = array( 'status' => $mensaje,
                                 'in_reply_to_status_id' => $tweet);

            $twitter = new TwitterAPIExchange($settings);
        
            return $twitter->buildOauth($url, $requestMethod)->setPostfields($postfields)->performRequest();
        }    

    //Function for faving tweet
        function doFAV($idtweet, $settings){
            require_once('TwitterAPIExchange.php');

            $url = 'https://api.twitter.com/1.1/favorites/create.json';      
            $requestMethod = 'POST';
            $postfields = array('id' => $idtweet);

            $twitter = new TwitterAPIExchange($settings);
    
            return $twitter->buildOauth($url, $requestMethod)->setPostfields($postfields)->performRequest();
        }    

    //Check if tweet exists
        function TweetExists($idtweet, $db){
            $rows = $db->query('SELECT id FROM tweets WHERE id="'.$idtweet.'"')->num_rows;
            
            if($rows > 0){
                return true;
            }else{
                return false;
            }
        }

        //Add tweet id to db
         function AddID($idtweet, $db){ 
            $db->query('INSERT INTO tweets (id) VALUES ("'.$idtweet.'")'); 
         } 


?>