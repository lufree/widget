<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $config['interface']['charset'] ?>" />
<title><?php echo qw_t('LBL_HTML_TITLE') ?></title>
<?php
echo $this->getPackerSign();
$member = Qwin::call('-session')->get('member');
$nickname = isset($member['contact']) ? $member['contact']['nickname'] : $member['username'];
$minify->addArray(array(
    $style->getCssFile(),
    $this->getTag('root') . 'com/style/style.css',
    QWIN . 'image/iconx.css',
    $jQuery->loadCore(false),
    $jQuery->loadUi('core', false),
    $jQuery->loadUi('widget', false),
    $jQuery->loadUi('button', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadPlugin('qui', null, false),
    QWIN . 'js/qwin.js',
    $this->getTag('root') . 'com/script/style.js',
));
?>
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->getTag('root') ?>com/style/style-ie6.css" />
<?php
$pngFix = $jQuery->loadPlugin('pngFix', 'pack');
?>
<script type="text/javascript" src="<?php echo $pngFix['js'] ?>"></script>
<![endif]-->
<script type="text/javascript">
    jQuery.noConflict();
    Qwin.Lang = <?php echo json_encode(Qwin::call('-lang')->toArray()) ?>;
    Qwin.get = <?php echo Qwin_Util_Array::jsonEncode($_GET) ?>;
</script>
<!--<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>-->
</head>
<body>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
<table id="ui-header" class="ui-header ui-widget" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td id="ui-header-left">
            <div class="ui-header-logo ui-widget-content">
                <a href="?">
                    <img src="<?php echo $this->getTag('root') ?>com/image/logo.png" alt="logo" />
                </a>
            </div>
        </td>
        <td colspan="2" id="ui-header-middle">
            <div class="ui-header-shortcut" id="ui-header-shortcut">
                <a class="ui-state-default" href="<?php echo qw_u('com/member/my') ?>"><?php echo qw_t('LBL_WELCOME') ?>, <?php echo $nickname ?>!</a>
<!--                <a class="ui-state-default" href="<?php echo qw_url(array('module' => 'Management', 'controller' => 'Management')) ?>"><?php echo qw_t('LBL_MANAGEMENT') ?></a>-->
                <?php
                if('guest' == $member['username']):
                ?>
                <a class="ui-state-default" href="<?php echo qw_u('com/member/auth', 'login') ?>"><?php echo qw_t('LBL_LOGIN') ?></a>
                <?php
                else :
                ?>
                <a class="ui-state-default" href="<?php echo qw_u('com/member/auth', 'logout') ?>"><?php echo qw_t('LBL_LOGOUT') ?></a>
                <?php
                endif;
                ?>
            </div>
            <div class="qw-c"></div>
            <?php Qwin::hook('ViewNaviBar') ?>
        </td>
    </tr>
</table>
<div class="ui-navbar2 ui-widget-content ui-state-default"></div>
<table id="ui-main-table" border="0" cellpadding="0" cellspacing="0">
    <tr id="ui-main-content">
        <td id="ui-main-left">
        <?php Qwin::hook('viewMainLeft', $this); ?>
        <?php require $this->getElement('sidebar') ?>
        </td>
        <td id="ui-main-middle" class="ui-state-default">
            <div class="ui-main-middle-content"></div>
        </td>
        <td id="ui-main-right" colspan="2">
        <?php require $this->getElement('content') ?>
        </td>
    </tr>
</table>
</div>
<div id="ui-floating-footer" class="ui-state-default">
    <div id="ui-footer-arrow" class="ui-icon ui-icon-arrowthickstop-1-n"></div>
    <div class="ui-footer-time"></div>
    <div class="ui-copyright ui-widget">Executed in <?php echo Qwin_Application::getInstance()->getEndTime() ?>(s). <?php echo qw_t('LBL_FOOTER_COPYRIGHT') ?></div>
</div>
</body>
</html>
