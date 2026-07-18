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
 * File: revenue_helper.php
 * Purpose: Centralizes recurring-revenue calculations used throughout
 *          the UCIR reporting framework.
 *
 * Version: 1.1.0
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

if (!defined("UCIR_WINDOW_THIS_MONTH")) {
    define("UCIR_WINDOW_THIS_MONTH", "this_month");
}

if (!defined("UCIR_WINDOW_NEXT_3_MONTHS")) {
    define("UCIR_WINDOW_NEXT_3_MONTHS", "next_3_months");
}

if (!defined("UCIR_WINDOW_NEXT_6_MONTHS")) {
    define("UCIR_WINDOW_NEXT_6_MONTHS", "next_6_months");
}

if (!defined("UCIR_WINDOW_NEXT_12_MONTHS")) {
    define("UCIR_WINDOW_NEXT_12_MONTHS", "next_12_months");
}

function ucirNormalizeRecurringAmount($amount)
{
    if (!is_numeric($amount)) {
        return 0.0;
    }

    return max(0.0, (float) $amount);
}

/**
 * Format a monetary value for CSV output.
 *
 * @param mixed $amount
 * @return string
 */
function ucirFormatCurrencyAmount($amount)
{
    return number_format(
        ucirNormalizeRecurringAmount($amount),
        2,
        ".",
        ""
    );
}

function ucirCalculateHostingARR($amount, $billingCycle)
{
    $amount = ucirNormalizeRecurringAmount($amount);
    $cycle = strtolower(trim((string) $billingCycle));

    $multipliers = array(
        "monthly" => 12,
        "quarterly" => 4,
        "semi-annually" => 2,
        "semi annually" => 2,
        "semiannual" => 2,
        "annually" => 1,
        "annual" => 1,
        "yearly" => 1,
        "biennially" => 0.5,
        "biennial" => 0.5,
        "triennially" => (1 / 3),
        "triennial" => (1 / 3)
    );

    if (!isset($multipliers[$cycle])) {
        return 0.0;
    }

    return round($amount * $multipliers[$cycle], 2);
}

function ucirCalculateDomainARR($recurringAmount, $registrationPeriod)
{
    $amount = ucirNormalizeRecurringAmount($recurringAmount);

    if (!is_numeric($registrationPeriod)) {
        return 0.0;
    }

    $period = (int) $registrationPeriod;

    if ($period < 1) {
        return 0.0;
    }

    return round($amount / $period, 2);
}


/**
 * Convert a WHMCS hosting billing cycle to its renewal interval in months.
 *
 * @param string $billingCycle
 * @return int
 */
function ucirGetHostingRenewalIntervalMonths($billingCycle)
{
    $cycle = strtolower(trim((string) $billingCycle));

    $intervals = array(
        "monthly" => 1,
        "quarterly" => 3,
        "semi-annually" => 6,
        "semi annually" => 6,
        "semiannual" => 6,
        "annually" => 12,
        "annual" => 12,
        "yearly" => 12,
        "biennially" => 24,
        "biennial" => 24,
        "triennially" => 36,
        "triennial" => 36
    );

    if (!isset($intervals[$cycle])) {
        return 0;
    }

    return $intervals[$cycle];
}

/**
 * Convert a domain registration period in years to months.
 *
 * @param mixed $registrationPeriod
 * @return int
 */
function ucirGetDomainRenewalIntervalMonths($registrationPeriod)
{
    if (!is_numeric($registrationPeriod)) {
        return 0;
    }

    $period = (int) $registrationPeriod;

    if ($period < 1) {
        return 0;
    }

    return $period * 12;
}

/**
 * Return the start and end dates for a named projection window.
 *
 * This Month includes the current calendar month.
 * Future windows begin on the first day of the next calendar month.
 *
 * @param string $windowType
 * @param DateTimeInterface|null $referenceDate
 * @return array
 */
function ucirGetProjectionWindow($windowType, $referenceDate = null)
{
    if ($referenceDate instanceof DateTimeInterface) {
        $reference = new DateTimeImmutable(
            $referenceDate->format("Y-m-d")
        );
    } else {
        $reference = new DateTimeImmutable("today");
    }

    $currentMonthStart = $reference->modify("first day of this month");
    $nextMonthStart = $reference->modify("first day of next month");

    switch ($windowType) {

        case UCIR_WINDOW_THIS_MONTH:

            $start = $currentMonthStart;

            $end = $reference->modify(
                "last day of this month"
            );

            break;

        case UCIR_WINDOW_NEXT_3_MONTHS:

            $start = $nextMonthStart;

            $end = $nextMonthStart
                ->modify("+3 months")
                ->modify("-1 day");

            break;

        case UCIR_WINDOW_NEXT_6_MONTHS:

            $start = $nextMonthStart;

            $end = $nextMonthStart
                ->modify("+6 months")
                ->modify("-1 day");

            break;

        case UCIR_WINDOW_NEXT_12_MONTHS:

            $start = $nextMonthStart;

            $end = $nextMonthStart
                ->modify("+12 months")
                ->modify("-1 day");

            break;

        default:

            return array(
                "start" => "",
                "end" => ""
            );

    }

    return array(
        "start" => $start->format("Y-m-d"),
        "end" => $end->format("Y-m-d")
    );
}

/**
 * Calculate hosting revenue expected within a projection window.
 *
 * The recurring amount is the actual charge for one billing cycle. Each
 * renewal date inside the selected window contributes one recurring charge.
 * This means monthly services contribute once per month, quarterly services
 * once per quarter, and so on.
 *
 * @param mixed  $recurringAmount
 * @param int    $renewalIntervalMonths
 * @param string $nextDueDate
 * @param string $windowType
 * @return float
 */
function ucirCalculateProjectedRevenue(
    $recurringAmount,
    $renewalIntervalMonths,
    $nextDueDate,
    $windowType
) {
    $amount = ucirNormalizeRecurringAmount($recurringAmount);
    $interval = (int) $renewalIntervalMonths;

    if ($amount <= 0 || $interval < 1) {
        return 0.0;
    }

    $nextDueDate = trim((string) $nextDueDate);

    if ($nextDueDate === "" || $nextDueDate === "0000-00-00") {
        return 0.0;
    }

    $window = ucirGetProjectionWindow($windowType);

    if (empty($window["start"]) || empty($window["end"])) {
        return 0.0;
    }

    try {
        $windowStart = new DateTimeImmutable($window["start"]);
        $windowEnd = new DateTimeImmutable($window["end"]);
        $dueDate = new DateTimeImmutable($nextDueDate);
    } catch (Exception $exception) {
        return 0.0;
    }

    $total = 0.0;

    while ($dueDate <= $windowEnd) {
        if ($dueDate >= $windowStart) {
            $total += $amount;
        }

        $dueDate = $dueDate->modify("+{$interval} months");
    }

    return round($total, 2);
}


/**
 * Return domain ARR when the domain's authoritative WHMCS Next Due Date
 * falls inside the selected projection window.
 *
 * A domain's previous multi-year registration period is intentionally not
 * used to advance or infer its next renewal date. The registration period
 * is used only when annualizing the recurring amount into ARR.
 *
 * @param mixed  $annualRecurringRevenue
 * @param string $nextDueDate
 * @param string $windowType
 * @return float
 */
function ucirCalculateDomainProjectedARR(
    $annualRecurringRevenue,
    $nextDueDate,
    $windowType
) {
    $amount = ucirNormalizeRecurringAmount($annualRecurringRevenue);

    if ($amount <= 0) {
        return 0.0;
    }

    $nextDueDate = trim((string) $nextDueDate);

    if ($nextDueDate === "" || $nextDueDate === "0000-00-00") {
        return 0.0;
    }

    $window = ucirGetProjectionWindow($windowType);

    if (empty($window["start"]) || empty($window["end"])) {
        return 0.0;
    }

    try {
        $windowStart = new DateTimeImmutable($window["start"]);
        $windowEnd = new DateTimeImmutable($window["end"]);
        $dueDate = new DateTimeImmutable($nextDueDate);
    } catch (Exception $exception) {
        return 0.0;
    }

    if ($dueDate >= $windowStart && $dueDate <= $windowEnd) {
        return round($amount, 2);
    }

    return 0.0;
}

