<?php

function get_Categories(){
    $conn = new mysqli("localhost", "root", "airpolo3", "intranet_Bayley");
    if ($conn->connect_error) { print "Database Connection Error"; }
    $sql = "SELECT * FROM category";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<a><div class=\"category_Block\">" . row['name'] ."</div></a>";
        }
    }
    $conn->close();

}

function get_Projects($category) {

}

$instructions = $_GET['instructions'];
$category = $_GET['category_name'];

if($instructions == "categories") { get_Categories(); }
if($instructions == "allocate_Projects") { get_Projects($category); }