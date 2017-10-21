<?php
$user = $_GET['username'];
?>
<html lang="en">
<head>
    <title><?php echo $user;?>'s Twitter profile card</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="tcard.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro|Merriweather" rel="stylesheet">
</head>
<body>
<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well bg">
                <div class="row content">
                    <div class="col-lg-6">
                        <?php
                        include 'functions.php';
                        $data = returndata($user);
                        $date = $data['created'];
                        $date = date("F j, Y", strtotime($date));
                        $website = $data['websiteLink'];
                        $short_url = str_replace("https://www.","","$website");
                        $short_url = str_replace("http://www.","","$website");
                        echo "<a href='https://twitter.com/".$user."'><h1 class='name'>" . $data['name'] . "</h1></a>
                        <a href='https://twitter.com/".$user."'><h1 class='username'>@" . $data['userName'] . "</h1></a>
                        <h3 class='desc'>" . $data['description'] . "</h3>
                        <h4 class='location'>" . $data['location'] . "</h4>";
                        if (is_null($data['websiteLink'])){}else{echo "<h3 class='stat'><a href='".$data['websiteLink']."'> " . $short_url . "</a></h3>";};
                    echo "</div>
                <div class='col-lg-6'>
                    <img src='" . $data['profileAvatar'] . "' class='img-circle avatar' height='122' width='122'>
                    <h3 class='stat'>" . number_format($data['tweets'], 0, ',', ',') . " Tweets</h3>
                    <h3 class='stat'>" . number_format($data['follower'], 0, ',', ',') . " Followers</h3>
                    <h3 class='stat'>Following " . number_format($data['following'], 0, ',', ',') . "</h3>
                    <h3 class='stat'>Created " . $date . "</h3>";
                    if ($data['verified'] == 1){echo "<h3 class='stat'>Is verified</h3>";}else{};
                echo "</div>"; ?>
                    </div>
                </div>
            </div>
            <div class='col-lg-3'></div>
        </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(".well").mouseenter(
            function(){
                $(this).css({'background':'url("<?php echo $data['profileBanner'];?>")', 'background-size':'contain', 'background-repeat':'repeat-x', 'box-shadow': 'inset 0 0 0 1000px rgba(255, 255, 255, 0.25),8px 10px 20px 2px #000000'});
            });

        $(".well").mouseleave(
            function(){
                $(this).css({'background-color':'#52a1e6', 'background-image':'url("")', 'box-shadow': '8px 10px 20px 2px #000000'});
            });
    </script>
</body>
</html>
