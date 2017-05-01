# SugarAdvancedWorkflowCustomPHPMethods
SugarCRM's Advanced Workflow custom PHP actions

## Technical Description
The purpose of this customisation is to be able to trigger complex PHP actions leveraging Advanced Workflows.
The customisation is built in a way that can be module specific (ie: for Contacts are available 3 PHP methods, for Accounts 7).
The coded PHP methods can be configured from the UI, so that the Sugar Administrator can choose what methods to call based on the need.

The suggestion is to leverage further custom job queues for PHP intensive methods, and send jobs to the background by using timers wherever possible.
With the implemented functionality it is possible to find out the originating user of the call, so that it is a possibility to act on behalf of that user if required.

## Requirements
* Tested on Sugar Enterprise 7.8.2.0
* Tested on Linux

## Installation
* Copy the full folder structure within your SugarCRM system
* Run a quick repair and rebuild
* Make sure the browser's cache is purged, so that the Advanced Workflow custom action displays.
* Make sure cron is running successfully

## PHP Customisations
The only class that needs to be customised is located on `custom/modules/pmse_Project/AWFCustomActionLogic.php`.
The current version of the class, has 3 sample methods that log a fatal message on the sugarcrm.log file.
Two methods are available for Accounts and one for Contacts.
The method customMethodWithOriginalUserOverride, will retrieve the original user that initiated the process and act as that user, finally it will restore the user.
The implemented functionality allows the user to put a job in the background using a timer, and still act in behalf of the original user, instead of Admin.

## Sample Screenshot
![Advanced Workflow Sample Screenshot](https://raw.githubusercontent.com/esimonetti/SugarAdvancedWorkflowCustomPHPMethods/master/screenshot.png)
