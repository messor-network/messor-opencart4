<?php

namespace Opencart\System\Library\Extension\Messor\Cms; //todo change namespace, before messor\cms

/**
 * Trait for interacting Messor with Opencart CMS
 * in an interface-independent way
 */
trait Opencart
{
    // todo change path, before extension/module/messor/requestToPeer
    private $pathToPeer = "extension/messor/module/messor|requestToPeer";

    public function index()
    {   
        // todo change path, before extension/module/messor/Messor
        $route = 'extension/messor/module/messor|Messor&user_token='.$this->session->data['user_token'];
        $this->redirect($route);
    }

    public function addLeftColumn(&$route, &$data)
    {
        // todo change path, before 'extension/module/messor'
        $this->load->language('extension/messor/module/messor');

        $messor = array();

        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'Messor',
                // todo change link, before extension/module/messor/Messor. Events now, controller | function
                'href'     => $this->url->link('extension/messor/module/messor|Messor', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'Malware Cleaner',
                'href'     => $this->url->link('extension/messor/module/messor|MCLMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'File System Controll',
                'href'     => $this->url->link('extension/messor/module/messor|FSControlMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'File Database Backup',
                'href'     => $this->url->link('extension/messor/module/messor|FDBBMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'File System Check',
                'href'     => $this->url->link('extension/messor/module/messor|FSCheckMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $messor[] = array(
                'name'     => 'Security Settings',
                'href'     => $this->url->link('extension/messor/module/messor|SecuritySettingsMain', 'user_token=' . $this->session->data['user_token'], true),
                'children' => array()
            );
        }


        $data['menus'][] = array(
            'id'       => 'menu-security',
            'icon'       => 'fas fa-shield',
            'name'     => $this->language->get('name_left_column'),
            'href'     => '',
            'children' => $messor
        );
    }

    /* Hooks that write settings to the database */
    public function install()
    {
        $this->load->model('extension/messor/module/messor'); // todo change path, before 'extension/module/messor'

        if ($this->validate()) {
            $settings['module_messor_status'] = 1; // todo change index array, before module_messor_status
            $this->load->model('setting/setting');
            $this->load->model('setting/extension');
            $this->load->model('user/user_group');
            $this->model_setting_setting->editSetting('module_messor', $settings);

            // todo delele calls
            // $extension_install_id = $this->model_extension_messor_module_messor->getInstallId();
            // unset($extension_install_id->rows[0]);
            // foreach ($extension_install_id->rows as $result) {
                // $this->model_setting_extension->deleteExtensionInstall($result['extension_install_id']);
                // $this->model_extension_messor_module_messor->deleteExtensionPathOfInstall($result['extension_install_id']); // todo change name var, before model_extension_module_messor
            // }
            // $this->model_setting_extension->uninstall('module', 'messor');
            // $settings['module_messor_status'] = 1;
            $this->model_setting_extension->install('module', 'module', 'messor'); // todo add parameter number 2 type extension


            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/messor/module/messor'); // todo change path, before 'extension/module/messor'
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/messor/module/messor'); // todo change path, before 'extension/module/messor'

            // Call install method if it exsits
            // $this->load->controller('extension/messor/' . $this->getRequestGet('extenson'] . '/install');

            $this->session->data['success'] = $this->language->get('text_success');
            $this->model_extension_messor_module_messor->createEvents(); // todo change name var, before model_extension_module_messor
        }
    }

    public function uninstall()
    {
        $this->load->model('extension/messor/module/messor'); // todo change path, before 'extension/module/messor'
        if ($this->validate()) {
            $this->model_setting_extension->uninstall('module', $this->getRequestGet('extension')); // todo change parameter,(messor change module) before code, type after type, code

            // Call uninstall method if it exsits
            $this->load->controller('extension/messor/module/messor' . $this->getRequestGet('extension') . '/uninstall'); // todo change path, before 'extension/module/messor'
            $this->model_extension_messor_module_messor->deleteEvents(); // todo change name var, before model_extension_module_messor
            $this->session->data['success'] = $this->language->get('text_success');
        }
    }


    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/messor/module/messor')) { // todo change path, before 'extension/module/messor'
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    /* end hook */

    public function getEmail()
    {
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');
        return $config['config_email'];
    }

    public function getDefaultPath()
    {
        return dirname(DIR_APPLICATION);
    }

    public function getFullPath()
    {
        return DIR_APPLICATION;
    }

    public function getAdminPanelName()
    {
        return 'admin';
    }

    public function getDatabaseData()
    {
        return array('host' => DB_HOSTNAME, 'user' => DB_USERNAME, 'password' => DB_PASSWORD, 'dbname' => DB_DATABASE);
    }

    public function getVersion()
    {
        return VERSION;
    }

    public function getCMS() 
    {
        return "Opencart";
    }
    
    public function getUserCMS()
    {
        $this->load->model('user/user');
        $this->load->model('user/user_group');
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');
        $users = $this->model_user_user->getUsers();
        foreach ($users as $item) {
            $group = $this->model_user_user_group->getUserGroup($item['user_group_id']);
            if (preg_match('/admin|administrator|manager|test|root|support/', $item['username'])) {
                $novalid = true;
            } else {
                $novalid = false;
            }
            $data['user'][] = array(
                'group'     => $group['name'],
                'user_id'    => $item['user_id'],
                'login'      => $item['username'],
                'firstname'   => $item['firstname'],
                'lastname'   => $item['lastname'],
                // todo change link, before user/user/edit
                'edit'       => $this->url->link('user/user|form', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $item['user_id'], true),
                'novalid'   => $novalid
            );
        }
        return $data['user'];
    }

    public function getSettingsShowError()
    {
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');
        return $config['config_error_display'];
    }

    public function moveDirectoryStorage()
    {
        if (DIR_STORAGE == DIR_SYSTEM . 'storage/' ||  DIR_STORAGE == null) {
            return true;
        } elseif (!defined(DIR_STORAGE)) {
            return false;
        }
    }

    public function getDBUserName()
    {
        return DB_USERNAME;
    }

    public function checkLastVersionCMS()
    {
        $versionCurrent = VERSION;
        $url = "https://github.com/opencart/opencart/releases/latest";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($ch);
        curl_close($ch);
        $headers = explode("\n", $a);
        $version = '';
        foreach ($headers as $item) {
            if (strpos($item, "location:") !== false) {
                $match = trim(str_replace("Location:", "", $item));
                $match = explode('/', $match);
                $version = end($match);
                break;
            }
        }
        if ($versionCurrent != $version) {
            return array(true, $version);
        } else {
            return array(false, $version);;
        }
    }

    public function getConfigFileName()
    {
        return 'config.php';
    }

    public function getDBPrefix()
    {
        return DB_PREFIX;
    }

    public function getDBPrefixForCMS()
    {
        return "oc_";
    }

    public function getPathInstallDir()
    {
        return dirname(DIR_APPLICATION) . '/install';
    }

    public function getUserNameAndEmail()
    {
        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUser($this->user->getId());
        $data['name'] = $user_info['firstname'] . $user_info['lastname'];
        $match = preg_match('/^[a-zA-Z0-9\ ]{3,35}$/', $data['name']);
        $data['name'] = $match !== 1 ? '' : $data['name'];
        $data['email'] = $user_info['email'];
        $data['http_host'] = $this->request->server['HTTP_HOST'];
        return array($data['name'], $data['email']);
    }

    public function getLinkApi($path)
    {
        // todo change path, before extension/module/messor
        return $this->url->link('extension/messor/module/messor|'.$path, '', true);
    }

    public function getUrlLink($route, $data = null, $tokenFlag = true)
    {
        // todo change path, before extension/module/messor
        $path = "extension/messor/module/messor|";
        $route = $path . $route;
        $token = $tokenFlag ? $this->getUserToken() : '';
        if ($data != null) {
            foreach ($data as $k => $v) {
                $string = '&' . $k . '=' . $v;
            }
            $url = $this->url->link($route, 'user_token=' . $token . $string, true);
            return str_replace('&amp;', '&', $url);
        } else if ($data == null) {
            $url = $this->url->link($route, 'user_token=' . $token, true);
            return str_replace('&amp;', '&', $url);
        }
    }

    public function isImage()
    {
        if ($this->getRequestGet('_route_') !== null) {
            $check = pathinfo($this->getRequestGet('_route_'));
            $list = array('svg', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'woff', 'ttf', 'eot', 'woff2', 'css', 'js');
            if (isset($check['extension']) && in_array(strtolower($check['extension']), $list)) {
                return true;
            }
        }
        if ($this->getRequestGet('route') !== null) {
            $check = pathinfo($this->getRequestGet('route'));
            $list = array('svg', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'woff', 'ttf', 'eot', 'woff2', 'css', 'js');

            if (isset($check['extension']) && in_array(strtolower($check['extension']), $list)) {
                return true;
            }
        }
    }

    public function addStyle($name)
    {
        if (is_array($name)) {
            foreach ($name as $item) {
                // todo change path script, before view/stylesheet/messor
                $this->document->addStyle('/extension/messor/admin/view/stylesheet/messor/'.$item);
            }
        } else {
            // todo change path script, before view/stylesheet/messor
            $this->document->addStyle('/extension/messor/admin/view/stylesheet/messor/'.$name);
        }
    }

    public function getStyle()
    {
        return false;
    }

    public function addScript($name)
    {
        if (is_array($name)) {
            foreach ($name as $item) {
                // todo change path script, before view/javascript/messor
                $this->document->addScript('/extension/messor/admin/view/javascript/messor/'.$item, 'footer');
            }
        } else {
            // todo change path script, before view/javascript/messor
            $this->document->addScript('/extension/messor/admin/view/javascript/messor/'.$name, 'footer');
        }
    }

    public function getScript()
    {
        return $this->document->getScripts('footer');
    }

    public function redirect($route)
    {
        $this->response->redirect($this->url->link($route));
    }

    public function redirectWithoutUrl($route)
    {
        $this->response->redirect($route);
    }

    public function getRequestGet($item)
    {   
        if (isset($this->request->get[$item])) {
            return $this->request->get[$item];
        } else {
            return null;
        }
    }

    public function getRequestGetArr()
    {
        return $this->request->get;
    }


    public function getRequestPost($item)
    {   
        if (isset($this->request->post[$item])) {
            return $this->request->post[$item];
        } else {
            return null;
        }
    }

    public function getRequestPostArr()
    {
        return $this->request->post;
    }


    public function getRoute()
    {
        if ($this->getRequestGet('route') !== null) {
            return $this->getRequestGet('route');
        }
    }

    public function getUrl()
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getUserToken()
    {
        return $this->session->data['user_token'];
    }

    public function getServerVar($var)
    {
        return $this->request->server[$var];
    }

    public function getLanguage()
    {
        // todo change path, before extension/messor/module
        return $this->load->language('extension/messor/module/messor');
    }

    public function getHeader()
    {
        return $this->load->controller('common/header');
    }

    public function getColumnLeft()
    {
        return $this->load->controller('common/column_left');
    }

    public function getFooter()
    {
        return $this->load->controller('common/footer');
    }

    public function setTitle($title)
    {
        $this->document->setTitle($title);
    }

    public function responseOutput($path, $data)
    {        
        $pathView = "extension/messor/module/messor/"; // todo change path, before extension/module/messor/
        $this->response->setOutput($this->load->view('extension/messor/module/messor/' . $path, $data)); // todo change path, before extension/module/messor/
    }

    public function getOutput()
    {
       return $this->response->getOutput();
    }

    public function getView($path, $data)
    {
        // todo change path, before extension/module/messor/
        return $this->load->view('extension/messor/module/messor/' . $path, $data);
    }

    public function notFound()
    {
        return "index.php?route=error/not_found&status=redirect";
    }
}
