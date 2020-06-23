<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-04-27
// Tested on Sugar 7.8.2.0

class AWFCustomAction
{
    public $availableMethods = array();
    public $previousUser;

    public function methodExists($method, $module)
    {
        if (!empty($method) && !empty($module) && !empty($this->availableMethods[$module])) {
            if (in_array($method, $this->availableMethods[$module]) && method_exists($this, $method)) {
                return true;
            }
        }
        return false;
    }

    public function getAvailableModulesApis()
    {
        if (!empty($this->availableMethods)) {
            return array_keys($this->availableMethods);
        }
    
        return array();
    }

    public function getAvailableApis($module = null)
    {
        if (empty($module)) {
            return array('success' => false);
        }

        $response = array(
            'success' => true,
            'result' => array(),
        );

        if (!empty($this->availableMethods) && !empty($this->availableMethods[$module])) {
            foreach ($this->availableMethods[$module] as $method) {
                if (method_exists($this, $method)) {
                    $response['result'][] = array(
                        'value' => $method,
                        'text' => $method,
                    );
                }
            }
    
            return $response;            
        }
        
        return array('success' => false);
    }

    public function impersonateUser($user)
    {
        global $current_user;
        if (!empty($user) && ($current_user->id !== $user->id)) {
            // backup current user
            $this->previousUser = clone($current_user);
            $current_user = clone($user);
        }
    }

    public function resetUser()
    {
        global $current_user;
        if (!empty($this->previousUser->id)) {
            // restore current user with previous user
            $current_user = clone($this->previousUser);
            $this->previousUser = null;
        }
    }

    public function retrieveOriginalUserForProcess($cas_id = null)
    {
        if (!empty($cas_id)) {
            // retrieve the first flow record of this process run
            $sugarQuery = new SugarQuery();
            $sugarQuery->from(BeanFactory::newBean('pmse_BpmFlow'));
            $sugarQuery->select(array('id', 'created_by'));
            $sugarQuery->where()->equals('cas_id', $cas_id);
            $sugarQuery->where()->equals('cas_index', '1');
            $sugarQuery->limit(1);
            $records = $sugarQuery->execute();

            if (!empty($records) && !empty($records['0']) && !empty($records['0']['created_by'])) {
                $override_user = BeanFactory::getBean('Users', $records['0']['created_by']);
                if (!empty($override_user->id) && $override_user->id !== '1') {
                    return $override_user;
                }
            }
        }

        return false;
    }

    public function callCustomLogic(SugarBean $b, $method, $additional_info)
    {
        if ($b instanceof SugarBean) {

            // retrieve the original user that initiated the process, if there was a timer to push the action in the background
            // right now there seem to be no way to differentiate if a process is real time if it was sent in the background
            // logic will have to apply to the executing logic, based on the method called

            $override_user = null;
            if (!empty($additional_info['flowData']['cas_id'])) {
                $override_user = $this->retrieveOriginalUserForProcess($additional_info['flowData']['cas_id']);
            }

            if ($this->methodExists($method, $b->module_name)) {
                try {
                    return call_user_func_array(array($this, $method), array($b, $override_user, $additional_info));
                } catch (Exception $e) {
                    return false;
                }
            }
        }

        return false;
    }
}
