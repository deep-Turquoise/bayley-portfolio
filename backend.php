<?php

function get_Categories(){

}

function get_Projects($category) {

}

$instructions = $_GET['instructions'];
$category = $_GET['category_name'];

if($instructions == "category") { get_Categories(); }
if($instructions == "allocate_Projects") { get_Projects($category); }