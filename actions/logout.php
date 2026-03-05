<?php
// admission-system/actions/logout.php

include('../includes/db.php');
include('../includes/auth.php');

// Destroy session and logout user
logoutUser();
?>