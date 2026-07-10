<?php
/**
 * Universal Client Inventory Report (UCIR)
 *
 * A free, open-source reporting framework for WHMCS.
 *
 * Copyright (c) 2026 Cortez Web Services
 * https://cortezweb.com
 *
 * Lead Developer: Douglas LaMunyon
 *
 * Licensed under the MIT License.
 * See LICENSE for details.
 *
 * File: report_engine.php
 * Purpose: Coordinates report generation by selecting the appropriate
 *          data source and preparing records for export.
 *
 * Version: 0.7.0
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


/**
 * Build Report Dataset
 *
 * @param array $request
 * @return array
 */
function ucirBuildReport(array $request = array())
{
    $reportType = $request["report_type"] ?? "inventory";

    switch ($reportType) {

        case "client":

            return ucirBuildClientReport();

        case "service":

            return ucirMapServices();

        case "domain":

            return ucirMapDomains();

        case "inventory":

        default:

            return ucirBuildInventory();
    }
}


/**
 * Client Report
 *
 * One row per active client.
 */
function ucirBuildClientReport()
{
    if (!function_exists("ucirGetActiveClients")) {
        return array();
    }

    $records = array();

    foreach (ucirGetActiveClients() as $client) {

        $records[] = array(

            "inventory_type"      => "Client",

            "client_id"           => $client["client_id"],

            "client_first_name"   => $client["firstname"],

            "client_last_name"    => $client["lastname"],

            "client_company"      => $client["company"],

            "client_email"        => $client["email"],

            "client_phone"        => $client["phone"]

        );
    }

    return $records;
}