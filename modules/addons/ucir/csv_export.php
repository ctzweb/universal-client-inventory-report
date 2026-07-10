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
 * File: csv_export.php
 * Purpose: Generates CSV reports from standardized UCIR records and
 *          delivers them as downloadable exports.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}



/**
 * Generate CSV Export
 *
 */

function ucirGenerateCSV($selectedFields = array())
{

    $reportType =
        $_POST["ucir_report_type"] ?? "inventory";


    $hostingStatuses =
        $_POST["ucir_hosting_statuses"] ?? array();


    $domainStatuses =
        $_POST["ucir_domain_statuses"] ?? array();


    if (
        empty($hostingStatuses)
        &&
        (
            $reportType == "inventory"
            ||
            $reportType == "service"
            ||
            $reportType == "client"
        )
    ) {

        return "

        <div class='alert alert-warning'>

            Please select at least one Hosting Status.

        </div>

        ";

    }


    if (
        empty($domainStatuses)
        &&
        (
            $reportType == "inventory"
            ||
            $reportType == "domain"
            ||
            $reportType == "client"
        )
    ) {

        return "

        <div class='alert alert-warning'>

            Please select at least one Domain Status.

        </div>

        ";

    }


    if (!function_exists("ucirBuildInventory")) {

        return "

        <div class='alert alert-danger'>

            UCIR inventory builder is missing.

        </div>

        ";

    }



    switch ($reportType) {

        case "client":

            $inventory =
                ucirMapClients(
                    $hostingStatuses,
                    $domainStatuses
                );

            break;

        case "service":

            $inventory =
                ucirMapServices(
                    $hostingStatuses
                );

            break;

        case "domain":

            $inventory =
                ucirMapDomains(
                    $domainStatuses
                );

            break;

        default:

            $inventory =
                ucirBuildInventory(
                    $hostingStatuses,
                    $domainStatuses
                );

            break;

    }



    if (empty($inventory)) {

        return "

        <div class='alert alert-info'>

            No records found for the selected report options.

        </div>

        ";

    }



    if (!function_exists("ucirGetFieldDefinitions")) {

        return "

        <div class='alert alert-danger'>

            UCIR field definitions are missing.

        </div>

        ";

    }



    $definitions =
        ucirGetFieldDefinitions();



    /**
     * Build CSV filename
     */

    switch ($reportType) {

        case "service":

            $reportName =
                "hosting_services";

            break;

        case "domain":

            $reportName =
                "domains";

            break;

        case "client":

            $reportName =
                "clients";

            break;

        default:

            $reportName =
                "complete_inventory";

            break;

    }


    $filename =
        "ucir_"
        . $reportName
        . "_"
        . date("Y-m-d_H-i-s")
        . ".csv";



    header(
        "Content-Type: text/csv; charset=UTF-8"
    );


    header(
        "Content-Disposition: attachment; filename="
        . $filename
    );



    $output =
        fopen(
            "php://output",
            "w"
        );



    /**
     * Build CSV Headers
     */

    $headers = array();


    foreach ($selectedFields as $fieldID) {


        if (
            isset($definitions[$fieldID])
        ) {


            $headers[] =
                $definitions[$fieldID]["label"];


        }


    }



    fputcsv(
        $output,
        $headers
    );



    /**
     * Build CSV Rows
     */

    foreach ($inventory as $record) {


        $row = array();



        foreach ($selectedFields as $fieldID) {


            $value = "";



            if (
                isset($definitions[$fieldID])
                &&
                isset($definitions[$fieldID]["map"])
            ) {


                $map =
                    $definitions[$fieldID]["map"];



                if (
                    isset($record[$map])
                ) {


                    $value =
                        $record[$map];


                }


            }



            $row[] =
                $value;


        }



        fputcsv(
            $output,
            $row
        );


    }



    fclose($output);


    exit;

}
