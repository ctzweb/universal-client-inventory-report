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
 * File: ucir.php
 * Purpose: Serves as the main controller by loading UCIR components,
 *          rendering the report interface, and initializing the module.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


/**
 * UCIR Base Path
 */

define(
    "UCIR_ROOT",
    __DIR__ . "/"
);


/**
 * Load UCIR Components
 */

$ucirFiles = array(
    "config.php",
    "field_definitions.php",
    "helpers.php",
    "html_builder.php",
    "query_clients.php",
    "query_services.php",
    "query_domains.php",
    "data_mapper.php",
    "report_engine.php",
    "csv_export.php"
);


foreach ($ucirFiles as $file) {

    $filePath = UCIR_ROOT . $file;

    if (file_exists($filePath)) {
        require_once $filePath;
    }

}


/**
 * Render Report Interface
 */

function ucirRenderReport()
{

    if (function_exists("ucirBuildInterface")) {

        return ucirBuildInterface();

    }


    return "
    <div class='alert alert-warning'>
        UCIR interface components have not been installed yet.
    </div>
    ";

}


/**
 * UCIR Diagnostic Test
 */

function ucirRunDiagnostics()
{


    $clients = array();

    $services = array();

    $domains = array();



    if (function_exists("ucirGetActiveClients")) {

        $clients = ucirGetActiveClients();

    }


    if (function_exists("ucirGetActiveServices")) {

        $services = ucirGetActiveServices();

    }


    if (function_exists("ucirGetActiveDomains")) {

        $domains = ucirGetActiveDomains();

    }



    $statusCounts = array();



    foreach ($domains as $domain) {


        $status =
            $domain["status"] ?? "Unknown";


        if (!isset($statusCounts[$status])) {

            $statusCounts[$status] = 0;

        }


        $statusCounts[$status]++;

    }



    $statusOutput = "";



    foreach ($statusCounts as $status => $count) {


        $statusOutput .= "

        <br>

        " . htmlspecialchars($status) . ":
        " . $count . "

        ";

    }



    if (empty($statusCounts)) {

        $statusOutput = "

        <br>
        No domain status information found.

        ";

    }



    return "

    <div class='alert alert-info'>

        <strong>UCIR Database Test</strong>

        <br><br>

        Active Clients:
        " . count($clients) . "

        <br>

        Active Services:
        " . count($services) . "

        <br>

        Active Domains:
        " . count($domains) . "


        <hr>


        <strong>Domain Status Breakdown</strong>

        " . $statusOutput . "


    </div>

    ";

}
