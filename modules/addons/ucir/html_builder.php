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
 * File: html_builder.php
 * Purpose: Builds and renders the UCIR user interface, including report
 *          options, filters, field selection, and validation messages.
 *
 * Version: 1.1.0
 */


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


/**
 * Build UCIR Report Interface
 */

function ucirBuildInterface()
{


    $ucirNotice = "";


    /*
     * Handle CSV Generation
     */

    if (isset($_POST["ucir_generate"])) {


        $selectedFields =
            $_POST["ucir_fields"] ?? array();


        if (empty($selectedFields)) {


            $ucirNotice = "

            <div class='alert alert-warning'>

                Please select at least one field
                before generating the report.

            </div>

            ";


        } else if (function_exists("ucirGenerateCSV")) {


            $result =
                ucirGenerateCSV(
                    $selectedFields
                );


            if (!empty($result)) {

                $ucirNotice =
                    $result;

            }


        } else {


            $ucirNotice = "

            <div class='alert alert-danger'>

                UCIR CSV export engine is missing.

            </div>

            ";


        }


    }



    /*
     * Handle Database Test
     */

    if (isset($_POST["ucir_test"])) {


        if (function_exists("ucirRunDiagnostics")) {

            return ucirRunDiagnostics();

        }


    }



    /*
     * Load Assets
     */

    $systemUrl =
        rtrim(
            \WHMCS\Config\Setting::getValue("SystemURL"),
            "/"
        );


    echo '

    <link rel="stylesheet"
    href="' . $systemUrl . '/modules/addons/ucir/assets/css/ucir.css?v=4">

    ';



    /*
     * Load Fields
     */

    if (!function_exists("ucirGetFieldDefinitions")) {


        return "

        <div class='alert alert-danger'>

            UCIR field definitions are missing.

        </div>

        ";

    }



    $fields =
        ucirGetFieldDefinitions();



    /*
     * Group Fields
     */

    $categories = array();



    foreach ($fields as $field) {


        $categories[$field["category"]][] =
            $field;


    }



$html = "

<div class='ucir-container'>

    " . $ucirNotice . "

    <p>
        <i>
            Generate customizable inventory reports for your WHMCS installation.
        </i>
    </p>

    <hr>

    <h3>
        1. Report Type
    </h3>

    <p class='text-muted'>
        Choose what each row of the report should represent.
    </p>

    <div class='ucir-report-types'>

        <label class='ucir-report-type'>

            <input
                type='radio'
                name='report_type'
                class='ucir-report-selector'
                value='inventory'
                checked
            >

            <strong>Complete Inventory Report</strong><br>

            <small>
                One row for every active service and active domain.
            </small>

        </label>

        <label class='ucir-report-type'>

            <input
                type='radio'
                name='report_type'
                class='ucir-report-selector'
                value='client'
            >

            <strong>Client Report</strong><br>

            <small>
                One row for every active client.
            </small>

        </label>

        <label class='ucir-report-type'>

            <input
                type='radio'
                name='report_type'
                class='ucir-report-selector'
                value='service'
            >

            <strong>Hosting Services Report</strong><br>

            <small>
                One row for every active hosting service.
            </small>

        </label>

        <label class='ucir-report-type'>

            <input
                type='radio'
                name='report_type'
                class='ucir-report-selector'
                value='domain'
            >

            <strong>Domain Report</strong><br>

            <small>
                One row for every active domain.
            </small>

        </label>

    </div>

    <hr>

    <h3>
        2. Report Filters
    </h3>

    <p class='text-muted'>
        Choose which records should be included in the export.
    </p>

    <form method='post' action=''>

        <input
            type='hidden'
            name='ucir_report_type'
            id='ucir_report_type'
            value='inventory'
        >


        <fieldset class='ucir-field-group ucir-filter-group' data-report-types='inventory service client'>

            <legend>
                Hosting Status
            </legend>

            <p class='text-muted'>
                Choose which hosting service statuses to include. This filter applies to Hosting Services and Complete Inventory reports.
            </p>

            <div class='ucir-group-controls'>

                <button
                    type='button'
                    class='btn btn-xs btn-default ucir-group-select'
                    data-target='hostingstatusfilter'
                >
                    Select All
                </button>

                <button
                    type='button'
                    class='btn btn-xs btn-default ucir-group-clear'
                    data-target='hostingstatusfilter'
                >
                    Select None
                </button>

            </div>

            <div class='checkbox'>
                <label>
                    <input
                        type='checkbox'
                        class='ucir-field hostingstatusfilter'
                        name='ucir_hosting_statuses[]'
                        value='Active'
                        checked
                    >
                    Active
                </label>
            </div>

            <div class='checkbox'>
                <label>
                    <input
                        type='checkbox'
                        class='ucir-field hostingstatusfilter'
                        name='ucir_hosting_statuses[]'
                        value='Suspended'
                    >
                    Suspended
                </label>
            </div>

            <div class='checkbox'>
                <label>
                    <input
                        type='checkbox'
                        class='ucir-field hostingstatusfilter'
                        name='ucir_hosting_statuses[]'
                        value='Pending'
                    >
                    Pending
                </label>
            </div>

            <div class='checkbox'>
                <label>
                    <input
                        type='checkbox'
                        class='ucir-field hostingstatusfilter'
                        name='ucir_hosting_statuses[]'
                        value='Cancelled'
                    >
                    Cancelled
                </label>
            </div>

            <div class='checkbox'>
                <label>
                    <input
                        type='checkbox'
                        class='ucir-field hostingstatusfilter'
                        name='ucir_hosting_statuses[]'
                        value='Terminated'
                    >
                    Terminated
                </label>
            </div>

        </fieldset>

        <div style='margin-top:20px;'></div>

        <fieldset class='ucir-field-group ucir-filter-group' data-report-types='inventory domain client'>

            <legend>
                Domain Status
            </legend>

            <p class='text-muted'>
                Choose which domain statuses to include. This filter applies to Domain and Complete Inventory reports.
            </p>

            <div class='ucir-group-controls'>
                <button type='button' class='btn btn-xs btn-default ucir-group-select' data-target='domainstatusfilter'>Select All</button>
                <button type='button' class='btn btn-xs btn-default ucir-group-clear' data-target='domainstatusfilter'>Select None</button>
            </div>

            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Active' checked> Active</label></div>
            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Expired'> Expired</label></div>
            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Grace'> Grace</label></div>
            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Redemption'> Redemption</label></div>
            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Pending'> Pending</label></div>
            <div class='checkbox'><label><input type='checkbox' class='ucir-field domainstatusfilter' name='ucir_domain_statuses[]' value='Cancelled'> Cancelled</label></div>

        </fieldset>

    <hr>

    <h3>
        3. Report Fields
    </h3>

    <p class='text-muted'>
        Select the information you want included as columns in the export.
    </p>

    <div class='ucir-global-controls'>


        <button
            type='button'
            class='btn btn-default'
            id='ucir-select-all'
        >
            Select All Fields
        </button>



        <button
            type='button'
            class='btn btn-default'
            id='ucir-clear-all'
        >
            Clear All Fields
        </button>


    </div>


    <hr>


    ";



    foreach ($categories as $category => $categoryFields) {



        $categoryId =
            strtolower(
                preg_replace(
                    '/[^a-zA-Z0-9]/',
                    '',
                    $category
                )
            );



        $fieldGroupReportTypes = array(
            "Inventory Information" =>
                "inventory client service domain",

            "Client Information" =>
                "inventory client service domain",

            "Hosting Information" =>
                "inventory service",

            "Domain Information" =>
                "inventory domain",

            "Client Summary" =>
                "inventory client"
        );


        $allowedReportTypes =
            $fieldGroupReportTypes[$category]
            ?? "inventory";


        $html .= "


        <fieldset
            class='ucir-field-group ucir-report-field-group'
            data-report-types='" . htmlspecialchars($allowedReportTypes) . "'
        >


            <legend>
                " . htmlspecialchars($category) . "
            </legend>



            <div class='ucir-group-controls'>


                <button
                    type='button'
                    class='btn btn-xs btn-default ucir-group-select'
                    data-target='{$categoryId}'
                >
                    Select All
                </button>



                <button
                    type='button'
                    class='btn btn-xs btn-default ucir-group-clear'
                    data-target='{$categoryId}'
                >
                    Select None
                </button>


            </div>


        ";



        $projectionFields = array();
        $standardFields = array();


        foreach ($categoryFields as $field) {


            if (
                ($category === "Hosting Information" || $category === "Domain Information")
                && strpos($field["id"], "_projection_") !== false
            ) {


                $projectionFields[] =
                    $field;


            } else {


                $standardFields[] =
                    $field;


            }


        }


        foreach ($standardFields as $field) {


            $checked = "";


            if (!empty($field["default"])) {


                $checked =
                    "checked";


            }


            $html .= "


            <div class='checkbox'>


                <label>


                    <input

                        type='checkbox'

                        class='ucir-field {$categoryId}'

                        name='ucir_fields[]'

                        value='" . htmlspecialchars($field["id"]) . "'

                        {$checked}

                    >


                    " . htmlspecialchars($field["label"]) . "


                </label>


            </div>


            ";


        }


        if (!empty($projectionFields)) {


            $projectionGroupKey =
                ($category === "Hosting Information")
                ? "hosting"
                : "domain";


            $html .= "


            <div class='checkbox ucir-projection-parent'>


                <label>


                    <input

                        type='checkbox'

                        class='ucir-projection-toggle'

                        data-projection-group='" . htmlspecialchars($projectionGroupKey) . "'

                    >


                    Projected Revenue


                </label>


            </div>


            <div
                class='ucir-projection-children'
                data-projection-children='" . htmlspecialchars($projectionGroupKey) . "'
                style='overflow:hidden; max-height:0; opacity:0; margin-top:0; margin-left:28px; pointer-events:none; transition:max-height 180ms ease, opacity 180ms ease, margin-top 180ms ease;'
            >


            ";


            $projectionLabels = array(
                "this_month" => "This Month",
                "next_3_months" => "Next 3 Months",
                "next_6_months" => "Next 6 Months",
                "next_12_months" => "Next 12 Months"
            );


            foreach ($projectionFields as $field) {


                $checked = "";


                if (!empty($field["default"])) {


                    $checked =
                        "checked";


                }


                $childLabel =
                    $field["label"];


                foreach ($projectionLabels as $suffix => $shortLabel) {


                    if (substr($field["id"], -strlen($suffix)) === $suffix) {


                        $childLabel =
                            $shortLabel;


                        break;


                    }


                }


                $html .= "


                <div class='checkbox ucir-projection-child'>


                    <label>


                        <input

                            type='checkbox'

                            class='ucir-field {$categoryId} ucir-projection-field'

                            name='ucir_fields[]'

                            value='" . htmlspecialchars($field["id"]) . "'

                            data-projection-group='" . htmlspecialchars($projectionGroupKey) . "'

                            {$checked}

                        >


                        " . htmlspecialchars($childLabel) . "


                    </label>


                </div>


                ";


            }


            $html .= "


            </div>


            ";


        }



        $html .= "

        </fieldset>

        ";


    }



    $html .= "


        <hr>

        <h3>
            4. Generate Report
        </h3>



        <button

            type='submit'

            name='ucir_generate'

            class='btn btn-primary'

        >

            Generate CSV Report

        </button>



        <button

            type='submit'

            name='ucir_test'

            class='btn btn-default'

        >

            Test Database Connection

        </button>



        </form>


    </div>


    ";

$html .= "

<script>

(function(){

    'use strict';


    function ucirGetReportType()
    {
        var selected =
            document.querySelector(
                'input[name=\"report_type\"]:checked'
            );

        return selected
            ? selected.value
            : 'inventory';
    }


    function ucirSetGroupAvailability(group, isVisible)
    {
        group.style.display =
            isVisible ? '' : 'none';

        group.querySelectorAll(
            'input, select, textarea, button'
        )
        .forEach(function(control){

            control.disabled =
                !isVisible;

        });
    }


    function ucirUpdateReportTypeDisplay(reportType)
    {
        var hiddenReportType =
            document.getElementById(
                'ucir_report_type'
            );

        if (hiddenReportType) {
            hiddenReportType.value =
                reportType;
        }

        document.querySelectorAll(
            '.ucir-report-type'
        )
        .forEach(function(card){

            var option =
                card.querySelector(
                    'input[name=\"report_type\"]'
                );

            card.classList.toggle(
                'is-selected',
                Boolean(
                    option &&
                    option.value === reportType
                )
            );
        });

        document.querySelectorAll(
            '.ucir-filter-group, .ucir-report-field-group'
        )
        .forEach(function(group){

            var allowedTypes =
                group.getAttribute(
                    'data-report-types'
                ) || '';

            var isVisible =
                allowedTypes
                .split(/\\s+/)
                .indexOf(reportType) !== -1;

            ucirSetGroupAvailability(
                group,
                isVisible
            );
        });

        ucirRefreshProjectionGroups();
    }


    function ucirGetProjectionContainer(groupKey)
    {
        return document.querySelector(
            '.ucir-projection-children' +
            '[data-projection-children=\"' +
            groupKey +
            '\"]'
        );
    }


    function ucirUpdateProjectionGroup(toggle, clearChildren)
    {
        var groupKey =
            toggle.getAttribute(
                'data-projection-group'
            );

        var childContainer =
            ucirGetProjectionContainer(
                groupKey
            );

        if (!childContainer) {
            return;
        }

        if (toggle.checked) {

            childContainer.style.pointerEvents =
                'auto';

            childContainer.style.marginTop =
                '4px';

            childContainer.style.opacity =
                '1';

            childContainer.style.maxHeight =
                childContainer.scrollHeight + 'px';

        } else {

            childContainer.style.maxHeight =
                '0';

            childContainer.style.opacity =
                '0';

            childContainer.style.marginTop =
                '0';

            childContainer.style.pointerEvents =
                'none';

            if (clearChildren) {

                childContainer.querySelectorAll(
                    '.ucir-projection-field'
                )
                .forEach(function(child){

                    child.checked =
                        false;

                });
            }
        }
    }


    function ucirSyncProjectionGroup(toggle)
    {
        var groupKey =
            toggle.getAttribute(
                'data-projection-group'
            );

        var checkedChild =
            document.querySelector(
                '.ucir-projection-field' +
                '[data-projection-group=\"' +
                groupKey +
                '\"]:checked:not(:disabled)'
            );

        toggle.checked =
            Boolean(checkedChild);

        ucirUpdateProjectionGroup(
            toggle,
            false
        );
    }


    function ucirRefreshProjectionGroups()
    {
        document.querySelectorAll(
            '.ucir-projection-toggle'
        )
        .forEach(function(toggle){

            ucirSyncProjectionGroup(
                toggle
            );

        });
    }


    function ucirSetCheckboxes(selector, checked)
    {
        document.querySelectorAll(selector)
        .forEach(function(field){

            if (!field.disabled) {
                field.checked =
                    checked;
            }
        });

        ucirRefreshProjectionGroups();
    }


    function ucirInitReportTypes()
    {
        document.querySelectorAll(
            'input[name=\"report_type\"]'
        )
        .forEach(function(option){

            option.addEventListener(
                'change',
                function(){

                    ucirUpdateReportTypeDisplay(
                        this.value
                    );

                }
            );
        });

        ucirUpdateReportTypeDisplay(
            ucirGetReportType()
        );
    }


    function ucirInitGlobalSelection()
    {
        var selectAll =
            document.getElementById(
                'ucir-select-all'
            );

        var clearAll =
            document.getElementById(
                'ucir-clear-all'
            );

        if (selectAll) {
            selectAll.addEventListener(
                'click',
                function(){

                    ucirSetCheckboxes(
                        '.ucir-report-field-group ' +
                        'input.ucir-field',
                        true
                    );

                }
            );
        }

        if (clearAll) {
            clearAll.addEventListener(
                'click',
                function(){

                    ucirSetCheckboxes(
                        '.ucir-report-field-group ' +
                        'input.ucir-field',
                        false
                    );

                }
            );
        }
    }


    function ucirInitGroupSelection()
    {
        document.querySelectorAll(
            '.ucir-group-select, .ucir-group-clear'
        )
        .forEach(function(button){

            button.addEventListener(
                'click',
                function(){

                    var targetClass =
                        this.getAttribute(
                            'data-target'
                        );

                    if (!targetClass) {
                        return;
                    }

                    var shouldCheck =
                        this.classList.contains(
                            'ucir-group-select'
                        );

                    ucirSetCheckboxes(
                        'input.' + targetClass,
                        shouldCheck
                    );

                }
            );
        });
    }


    function ucirInitProjectionGroups()
    {
        document.querySelectorAll(
            '.ucir-projection-toggle'
        )
        .forEach(function(toggle){

            toggle.addEventListener(
                'change',
                function(){

                    ucirUpdateProjectionGroup(
                        this,
                        true
                    );

                }
            );
        });

        document.querySelectorAll(
            '.ucir-projection-field'
        )
        .forEach(function(child){

            child.addEventListener(
                'change',
                function(){

                    var groupKey =
                        this.getAttribute(
                            'data-projection-group'
                        );

                    var toggle =
                        document.querySelector(
                            '.ucir-projection-toggle' +
                            '[data-projection-group=\"' +
                            groupKey +
                            '\"]'
                        );

                    if (toggle) {
                        ucirSyncProjectionGroup(
                            toggle
                        );
                    }
                }
            );
        });

        ucirRefreshProjectionGroups();
    }


    function ucirInit()
    {
        ucirInitProjectionGroups();
        ucirInitGlobalSelection();
        ucirInitGroupSelection();
        ucirInitReportTypes();
    }


    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            ucirInit
        );

    } else {

        ucirInit();

    }

})();

</script>


";

    return $html;


}
