<?php

/*
 * LMS version 1.11-cvs
 *
 *  (C) Copyright 2001-2009 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

if($layout['module'] != 'customeredit')
{
	$customerinfo = $LMS->GetCustomer($customerid);
	$SMARTY->assign_by_ref('customerinfo', $customerinfo);
}

$expired = !empty($_GET['expired']) ? true : false;
$assignments = $LMS->GetCustomerAssignments($customerid, !empty($expired) ? $expired : NULL);
$customergroups = $LMS->CustomergroupGetForCustomer($customerid);
$othercustomergroups = $LMS->GetGroupNamesWithoutCustomer($customerid);
$balancelist = $LMS->GetCustomerBalanceList($customerid);
$customervoipaccounts = $LMS->GetCustomerVoipAccounts($customerid);
$tariffs = $LMS->GetTariffs();
$documents = $LMS->GetDocuments($customerid, 10);
$taxeslist = $LMS->GetTaxes();
$allnodegroups = $LMS->GetNodeGroupNames();
$messagelist = $LMS->GetMessages($customerid, 10);
$eventlist = $LMS->EventSearch(array('customerid' => $customerid), 'date,desc', true);
$customernodes = $LMS->GetCustomerNodes($customerid);
$customernodes['ownerid'] = $customerid;

if(!empty($documents))
{
        $SMARTY->assign('docrights', $DB->GetAllByKey('SELECT doctype, rights
	        FROM docrights WHERE userid = ? AND rights > 1', 'doctype', array($AUTH->id)));
}

if(isset($CONFIG['phpui']['ewx_support']) && chkconfig($CONFIG['phpui']['ewx_support']))
{
        $SMARTY->assign('ewx_channelid', $DB->GetOne('SELECT MAX(channelid)
		FROM ewx_stm_nodes, nodes
                WHERE nodeid = nodes.id AND ownerid = ?', array($customerid)));
}

$SMARTY->assign(array(
	'expired' => $expired, 
	'time' => $SESSION->get('addbt'),
	'value' => $SESSION->get('addbv'),
	'taxid' => $SESSION->get('addbtax'),
	'comment' => $SESSION->get('addbc'),
	'sourceid' => $SESSION->get('addsource'),
));

$SMARTY->assign('sourcelist', $DB->GetAll('SELECT id, name FROM cashsources ORDER BY name'));
$SMARTY->assign_by_ref('customernodes', $customernodes);
$SMARTY->assign_by_ref('assignments', $assignments);
$SMARTY->assign_by_ref('customergroups', $customergroups);
$SMARTY->assign_by_ref('othercustomergroups', $othercustomergroups);
$SMARTY->assign_by_ref('balancelist', $balancelist);
$SMARTY->assign_by_ref('customervoipaccounts', $customervoipaccounts);
$SMARTY->assign_by_ref('tariffs', $tariffs);
$SMARTY->assign_by_ref('documents', $documents);
$SMARTY->assign_by_ref('taxeslist', $taxeslist);
$SMARTY->assign_by_ref('allnodegroups', $allnodegroups);
$SMARTY->assign_by_ref('messagelist', $messagelist);
$SMARTY->assign_by_ref('eventlist', $eventlist);

?>