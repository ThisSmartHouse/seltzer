<?php

/*
    Copyright 2009-2011 Edward L. Platt <elplatt@alum.mit.edu>
    
    This file is part of the Seltzer CRM Project
    reports.php - Displays list of available reports

    Seltzer is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    Seltzer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Seltzer.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('include/crm.inc.php');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.14.custom.css"/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.14.custom.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <title><?php print title(); ?></title>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php print theme('header'); ?>
        </div>
        <div class="content">
            <?php print theme('errors'); ?>
            <!-- TEMPORARY: should be replaced with dynamic code to list available reports -->
            <?php print theme('member_email_report', array('filter'=>array('active'=>true))); ?>
            <?php print theme('member_email_report', array('filter'=>array('active'=>false))); ?>
            <?php print theme('member_voting_report'); ?>
            <!-- / TEMPORARY -->
        </div>
        <div class="footer">
            <?php print theme('footer'); ?>
        </div>
    </div>
</body>
</html>
