<?php

function backImage($category, $id, $cur_img) {
    $dir = "projects/$category/$id";
    $contents = scandir($dir);
    for($x = 0; $x < count($contents); ++$x) {
        print "This is category: $category <br>";
        print "This is id: $id <br>";
        print "This is contents: $contents[4] <br>";
        print "This is image: $cur_img <br>";
        if("$dir/$contents[$x]" == $cur_img) {
            if($x == 2) {
                $top_image_num = count($contents);
                print "<img src=\"$dir/$contents[$top_image_num]\">";
            } else {
                $num = $x - 1;
                $image_name = $contents[$num];
                print "<img src=\"$dir/$image_name\">";
            }
        }
    }
}

function nextImage($category, $id, $cur_img) {
    $dir = "projects/$category/$id";
    $contents = scandir($dir);
    for($x=0; $x < count($contents); ++$x) {
        if("$dir/$contents[$x]" == $cur_img) {
            if($x == count($contents)) {
                $top_image_num = $contents[2];
                print "<img src=\"$dir/$contents[$top_image_num]\">";
            } else {
                $num = $x + 1;
                print "<img src=\"$dir/$contents[$num]\">";
            }
        }
    }
}

function return_First_File_Name($dir) {
    $contents = scandir($dir);
    return $contents[2]; // second element skips . and ..
}

function openModal($category, $id) {
    $conn = new mysqli("localhost", "root", "airpolo3", "intranet_Bayley");
    if ($conn->connect_error) { print "Database Connection Error"; }
    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);

    $delay = 0.1; // used for increment the fade in time...

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['category'] == $category && $row['id'] == $id) {

                $dir = "projects/" .  $category . "/" . $id; // current directory
                $first_Image_Dir = $dir . "/" . return_First_File_Name($dir);

                print "<div class=\"modal_left_arrow\" onclick=\"back_image('$category','$id','$first_Image_Dir');\"><img src=\"left.png\"></div>"; // left/next image arrow
                print "<div class=\"modal_right_arrow\" onclick=\"next_image('$category', '$id', '$first_Image_Dir');\"><img src=\"right.png\"></div>"; // right arrow

                print "<div id=\"modal_content\" class=\"modal_content\">";
                print "<div id=\"modal_image_zone\">";
                print "<img id=\"cur_modal_image\" src=\"$first_Image_Dir\">";
                print "</div>";

                print "<div class=\"modal_image_overlay\">";
                print "<h1>" . $row['project_name'] . "</h1>";
                print "<p>" . $row['project_location'] . "</p>";


                print "</div>"; // END overlay
                print "</a></div>"; // END block

                $delay += 0.15;
            }
        }
    }
}

function get_Categories(){
    $conn = new mysqli("localhost", "root", "airpolo3", "intranet_Bayley");
    if ($conn->connect_error) { print "Database Connection Error"; }
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);

    //Just so we can make featured first
    print "<a onclick=\"getProjects('Featured')\"><div class=\"category_Block\">Featured</div></a>";

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            if($name != "Featured") {
                print "<a onclick=\"getProjects('$name')\"><div class=\"category_Block\">$name</div></a>";
            }
        }
    }
    $conn->close();
}

function get_Projects($category) {
    $conn = new mysqli("localhost", "root", "airpolo3", "intranet_Bayley");
    if ($conn->connect_error) { print "Database Connection Error"; }
    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);

    $delay = 0.1; // used for increment the fade in time...

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['category'] == $category) {
                $cur_Category = $row['category'];
                $cur_id = $row['id'];
                print "<div class=\"project_Block\" style=\"
                            animation: fadein 1200ms;
                            animation-delay: " . $delay . "s;
                            -moz-animation: fadein 1200ms;
                            -moz-animation-delay: " . $delay . "s;
                            -webkit-animation: fadein 1200ms;
                            -webkit-animation-delay: " . $delay . "s;
                            -o-animation: fadein 3200ms; 
                            opacity: 0;
                            animation-fill-mode: forwards;
                            \" onclick=\"openModal('$cur_Category', '$cur_id');\">";
                //print "<a onclick=\"openModal('$cur_Category', '$cur_id')\">";

                $dir = "projects/" .  $row['category'] . "/" . $row['id']; // current directory
                $first_Image_Dir = $dir . "/" . return_First_File_Name($dir);

                print "<img src=\"$first_Image_Dir\">";

                print "<div class=\"project_Block_Overlay\">";
                print "<h1>" . $row['project_name'] . "</h1>";
                print "<p>" . $row['project_location'] . "</p>";

                print "</div>"; // END overlay
                print "</a></div>"; // END block

                $delay += 0.15;
            }
        }
    }
    $conn->close();
}

$instructions = $_GET['instructions'];
$category = $_GET['category_name'];
$id = $_GET['id'];
$cur_img = $_GET['img'];

if($instructions == "categories") { get_Categories(); }
if($instructions == "allocate_Projects") { get_Projects($category); }
if($instructions == "openModal") { openModal($category, $id); }
if($instructions == "backImage") { backImage($category, $id, $cur_img); }
if($instructions == "nextImage") { nextImage($category, $id, $cur_img); }