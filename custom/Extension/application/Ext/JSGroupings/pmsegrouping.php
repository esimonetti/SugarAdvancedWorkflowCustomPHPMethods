<?php
foreach ($js_groupings as $key => $groupings) {
    foreach ($groupings as $target) {
        if ($target == 'include/javascript/pmse.designer.min.js') {
            $js_groupings[$key]['custom/include/javascript/pmse/activity.js'] = 'include/javascript/pmse.designer.min.js';
        }
        break;
    }
}
