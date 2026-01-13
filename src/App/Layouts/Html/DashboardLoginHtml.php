<?php

namespace App\Layouts\Html;

use App\BreadCrumbs;use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class DashboardLoginHtml extends BaseLayout implements LayoutInterface, ViewInterface
{

    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-Type: {$this->getContentMimeType()}; charset=utf-8");
        }

        if ($this->getLocationRedirectUri()) {
            header(sprintf("Location: %s", $this->getLocationRedirectUri()));
            return;
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php print $this->getWindowTitle()?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

    <!-- build:css styles/vendor.css -->
    <!-- bower:css -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/bower_components/animate.css/animate.css">
    <link rel="stylesheet" href="/bower_components/hover/css/hover.css">
    <link rel="stylesheet" href="/bower_components/pnotify/pnotify.core.css">
    <link rel="stylesheet" href="/bower_components/imgareaselect/distfiles/css/imgareaselect-default.css">
    <link rel="stylesheet" href="/bower_components/morris.js/morris.css">

    <!-- endbower -->
    <!-- endbuild -->

    <!-- build:css(.tmp) styles/main.css -->
    <link id="style-components" href="/css/dashboard/loaders.css" rel="stylesheet">
    <link id="style-components" href="/css/dashboard/bootstrap-theme.css?<?php print filemtime(APP_DOCUMENT_ROOT . "/public/css/dashboard/bootstrap-theme.css")?>" rel="stylesheet">
    <link id="style-components" href="/css/dashboard/dependencies.css?<?php print filemtime(APP_DOCUMENT_ROOT . "/public/css/dashboard/dependencies.css")?>" rel="stylesheet">
    <link id="style-base" href="/css/dashboard/stilearn.css" rel="stylesheet">
    <link id="style-responsive" href="/css/dashboard/stilearn-responsive.css" rel="stylesheet">
    <link id="style-helper" href="/css/dashboard/helper.css" rel="stylesheet">
    <!-- endbuild -->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="/bower_components/html5shiv/dist/html5shiv.min.js"></script>
    <![endif]-->

    <script src="/bower_components/jquery/dist/jquery.js"></script>
    <script src="/bower_components/jqueryui/jquery-ui.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.js"></script>

    <style type="text/css">
        header a {
            font-weight: 100;
        }
        footer {
            font-size: 12px;
        }
        a[rel=to-top] {
            right: auto;
            left: 25px;
            bottom: 60px;
        }
        .sidebar {
            border: none;
        }
        .sidebar > li {
            border: none;
            padding: 0 0 15px 0;
        }
        .sidebar > li > a {
            font-size: 12px;
        }
    </style>

</head>

<body class="animated fadeIn">
<div class="content content-full">
    <div class="container">
        <div class="signin-wrapper">
            <?php print $this->getContent();?>
        </div>
    </div>
</div>

<!-- section footer -->
<footer style="margin-top: -40px">
    <div class="pull-left">Antminer machines monitoring<span class="hidden-xs">, control & automation board</span> <span class="label label-success">BETA</span></div>
    <div class="pull-right">
        <span class="hidden-md hidden-sm hidden-xs">Cryptocurency Mining industrial processes Monitoring, Automation & Controlling software solutions. </span>
        <span class="hidden-xs">Collaboration of self-initiated Saas Developers</span>
    </div>
</footer>

<script src="/bower_components/wow/dist/wow.min.js"></script>
<script src="/bower_components/TableDnD/js/jquery.tablednd.js"></script>
<script src="/bower_components/select2/select2.js"></script>
<script src="/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/bower_components/bootstrap-datepicker/js/locales/bootstrap-datepicker.ru.js"></script>
<script src="/bower_components/jquery-form/jquery.form.js"></script>
<script src="/bower_components/pnotify/pnotify.core.js"></script>
<script src="/bower_components/imgareaselect/jquery.imgareaselect.dev.js"></script>

<script src="/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.js?v2"></script>
<script src="/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.js"></script>
<script src="/bower_components/raphael/raphael-min.js"></script>
<script src="/bower_components/morris.js/morris.min.js"></script>


<script src="/js/dashboard/theme-setup.js?<?php print filemtime(APP_DOCUMENT_ROOT ."/public/js/dashboard/theme-setup.js")?>"></script>
<script src="/js/dashboard/bootstrap-setup.js?<?php print filemtime(APP_DOCUMENT_ROOT . "/public/js/dashboard/bootstrap-setup.js")?>"></script>
<script src="/js/dashboard/js-prototype.js?<?php print filemtime(APP_DOCUMENT_ROOT . "/public/js/dashboard/js-prototype.js")?>"></script>

<script src="/js/dashboard/dashboard.js?<?php print filemtime(APP_DOCUMENT_ROOT . "/public/js/dashboard/dashboard.js")?>"></script>


</body>
</html>


    <?php
    }

}