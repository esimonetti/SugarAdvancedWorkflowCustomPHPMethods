<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-04-27
// Tested on Sugar 7.8.2.0


// This is an example of a possible skeleton implementation of 3 custom methods for Advanced Workflows
// 2 methods available for Accounts
// 1 method available for Contacts
//
// The method customMethodWithOriginalUserOverride, will retrieve the original user that initiated the process and act as that user on this method.
// This allows the user to put a job in the background using a timer, and still act in behalf of the original user, instead of Admin

SugarAutoLoader::load('custom/modules/pmse_Project/AWFCustomAction.php');

class AWFCustomActionLogic extends AWFCustomAction
{
    public $availableMethods = array(
        'Accounts' => array(
            'customMethodWithOriginalUserOverride',
            'normalCustomMethod1',
        ),
        'Contacts' => array(
            'contactsCustomMethod1',
        )
    );

    public function customMethodWithOriginalUserOverride($b, $user, $additional_info)
    {
        // call to impersonate the user
        $this->impersonateUser($user);

        $GLOBALS['log']->fatal('called customMethodWithOriginalUserOverride as '.$GLOBALS['current_user']->id.' originally run by '.$this->previousUser->id);
    
        // call to reset back to original user
        $this->resetUser();

        return true;
    }

    public function normalCustomMethod1($b, $user, $additional_info)
    {
        $GLOBALS['log']->fatal('called normalCustomMethod1');

        return true;
    }

    public function contactsCustomMethod1($b, $user, $additional_info)
    {
        $GLOBALS['log']->fatal('called contactsCustomMethod1');

        return true;
    }
}
