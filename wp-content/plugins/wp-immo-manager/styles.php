<?php

function wpi_single_style()
{
    ?>
    <style type="text/css">

        #main {
            padding: 0;
        }

        .type-wpi_immobilie {
            padding: 0 !important;
        }

        /*Nav-Tabs li a*/
        .wpi_immobilie .nav-tabs > li > a {
            color: rgb(114, 117, 121);
            font-weight: bold;
        }

        /*Überschrift h2*/
        .wpi_immobilie h2, .single-wpi_immobilie .aside h2 {
            font-size: 21px;
            font-size: 1.5em;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 0.5em;
            border-radius: 5px;
            box-shadow: 2px 2px 3px #0d0d0d;
        }

        .wpi_immobilie h2 > span {
            padding-right: 2em;
        }

        /*Tab-Content*/
        .wpi_immobilie .tab-content {
            background: #fff;
            padding: 1em;
            border-radius: 0 0.5em 0.5em 0.5em;
            margin-left: 1px;
        }

        .wpi_immobilie .tab-content > .active {
            display: inline-block;
        }

        /*Details*/
        .wpi_immobilie .details {
            background-color: #E8E8E8;
            margin: 0.5em;
            box-shadow: 2px 2px 2px #736D6D;
            padding: 0;
        }

        .wpi_immobilie .details h3 {
            font-size: 21px;
            font-size: 1.5em;
            background: #ADADAD;
            color: #fff;
            padding: 0.5em;
            border-radius: 5px;
            box-shadow: 1px 2px 3px #908A8A;
            margin-top: 0;
        }

        .wpi_immobilie .details-inner {
            padding: 0.5em;
        }

        .wpi_immobilie .details-inner dt {
            text-align: left;
        }

        /**********Media**************/

        .wpi_immobilie #media {
            display: block;
        }

        /***** Aside ***********/
        .single-wpi_immobilie .aside .btn-group {
            margin-top: 3em;
            padding: 0;
        }
    </style>
<?php
}

?>

<?php

function wpi_archive_style()
{
    ?>
    <style type="text/css">

    </style>
<?php
}

?>