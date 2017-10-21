<?PHP
error_reporting(0);
ini_set('display_errors', 0);
function buildBaseString($baseURI, $method, $params)
{
    $r = array();
    ksort($params);
    foreach ($params as $key => $value) {
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}
function buildAuthorizationHeader($oauth)
{
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach ($oauth as $key => $value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}
function returndata($user)
{
    $oauth_access_token = "XXX";
    $oauth_access_token_secret = "XXX";
    $consumer_key = "XXX";
    $consumer_secret = "XXX";
    $twitter_timeline = "user_timeline";
    $request = array(
        'screen_name' => $user,
        'count' => '3'
    );
    $oauth = array(
        'oauth_consumer_key' => $consumer_key,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_token' => $oauth_access_token,
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0'
    );
    $oauth = array_merge($oauth, $request);
    $base_info = buildBaseString("https://api.twitter.com/1.1/statuses/$twitter_timeline.json", 'GET', $oauth);
    $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;
    $header = array(buildAuthorizationHeader($oauth), 'Expect:');
    $options = array(CURLOPT_HTTPHEADER => $header,
        CURLOPT_HEADER => false,
        CURLOPT_URL => "https://api.twitter.com/1.1/statuses/$twitter_timeline.json?" . http_build_query($request),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false);
    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);
    $tweets = json_decode($json, true);
    foreach ($tweets as $tweet) {
        $userid = $tweet["user"]["id"];
        $name = $tweet["user"]["name"];
        $username = $tweet["user"]["screen_name"];
        $location = $tweet["user"]["location"];
        $description = $tweet["user"]["description"];
        $followers = $tweet["user"]["followers_count"];
        $following = $tweet["user"]["friends_count"];
        $made = $tweet["user"]["created_at"];
        $favourites_count = $tweet["user"]["favourites_count"];
        $listed_count = $tweet["user"]["listed_count"];
        $utc_offset = $tweet["user"]["utc_offset"];
        $time_zone = $tweet["user"]["time_zone"];
        $tweets_amount = $tweet["user"]["statuses_count"];
        $cover_img = $tweet["user"]["profile_background_image_url_https"];
        $profile_banner = $tweet["user"]["profile_banner_url"];
        $profile_img = $tweet["user"]["profile_image_url_https"];
        $profile_img = str_replace("_normal","","$profile_img");
        $verified = $tweet["user"]["verified"];
        if (is_null($website = $tweet["user"]["url"])){$website = NULL;}else{$website = $tweet["user"]["url"];};
        if (is_null($tweet["user"]["entities"]["url"]['urls'][0]['expanded_url'])){$website_link = NULL;}else{$website_link = $tweet["user"]["entities"]["url"]['urls'][0]['expanded_url'];};
    }
    return array('id' => $userid, 'userName' => $username, 'name' => $name, 'location' => $location, 'description' => $description, 'follower' => $followers,
        'following' => $following, 'created' => $made, 'favortitesCount' => $favourites_count, 'listedCount' => $listed_count, 'utcOffset' => $utc_offset,
        'timeZone' => $time_zone, 'tweets' => $tweets_amount, 'coverImage' => $cover_img, 'profileBanner' => $profile_banner, 'profileAvatar' => $profile_img, 'website' => $website, 'websiteLink' => $website_link, 'verified' => $verified);
}
