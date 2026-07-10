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
 * File: query_domains.php
 * Purpose: Retrieves domain information from WHMCS for use throughout
 *          the UCIR reporting framework.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


use WHMCS\Database\Capsule;


/**
 * Retrieve Domains
 *
 * Returns domains attached to active clients.
 *
 * @param array $statuses Domain statuses to include.
 * @return array
 *
 */


function ucirGetActiveDomains($statuses = array())
{

    $domains = array();


    /*
     * Default behavior remains Active only.
     */

    if (empty($statuses)) {

        $statuses = array(
            "Active"
        );

    }


    try {


        $results = Capsule::table("tbldomains")

            ->leftJoin(
                "tblclients",
                "tbldomains.userid",
                "=",
                "tblclients.id"
            )

            ->select(

                "tbldomains.id as domain_id",

                "tbldomains.userid as client_id",

                "tbldomains.domain",

                "tbldomains.status",

                "tbldomains.registrar",

                "tbldomains.registrationdate",

                "tbldomains.expirydate",

                "tbldomains.nextduedate",

                "tbldomains.donotrenew",

                "tblclients.status as client_status"

            )

            ->whereIn(
                "tbldomains.status",
                $statuses
            )

            ->where(
                "tblclients.status",
                "Active"
            )

            ->orderBy(
                "tbldomains.userid",
                "asc"
            )

            ->get();



        foreach ($results as $domain) {


            $domains[] = array(

                "domain_id" =>
                    $domain->domain_id,


                "client_id" =>
                    $domain->client_id,


                "domain" =>
                    $domain->domain,


                "registrar" =>
                    $domain->registrar,


                "registration_date" =>
                    $domain->registrationdate,


                "expiry_date" =>
                    $domain->expirydate,


                "next_due_date" =>
                    $domain->nextduedate,


                "do_not_renew" =>
                    $domain->donotrenew,


                "status" =>
                    $domain->status

            );


        }



    } catch (\Exception $e) {


        if (defined("UCIR_DEBUG") && UCIR_DEBUG) {

            logActivity(
                "UCIR Domain Query Error: "
                . $e->getMessage()
            );

        }


    }



    return $domains;

}
