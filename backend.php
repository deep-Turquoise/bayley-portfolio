<?php

function return_First_File_Name($dir) {
    $contents = scandir($dir);
    return $contents[2]; // second element skips . and ..
}

function openModal($category, $id) {
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
                            \">";
                $cur_Category = $row['category'];
                $cur_id = $row['id'];
                print "<a onclick=\"openModal('$cur_Category', '$cur_id')\">";

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

if($instructions == "categories") { get_Categories(); }
if($instructions == "allocate_Projects") { get_Projects($category); }
if($instructions == "openModal") { openModal($category, $id); }