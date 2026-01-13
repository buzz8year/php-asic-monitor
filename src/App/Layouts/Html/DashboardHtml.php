<?php
/**

 * Date: 11.04.2017
 * Time: 14:18
 */

namespace App\Layouts\Html;

use App\BreadCrumbs;use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class DashboardHtml extends BaseLayout implements LayoutInterface, ViewInterface
{

    public function __construct()
    {
        parent::__construct();
        $this->addBreadCrumbs(new BreadCrumbs("Dashboard", "/"));
    }

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

?><!DOCTYPE html>
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
<!-- section header -->
<header class="header">
    <!-- header brand -->
    <div class="header-brand">
        <!-- <h2><a data-pjax=".content-body" href="/"><span class="text-primary">Irkutsk</span>&nbsp;Team</a> Dashboard</h2> -->
        <h2><a data-pjax=".content-body" href="/">Antminer Monitoring</a></h2>
    </div><!-- header brand -->

    <!-- header-profile -->
    <div class="header-profile hidden">
        <div class="profile-nav">
            <span class="profile-username">Bent</span>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-angle-down"></span>
            </a>
            <ul class="dropdown-menu animated flipInX pull-right" role="menu">
                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                <li><a href="#"><i class="fa fa-envelope"></i> Inbox</a></li>
                <li><a href="#"><i class="fa fa-tasks"></i> Tasks</a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="fa fa-sign-out"></i> Log Out</a></li>
            </ul>
        </div>
        <div class="profile-picture">
            <img alt="me" src="/images/dashboard/dummy/profile.jpg">
        </div>
    </div><!-- header-profile -->

</header><!--/header-->


<!-- content section -->
<section class="section">

    <aside class="side-left">
        <ul class="sidebar">
            <li>
                <a href="/RealTime">
                    <i class="sidebar-icon fa fa-television"></i>
                    <span class="sidebar-text">Real Time</span>
                </a>
            </li>

            <li>
                <a href="/Miners">
                    <i class="sidebar-icon fa fa-legal"></i>
                    <span class="sidebar-text">Units</span>
                </a>
            </li>

            <li>
                <a href="/Locations">
                    <i class="sidebar-icon fa fa-building-o"></i>
                    <span class="sidebar-text">Locations</span>
                </a>
            </li>

            <li>
                <a href="/EnergyConsumption">
                    <i class="sidebar-icon fa fa-bolt"></i>
                    <span class="sidebar-text">Energy Consumption</span>
                </a>
            </li>

            <li>
                <a href="/Users">
                    <i class="sidebar-icon fa fa-user"></i>
                    <span class="sidebar-text">User accounts</span>
                </a>
            </li>

        </ul><!--/sidebar-->
    </aside><!--/side-left-->

    <div class="content">
        <div class="content-header">
            <h2 class="content-title"><i class="fa fa-home"></i> <?php print $this->getHeaderTitle()?></h2>
        </div><!--/content-header -->

        <!-- content-control -->
        <div class="content-control">
            <!--breadcrumb-->
            <ul class="breadcrumb">
                <?php foreach ($this->getBreadCrumbs() as $bread_crumb): ?>
                    <?php if ($bread_crumb->getUrl()):?>
                        <li><a href="<?php print $bread_crumb->getUrl();?>"><?php print $bread_crumb->getTitle();?></a></li>
                    <?php else: ?>
                        <li class="active"><?php print $bread_crumb->getTitle();?></li>
                    <?php endif;?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- /content-control -->

        <div class="content-body">
            <?php print $this->getContent();?>
        </div><!--/content-body -->
    </div><!--/content -->

</section><!--/content section -->

<!-- section footer -->
<a rel="to-top" href="#top" class="hidden-xs"><i class="fa fa-arrow-up"></i></a>
<footer>
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