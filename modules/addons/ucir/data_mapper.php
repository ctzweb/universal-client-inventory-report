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
 * File: data_mapper.php
 * Purpose: Converts raw WHMCS data into standardized records for use
 *          throughout the UCIR reporting framework.
 *
 * Version: 1.1.0
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . "/revenue_helper.php";

/**
 * Create Client Lookup
 */
function ucirBuildClientLookup()
{
    $lookup = array();

    if (!function_exists("ucirGetActiveClients")) {
        return $lookup;
    }

    $clients = ucirGetActiveClients();

    foreach ($clients as $client) {
        $lookup[$client["client_id"]] = $client;
    }

    return $lookup;
}

/**
 * Normalize Client Summary Records
 *
 * One row per active client.
 */
function ucirMapClients($hostingStatuses = array(), $domainStatuses = array())
{
    $records = array();

    if (!function_exists("ucirGetActiveClients")) {
        return $records;
    }

    $clients = ucirGetActiveClients();

    $serviceCounts = array();
    $domainCounts = array();

    $serviceLists = array();
    $domainLists = array();

    if (function_exists("ucirGetActiveServices")) {

        foreach (ucirGetActiveServices($hostingStatuses) as $service) {

            $clientId = $service["client_id"];

            if (!isset($serviceCounts[$clientId])) {
                $serviceCounts[$clientId] = 0;
            }

            if (!isset($serviceLists[$clientId])) {
                $serviceLists[$clientId] = array();
            }

            $serviceCounts[$clientId]++;

            $serviceLabel = trim(
                ($service["product"] ?? "")
                . (
                    !empty($service["service_domain"])
                    ? " - " . $service["service_domain"]
                    : ""
                )
            );

            if (!empty($serviceLabel)) {
                $serviceLists[$clientId][] = $serviceLabel;
            }

        }

    }

    if (function_exists("ucirGetActiveDomains")) {

        foreach (ucirGetActiveDomains($domainStatuses) as $domain) {

            $clientId = $domain["client_id"];

            if (!isset($domainCounts[$clientId])) {
                $domainCounts[$clientId] = 0;
            }

            if (!isset($domainLists[$clientId])) {
                $domainLists[$clientId] = array();
            }

            $domainCounts[$clientId]++;

            if (!empty($domain["domain"])) {
                $domainLists[$clientId][] = $domain["domain"];
            }

        }

    }

    foreach ($clients as $client) {

        $clientId = $client["client_id"];

        $serviceCount =
            $serviceCounts[$clientId] ?? 0;

        $domainCount =
            $domainCounts[$clientId] ?? 0;

        $serviceList =
            "";

        if (!empty($serviceLists[$clientId])) {
            $serviceList = implode("; ", $serviceLists[$clientId]);
        }

        $domainList =
            "";

        if (!empty($domainLists[$clientId])) {
            $domainList = implode("; ", $domainLists[$clientId]);
        }

        $records[] = array(

            "inventory_type" =>
                "Client",

            "client_id" =>
                $clientId,

            "client_first_name" =>
                $client["firstname"],

            "client_last_name" =>
                $client["lastname"],

            "client_company" =>
                $client["company"],

            "client_email" =>
                $client["email"],

            "client_phone" =>
                $client["phone"],

            /*
             * Client Summary
             */

            "service_count" =>
                $serviceCount,

            "domain_count" =>
                $domainCount,

            "service_list" =>
                $serviceList,

            "domain_list" =>
                $domainList,

            /*
             * Backward-compatible aliases from earlier Client Summary fields.
             */

            "active_services" =>
                $serviceCount,

            "active_domains" =>
                $domainCount

        );

    }

    return $records;
}

/**
 * Normalize Service Records
 */
function ucirMapServices($hostingStatuses = array())
{
    $records = array();

    if (!function_exists("ucirGetActiveServices")) {
        return $records;
    }

    $clients = ucirBuildClientLookup();

    $services = ucirGetActiveServices($hostingStatuses);

    foreach ($services as $service) {

        $client = array();

        if (isset($clients[$service["client_id"]])) {
            $client = $clients[$service["client_id"]];
        }

        $serviceArr = ucirCalculateHostingARR(
            $service["amount"],
            $service["billing_cycle"]
        );

        $records[] = array(

            "inventory_type" =>
                "Service",

            "client_id" =>
                $service["client_id"],

            "client_first_name" =>
                $client["firstname"] ?? "",

            "client_last_name" =>
                $client["lastname"] ?? "",

            "client_company" =>
                $client["company"] ?? "",

            "client_email" =>
                $client["email"] ?? "",

            "client_phone" =>
                $client["phone"] ?? "",

            /*
             * Service Information
             */

            "service_id" =>
                $service["service_id"],

            "product" =>
                $service["product"],

            "service_status" =>
                $service["service_status"],

            "service_domain" =>
                $service["service_domain"],

            "username" =>
                $service["username"],

            "server" =>
                $service["server"],

            "billing_cycle" =>
                $service["billing_cycle"],

            "amount" =>
                ucirFormatCurrencyAmount(
                    $service["amount"]
                ),

            "service_arr" =>
                ucirFormatCurrencyAmount(
                    $serviceArr
                ),

            "service_projected_revenue_this_month" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateProjectedRevenue(
                        $service["amount"],
                        ucirGetHostingRenewalIntervalMonths(
                            $service["billing_cycle"]
                        ),
                        $service["next_due_date"],
                        UCIR_WINDOW_THIS_MONTH
                    )
                ),

            "service_projected_revenue_next_3_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateProjectedRevenue(
                        $service["amount"],
                        ucirGetHostingRenewalIntervalMonths(
                            $service["billing_cycle"]
                        ),
                        $service["next_due_date"],
                        UCIR_WINDOW_NEXT_3_MONTHS
                    )
                ),

            "service_projected_revenue_next_6_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateProjectedRevenue(
                        $service["amount"],
                        ucirGetHostingRenewalIntervalMonths(
                            $service["billing_cycle"]
                        ),
                        $service["next_due_date"],
                        UCIR_WINDOW_NEXT_6_MONTHS
                    )
                ),

            "service_projected_revenue_next_12_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateProjectedRevenue(
                        $service["amount"],
                        ucirGetHostingRenewalIntervalMonths(
                            $service["billing_cycle"]
                        ),
                        $service["next_due_date"],
                        UCIR_WINDOW_NEXT_12_MONTHS
                    )
                ),

            "registration_date" =>
                $service["registration_date"],

            "next_due_date" =>
                $service["next_due_date"]

        );

    }

    return $records;
}

/**
 * Normalize Domain Records
 */
function ucirMapDomains($domainStatuses = array())
{
    $records = array();

    if (!function_exists("ucirGetActiveDomains")) {
        return $records;
    }

    $clients = ucirBuildClientLookup();

    $domains = ucirGetActiveDomains($domainStatuses);

    foreach ($domains as $domain) {

        $client = array();

        if (isset($clients[$domain["client_id"]])) {
            $client = $clients[$domain["client_id"]];
        }

        $domainArr = ucirCalculateDomainARR(
            $domain["recurring_amount"],
            $domain["registration_period"]
        );

        $records[] = array(

            "inventory_type" =>
                "Domain",

            "client_id" =>
                $domain["client_id"],

            "client_first_name" =>
                $client["firstname"] ?? "",

            "client_last_name" =>
                $client["lastname"] ?? "",

            "client_company" =>
                $client["company"] ?? "",

            "client_email" =>
                $client["email"] ?? "",

            "client_phone" =>
                $client["phone"] ?? "",

            "domain_name" =>
                $domain["domain"],

            "registrar" =>
                $domain["registrar"],

            "registration_date" =>
                $domain["registration_date"],

            "expiry_date" =>
                $domain["expiry_date"],

            "next_due_date" =>
                $domain["next_due_date"],

            "domain_recurring_amount" =>
                ucirFormatCurrencyAmount(
                    $domain["recurring_amount"]
                ),

            "domain_registration_period" =>
                $domain["registration_period"],

            "domain_arr" =>
                ucirFormatCurrencyAmount(
                    $domainArr
                ),

            "domain_projected_revenue_this_month" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateDomainProjectedARR(
                        $domainArr,
                        $domain["next_due_date"],
                        UCIR_WINDOW_THIS_MONTH
                    )
                ),

            "domain_projected_revenue_next_3_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateDomainProjectedARR(
                        $domainArr,
                        $domain["next_due_date"],
                        UCIR_WINDOW_NEXT_3_MONTHS
                    )
                ),

            "domain_projected_revenue_next_6_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateDomainProjectedARR(
                        $domainArr,
                        $domain["next_due_date"],
                        UCIR_WINDOW_NEXT_6_MONTHS
                    )
                ),

            "domain_projected_revenue_next_12_months" =>
                ucirFormatCurrencyAmount(
                    ucirCalculateDomainProjectedARR(
                        $domainArr,
                        $domain["next_due_date"],
                        UCIR_WINDOW_NEXT_12_MONTHS
                    )
                ),

            "domain_status" =>
                $domain["status"]

        );

    }

    return $records;
}

/**
 * Build Complete UCIR Inventory
 */
function ucirBuildInventory($hostingStatuses = array(), $domainStatuses = array())
{
    $inventory = array();

    $inventory = array_merge(
        $inventory,
        ucirMapServices($hostingStatuses),
        ucirMapDomains($domainStatuses)
    );

    return $inventory;
}
