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
 * File: ucir.js
 * Purpose: Provides client-side behavior for the UCIR user interface,
 *          including conditional display logic and interactive controls.
 *
 * Version: 0.7.0
 */
 
document.addEventListener(
    "DOMContentLoaded",
    function () {


        const selectAll =
            document.getElementById(
                "ucir-select-all"
            );


        const clearAll =
            document.getElementById(
                "ucir-clear-all"
            );



        if (selectAll) {

            selectAll.onclick = function () {

                document
                .querySelectorAll(
                    ".ucir-field"
                )
                .forEach(
                    function(box){
                        box.checked = true;
                    }
                );

            };

        }



        if (clearAll) {

            clearAll.onclick = function () {

                document
                .querySelectorAll(
                    ".ucir-field"
                )
                .forEach(
                    function(box){
                        box.checked = false;
                    }
                );

            };

        }




        document
        .querySelectorAll(
            ".ucir-group-select"
        )
        .forEach(
            function(button){


                button.onclick = function(){


                    const target =
                        this.dataset.target;



                    document
                    .querySelectorAll(
                        "." + target
                    )
                    .forEach(
                        function(box){
                            box.checked = true;
                        }
                    );


                };


            }
        );




        document
        .querySelectorAll(
            ".ucir-group-clear"
        )
        .forEach(
            function(button){


                button.onclick = function(){


                    const target =
                        this.dataset.target;



                    document
                    .querySelectorAll(
                        "." + target
                    )
                    .forEach(
                        function(box){
                            box.checked = false;
                        }
                    );


                };


            }
        );


    }
);