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
 * File: field_definitions.php
 * Purpose: Defines the available report fields and their metadata for
 *          use throughout the UCIR reporting framework.
 *
 * Version: 0.7.0
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function ucirGetFieldDefinitions()
{
    return array(

        "inventory_type"=>array(
            "id"=>"inventory_type","label"=>"Inventory Type","category"=>"Inventory Information",
            "source"=>"UCIR inventory mapper","map"=>"inventory_type","default"=>true
        ),

        "client_id"=>array(
            "id"=>"client_id","label"=>"Client ID","category"=>"Client Information",
            "source"=>"tblclients.id","map"=>"client_id","default"=>true
        ),
        "client_first_name"=>array(
            "id"=>"client_first_name","label"=>"First Name","category"=>"Client Information",
            "source"=>"tblclients.firstname","map"=>"client_first_name","default"=>true
        ),
        "client_last_name"=>array(
            "id"=>"client_last_name","label"=>"Last Name","category"=>"Client Information",
            "source"=>"tblclients.lastname","map"=>"client_last_name","default"=>true
        ),
        "client_company"=>array(
            "id"=>"client_company","label"=>"Company","category"=>"Client Information",
            "source"=>"tblclients.companyname","map"=>"client_company","default"=>true
        ),
        "client_email"=>array(
            "id"=>"client_email","label"=>"Email Address","category"=>"Client Information",
            "source"=>"tblclients.email","map"=>"client_email","default"=>true
        ),
        "client_phone"=>array(
            "id"=>"client_phone","label"=>"Phone Number","category"=>"Client Information",
            "source"=>"tblclients.phonenumber","map"=>"client_phone","default"=>false
        ),

        "service_id"=>array("id"=>"service_id","label"=>"Service ID","category"=>"Hosting Information","source"=>"tblhosting.id","map"=>"service_id","default"=>false),
        "service_product"=>array("id"=>"service_product","label"=>"Product / Service","category"=>"Hosting Information","source"=>"tblproducts.name","map"=>"product","default"=>true),
        "service_status"=>array("id"=>"service_status","label"=>"Service Status","category"=>"Hosting Information","source"=>"tblhosting.domainstatus","map"=>"service_status","default"=>true),
        "service_domain"=>array("id"=>"service_domain","label"=>"Service Domain","category"=>"Hosting Information","source"=>"tblhosting.domain","map"=>"service_domain","default"=>true),
        "service_username"=>array("id"=>"service_username","label"=>"Username","category"=>"Hosting Information","source"=>"tblhosting.username","map"=>"username","default"=>true),
        "service_server"=>array("id"=>"service_server","label"=>"Server","category"=>"Hosting Information","source"=>"tblservers.name","map"=>"server","default"=>false),
        "service_billing_cycle"=>array("id"=>"service_billing_cycle","label"=>"Billing Cycle","category"=>"Hosting Information","source"=>"tblhosting.billingcycle","map"=>"billing_cycle","default"=>false),
        "service_amount"=>array("id"=>"service_amount","label"=>"Recurring Amount","category"=>"Hosting Information","source"=>"tblhosting.amount","map"=>"amount","default"=>false),
        "service_registration_date"=>array("id"=>"service_registration_date","label"=>"Service Registration Date","category"=>"Hosting Information","source"=>"tblhosting.regdate","map"=>"registration_date","default"=>false),
        "service_next_due_date"=>array("id"=>"service_next_due_date","label"=>"Service Next Due Date","category"=>"Hosting Information","source"=>"tblhosting.nextduedate","map"=>"next_due_date","default"=>false),

        "domain_name"=>array("id"=>"domain_name","label"=>"Domain Name","category"=>"Domain Information","source"=>"tbldomains.domain","map"=>"domain_name","default"=>true),
        "domain_registrar"=>array("id"=>"domain_registrar","label"=>"Registrar","category"=>"Domain Information","source"=>"tbldomains.registrar","map"=>"registrar","default"=>false),
        "domain_registration_date"=>array("id"=>"domain_registration_date","label"=>"Registration Date","category"=>"Domain Information","source"=>"tbldomains.registrationdate","map"=>"registration_date","default"=>false),
        "domain_expiry_date"=>array("id"=>"domain_expiry_date","label"=>"Expiry Date","category"=>"Domain Information","source"=>"tbldomains.expirydate","map"=>"expiry_date","default"=>false),
        "domain_next_due_date"=>array("id"=>"domain_next_due_date","label"=>"Next Due Date","category"=>"Domain Information","source"=>"tbldomains.nextduedate","map"=>"next_due_date","default"=>false),

        "client_service_count"=>array(
            "id"=>"client_service_count",
            "label"=>"Hosting Service Count",
            "category"=>"Client Summary",
            "source"=>"Calculated",
            "map"=>"service_count",
            "default"=>false
        ),
        "client_domain_count"=>array(
            "id"=>"client_domain_count",
            "label"=>"Domain Count",
            "category"=>"Client Summary",
            "source"=>"Calculated",
            "map"=>"domain_count",
            "default"=>false
        ),
        "client_services"=>array(
            "id"=>"client_services",
            "label"=>"Hosting Services",
            "category"=>"Client Summary",
            "source"=>"Calculated",
            "map"=>"service_list",
            "default"=>false
        ),
        "client_domains"=>array(
            "id"=>"client_domains",
            "label"=>"Domains",
            "category"=>"Client Summary",
            "source"=>"Calculated",
            "map"=>"domain_list",
            "default"=>false
        )

    );
}
