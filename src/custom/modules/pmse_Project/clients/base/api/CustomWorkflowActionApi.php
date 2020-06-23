<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-04-27
// Tested on Sugar 7.8.2.0

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

SugarAutoLoader::load('custom/modules/pmse_Project/AWFCustomActionLogic.php');

class CustomWorkflowActionApi extends SugarApi
{
    public function registerApiRest() {
        return array(
            array(
                'reqType' => 'GET',
                'path' => array('customv1', 'pmse_Project', 'CrmData', 'customWorkflowActions'),
                'pathVars' => array('', '', '', ''),
                'method' => 'getAvailableModulesApis',
                'shortHelp' => 'customv1/pmse_Project/CrmData/customWorkflowActions',
            ),
            array(
                'reqType' => 'GET',
                'path' => array('customv1', 'pmse_Project', 'CrmData', 'customWorkflowActions', '?'),
                'pathVars' => array('', '', '', '', 'module'),
                'method' => 'getAvailableApis',
                'shortHelp' => 'customv1/pmse_Project/CrmData/customWorkflowActions/:module',
            ),
        );
    }

    public function getAvailableModulesApis($api, $args) {
        $AWFCustomActionLogic = new AWFCustomActionLogic();
        return $AWFCustomActionLogic->getAvailableModulesApis();
    }

    public function getAvailableApis($api, $args) {

        if (empty($args['module'])) {
            return array('success' => false);
        }

        $AWFCustomActionLogic = new AWFCustomActionLogic();
        return $AWFCustomActionLogic->getAvailableApis($args['module']);
    }
}
