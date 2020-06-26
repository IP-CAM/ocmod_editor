<?php
class ControllerExtensionOcmodeditor extends Controller {
    private $error = array();
	public function index() {
		$this->load->language('extension/ocmodeditor');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ocmodeditor', 'token=' . $this->session->data['token'], true)
        );
        $data['success'] = "";

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if($this->save()) $data['success'] = $this->language->get('text_success');
            else $data['error'] = $this->error;
        }

        $url = '';
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
        }
		
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_dashboard'] = $this->language->get('text_dashboard');
        $data['text_link'] = $this->language->get('text_link');
        $data['text_author'] = $this->language->get('text_author');
        $data['text_version'] = $this->language->get('text_version');
        $data['text_xml'] = $this->language->get('text_xml');
        $data['text_code'] = $this->language->get('text_code');
        $data['text_save'] = $this->language->get('text_save');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['error_no_mod'] = $this->language->get('error_no_mod');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['refresh'] = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'], true);
        $data['action'] = $this->url->link('extension/ocmodeditor', 'token=' . $this->session->data['token'] . $url, true);
        $data['button_cancel'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true);

        
            
        if(isset($this->request->get['id'])){
            $this->load->model('extension/ocmodeditor');
            $data = array_merge($data,$this->model_extension_ocmodeditor->getModification($this->request->get['id']));
        }else{
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($this->user->getId());

            $data["name"] = "My new modification";
            $data["code"] = "my_new_modification";
            $data["author"] = "{$user_info['firstname']} {$user_info['lastname']}";
            $data["version"] = "1.0";
            $data["link"] = "";
            $data["xml"] = '<?xml version="1.0" encoding="utf-8"?>
<modification>
  <name>My new modification</name> 
  <code>my_new_modification</code>
  <version>1.0</version>
  <author>'.$data["author"].'</author>
  <link></link>

<file path="catalog/view/theme/default/stylesheet/stylesheet.css">

  <operation>
    <search trim="true"><![CDATA[
        body {
    ]]></search>
    <add position="before"><![CDATA[
        .my_new_modification_custom_class{
            position: relative;
        }
    ]]></add>
  </operation>
  
</file>

</modification>';
            $data["modification_id"] = "0";
        }
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/ocmodeditor', $data));
    }
    public function save() {
        libxml_use_internal_errors(true);
        $xml = html_entity_decode(trim($this->request->post['xml']));

        if(!simplexml_load_string($xml)){
            $this->error["danger"] = $this->language->get('text_xml_error');
            $this->error["warning"] = $xml;
            return false;
        }

        $this->load->model('extension/ocmodeditor');
        $mod_info = $this->model_extension_ocmodeditor->getModification($this->request->post['modification_id']);
        $xml = str_replace(
            "<name>{$mod_info['name']}</name>",
            "<name>{$this->request->post['name']}</name>",
            $xml
        );
        $name = $this->request->post['name'];

        $xml = str_replace(
            "<code>{$mod_info['code']}</code>",
            "<code>{$this->request->post['code']}</code>",
            $xml
        );
        $code = $this->request->post['code'];
        $this->load->model('extension/modification');
        $modification_info = $this->model_extension_modification->getModificationByCode($code);
		if ($modification_info && $modification_info['modification_id']!=$this->request->post['modification_id']) {
			$this->error['warning'] = sprintf($this->language->get('error_exists'), $modification_info['name']);
		}

        $xml = str_replace(
            "<author>{$mod_info['author']}</author>",
            "<author>{$this->request->post['author']}</author>",
            $xml
        );
        $author = $this->request->post['author'];

        $xml = str_replace(
            "<version>{$mod_info['version']}</version>",
            "<version>{$this->request->post['version']}</version>",
            $xml
        );
        $version = $this->request->post['version'];

        $xml = str_replace(
            "<link>{$mod_info['link']}</link>",
            "<link>{$this->request->post['link']}</link>",
            $xml
        );
        $link = $this->request->post['link'];

        $modification_data = array(
            'name'    => $name,
            'code'    => $code,
            'author'  => $author,
            'version' => $version,
            'link'    => $link,
            'xml'     => $xml,
            'status'  => $this->request->post['status']
        );

        if (!$this->error) {
            $this->model_extension_ocmodeditor->setModification($this->request->post['modification_id'],$modification_data);
        }
    
        return !$this->error;
	}
    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}