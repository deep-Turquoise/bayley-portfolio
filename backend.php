<?php

function changeImage($instructions, $image_URL) {
//    print "<div style='color:white'>";
//    print "<p>$image_URL</p>";

    if(strpos($image_URL,"http://intranet.bayley.net") === false) {
        $chunkA = substr($image_URL, 0, strlen($image_URL));
    } else {
        $chunkA = substr($image_URL, 53, strlen($image_URL));
    }

    $cur_Category = substr($chunkA, 0, strpos($chunkA, "/"));
    $chunkB = substr($chunkA, strpos($chunkA, "/")+1, strlen($chunkA));
    $cur_Id = substr($chunkB, 0, strpos($chunkB, "/"));
    $cur_Image_Name = substr(strrchr($chunkA, "/"), 1);

    $scan_arr = scandir("projects/$cur_Category/$cur_Id/");
    $arr_dir = [];

    for($x = 0; $x < count($scan_arr); ++$x) {
        if($scan_arr[$x] != "." && $scan_arr[$x] != ".." && $scan_arr[$x] != "") {
            array_push($arr_dir, $scan_arr[$x]);
        }
    }

    if(count($arr_dir) > 1) {
        for($x = 0; $x < count($arr_dir); ++$x) {
            if($arr_dir[$x] == $cur_Image_Name) {
                if($instructions == "nextImage") {
                    if($x == (count($arr_dir)-1)) {
                        print "projects/$cur_Category/$cur_Id/" . $arr_dir[0];
                    }
                    else {
                        print "projects/$cur_Category/$cur_Id/" . $arr_dir[$x+1];
                    }
                }
                if($instructions == "backImage") {
                    if($x == 0) {
                        print "projects/$cur_Category/$cur_Id/" . $arr_dir[count($arr_dir)-1];
                    }
                    else {
                        print "projects/$cur_Category/$cur_Id/" . $arr_dir[$x-1];
                    }
                }

                break;
            }
        }
    }
    if(count($arr_dir) <= 1) {
        print "projects/$cur_Category/$cur_Id/$cur_Image_Name";
    }

//    print "</div>";
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

                print "<div id=\"modal_left_arrow\" class=\"modal_left_arrow\" onclick=\"changeImage('backImage');\"><img src=\"left.png\"></div>";
                print "<div id=\"modal_right_arrow\" class=\"modal_right_arrow\" onclick=\"changeImage('nextImage');\"><img src=\"right.png\"></div>";

                print "<div id=\"modal_content\" class=\"modal_content\">";
                print "<div id=\"modal_image_zone\">";
                print "<img id=\"cur_modal_image\" src=\"$first_Image_Dir\">";
                print "</div>";

                print "<div class=\"modal_image_overlay\">";
                print "<h1>" . $row['project_name'] . "</h1>";
                print "<h3>" . $row['project_location'] . "</h3>";
                print "<p>" . $row['description'] . "</p>";

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
//$cur_img = $_GET['img'];
$image_URL = $_GET['image_URL'];


if($instructions == "categories") { get_Categories(); }
if($instructions == "allocate_Projects") { get_Projects($category); }
if($instructions == "openModal") { openModal($category, $id); }
if($instructions == "backImage" || $instructions == "nextImage") { changeImage($instructions, $image_URL); }