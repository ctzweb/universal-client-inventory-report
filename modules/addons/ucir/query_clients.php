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
 * File: query_clients.php
 * Purpose: Retrieves client information from WHMCS for use throughout
 *          the UCIR reporting framework.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


use WHMCS\Database\Capsule;


/**
 * Retrieve Active Clients
 *
 * Returns an array of active WHMCS clients.
 *
 */

function ucirGetActiveClients()
{


    $clients = array();


    try {


        $results = Capsule::table("tblclients")
            ->where(
                "status",
                "Active"
            )
            ->orderBy(
                "id",
                "asc"
            )
            ->get();



        foreach ($results as $client) {


            $clients[] = array(

                "client_id" =>
                    $client->id,


                "firstname" =>
                    $client->firstname,


                "lastname" =>
                    $client->lastname,


                "company" =>
                    $client->companyname,


                "email" =>
                    $client->email,


                "phone" =>
                    $client->phonenumber,


                "status" =>
                    $client->status

            );


        }


    } catch (\Exception $e) {


        if (defined("UCIR_DEBUG") && UCIR_DEBUG) {

            logActivity(
                "UCIR Client Query Error: "
                . $e->getMessage()
            );

        }


    }



    return $clients;

}