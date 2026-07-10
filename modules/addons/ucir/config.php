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
 * File: config.php
 * Purpose: Defines configuration settings and shared constants used
 *          throughout the UCIR reporting framework.
 *
 * Version: 0.7.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


/**
 * UCIR Version
 */

define(
    "UCIR_VERSION",
    "0.7.0"
);


/**
 * Report Information
 */

define(
    "UCIR_REPORT_NAME",
    "Universal Client Inventory Report"
);


/**
 * Default Inventory Filters
 *
 * UCIR is designed around active inventory reporting.
 */

define(
    "UCIR_ACTIVE_CLIENTS_ONLY",
    true
);


define(
    "UCIR_ACTIVE_SERVICES_ONLY",
    true
);


define(
    "UCIR_ACTIVE_DOMAINS_ONLY",
    true
);


/**
 * CSV Export Settings
 */

define(
    "UCIR_CSV_DELIMITER",
    ","
);


define(
    "UCIR_CSV_ENCLOSURE",
    '"'
);


define(
    "UCIR_CSV_ENCODING",
    "UTF-8"
);


/**
 * Report Behavior
 */


/*
 * When true:
 * - Show field selector before export
 *
 * When false:
 * - Generate using defaults
 */

define(
    "UCIR_REQUIRE_FIELD_SELECTION",
    true
);


/**
 * Debug Mode
 *
 * Keep false on production systems.
 */

define(
    "UCIR_DEBUG",
    false
);