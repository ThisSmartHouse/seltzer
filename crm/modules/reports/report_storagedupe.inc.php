<?php

/*
    Copyright 2009-2014 Edward L. Platt <ed@elplatt.com>
    
    This file is part of the Seltzer CRM Project
    report_planinfo.inc.php - Membership plan reports
    Part of the Reports module

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

// Installation functions //////////////////////////////////////////////////////
// No install as this is called by reports module

// Utility functions ///////////////////////////////////////////////////////////
/*
 * Set the page content based on report name. Used for autoinclude
 */
$report_storagedupe_theme = 'table';
$report_storagedupe_theme_opts = 'storagedupe';
$report_storagedupe_name = "Duplicate Plots";
$report_storagedupe_desc = "List of contacts with multiple storage plots";

/**
 * @return A comma-separated list of user emails.
 * @param $opts - Options to pass to member_data().
 */
function get_dupe_storage () {
    // Query contacts who have no plans associated
    $sql = "SELECT *
    FROM storage_plot
    WHERE cid != ''
    AND cid IN (
        SELECT cid
        FROM storage_plot
        GROUP BY cid
        HAVING COUNT(cid) > 1
    )
    ORDER BY cid;";

    $res = mysql_query($sql);
    if (!$res) { crm_error(mysql_error($res)); }
    
    $dupes=array();
    $row = mysql_fetch_assoc($res);
    while (!empty($row)) {
        // Contents of row are name, value
        $dupes[] = $row;
        $row = mysql_fetch_assoc($res);
    }

    return $dupes;
}

// Tables ///////////////////////////////////////////////////////////////////////
function storagedupe_table () {
    // Determine settings
    $export = false;
    // foreach ($opts as $option => $value) {
    //     switch ($option) {
    //         case 'export':
    //             $export = $value;
    //             break;
    //     }
    // }
    $dupes = get_dupe_storage();

     // Initialize table
    $table = array(
        'columns' => array(
            array('title' => 'Name')
            , array('title' => 'Plan')
            , array('title' => 'Start')
            , array('title' => 'End')
            , array('title' => 'Plot#')
            , array('title' => 'Description')
        )
        , 'rows' => array()
    );

    // Add rows
    $contact_data = crm_get_data('contact', '');
    foreach ($dupes as $plot) {
        // Add secrets data
        // var_dump_pre($plot);
        $row = array();
        
        // Get info on member
        if ($plot['cid'] == 0) {
            $data = array();
            $member = array(array());
        } else {
            $data = member_data(array('cid'=>$plot['cid']));
            $member = $data[0];
        }
        $contact = $member['contact'];
        if ( $plot['cid'] ) {
            $crm_user = crm_get_one('contact',array('cid'=>$plot['cid']));
            if ($crm_user) {
                $cid_to_contact = crm_map($contact_data, 'cid');
                $name = theme('contact_name', $cid_to_contact[$plot['cid']], !$export);
            } else {
               $name = empty($plot['email']) ? $plot['cid'] : '<a href="mailto:'.$plot['email'].'">'.$plot['cid'].'</a>';
            }
        } else {
            $row[] = '';
        }
        if (isset($member['membership'])) {
            $recentMembership = end($member['membership']);
            $plan = $recentMembership['plan']['name']; // then this is an active plan
            $planstart = $recentMembership['start'];
            $planend = $recentMembership['end'];
        } else {
            $recentMembership = '';
            $plan = ''; // then this is an active plan
            $planstart = '';
            $planend = '';
        }
        $row[] = $name;
        $row[] = $plan;
        $row[] = $planstart;
        $row[] = $planend;
        $row[] = $plot['pid'];
        $row[] = $plot['desc'];

        $table['rows'][] = $row;  

    }   
    // Return table
    return $table;
}


// Themeing ////////////////////////////////////////////////////////////////////

// Pages ///////////////////////////////////////////////////////////////////////
// No pages
