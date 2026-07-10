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
 * File: query_services.php
 * Purpose: Retrieves hosting service information from WHMCS for use
 *          throughout the UCIR reporting framework.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


use WHMCS\Database\Capsule;


/**
 * Retrieve Hosting Services
 *
 * Returns services linked to active clients.
 *
 * @param array $statuses Hosting statuses to include.
 * @return array
 */


function ucirGetActiveServices($statuses = array())
{


    $services = array();


    /*
     * Default behavior remains Active only.
     */

    if (empty($statuses)) {

        $statuses = array(
            "Active"
        );

    }


    try {


        $query = Capsule::table("tblhosting")

            ->join(
                "tblclients",
                "tblhosting.userid",
                "=",
                "tblclients.id"
            )

            ->leftJoin(
                "tblproducts",
                "tblhosting.packageid",
                "=",
                "tblproducts.id"
            )

            ->leftJoin(
                "tblproductgroups",
                "tblproducts.gid",
                "=",
                "tblproductgroups.id"
            )

            ->leftJoin(
                "tblservers",
                "tblhosting.server",
                "=",
                "tblservers.id"
            )

            ->where(
                "tblclients.status",
                "Active"
            )

            ->whereIn(
                "tblhosting.domainstatus",
                $statuses
            )

            ->select(

                "tblhosting.id as service_id",

                "tblhosting.userid as client_id",

                "tblhosting.domain",

                "tblhosting.username",

                "tblhosting.billingcycle",

                "tblhosting.amount",

                "tblhosting.nextduedate",

                "tblhosting.regdate",

                "tblhosting.domainstatus",

                "tblproducts.name as product",

                "tblproductgroups.name as product_group",

                "tblservers.name as server"

            )

            ->orderBy(
                "tblhosting.userid",
                "asc"
            );


        $results =
            $query->get();



        foreach ($results as $service) {


            $services[] = array(

                "service_id" =>
                    $service->service_id,


                "client_id" =>
                    $service->client_id,


                "product" =>
                    $service->product,


                "product_group" =>
                    $service->product_group,


                "service_status" =>
                    $service->domainstatus,


                "service_domain" =>
                    $service->domain,


                "username" =>
                    $service->username,


                "server" =>
                    $service->server,


                "billing_cycle" =>
                    $service->billingcycle,


                "amount" =>
                    $service->amount,


                "next_due_date" =>
                    $service->nextduedate,


                "registration_date" =>
                    $service->regdate

            );


        }



    } catch (\Exception $e) {


        if (defined("UCIR_DEBUG") && UCIR_DEBUG) {

            logActivity(
                "UCIR Service Query Error: "
                . $e->getMessage()
            );

        }


    }



    return $services;

}
