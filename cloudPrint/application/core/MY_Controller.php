<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Base_Controller
 * 公共父类
 */
class Base_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //开启页面缓存
       // $this->output->cache(10);
    }

    /**
     * 分页效果
     * @param $url 基础url
     * @param $total 总共多少页
     * @param $pre  每页显示
     * @param bool $status
     */
    protected  function page($url,$total,$pre,$status=TRUE){
        $this->load->library('pagination');

        $config['full_tag_open']="<ul class='paginList'>";
        $config['full_tag_close']='</ul>';

        $config['first_link'] =false;
        $config['first_tag_open'] = '<li class="paginItem">';
        $config['first_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="paginItem">';
        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="paginItem current"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $config['last_link'] =false;
        $config['last_tag_open'] = '<li class="paginItem"> ';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="paginItem">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="paginItem">';
        $config['prev_tag_close'] = '</li>';
        $config['page_query_string'] = $status;

        $config['base_url'] = $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $pre;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /**
     * 输出json格式数据
     * @param mixed $re 数据
     */
    private function toJson($data = '') {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    /**
     * 判断ajax请求返回输出(布局/碎片)
     */
    protected function display($data, $json = FALSE)
    {
        if($this->input->is_ajax_request() || $json === TRUE)
        {
            $this->toJson($data);
        }
        else
        {
            $this->toHtml($data);
        }
    }

    /**
     * 循环创建目录
     * @param $dir
     * @param int $mode
     * @return bool
     */
    function mk_dir($dir, $mode = 0755)
    {
        if (is_dir($dir) || @mkdir($dir,$mode)) return true;
        if (!mk_dir(dirname($dir),$mode)) return false;
        return @mkdir($dir,$mode);
    }


}
/**
 * 前端父类
 * Class Front_Controller
 */
class Front_Controller extends Base_Controller
{
    const CTL = 'index'; // 默认控制器
    const ACT = 'index';        // 默认动作

    protected $_result;

    function __construct()
    {
        parent::__construct();
        //设置session参数
        $this->load->library('session');
        //根据不同的值来加载不同的语言包
        $lang=get_cookie('user_lang');
        if ($lang) {
            $this->config->set_item('language',$lang);
        }
        //设置缓存目录
        //$this->config->set_item('cache_path',APPPATH."cache/$lang/");
    }

    /**
     * 判断是否登陆
     */
    protected function check_login(){
        if(!$this->session->userdata('user')){//如果没有管理员的session，则实行跳转
            redirect(base_url('login'));
            exit("no right!");
        }
    }
}

/**
 * 后端父类
 * Class MY_Controller
 */
class MY_Controller extends Base_Controller
{

    const CTL = 'index'; // 默认控制器
    const ACT = 'index';        // 默认动作

    protected $_result;

    function __construct()
    {
        parent::__construct();
        //设置session参数
        $this->load->database();
        //$this->load->library('session');

        //$this->_check_admin_login();
        //根据不同的值来加载不同的语言包
        //设置cookie语言
        /*$lang=get_cookie('admin_user_lang');
        if ($lang) {
            $this->config->set_item('language',$lang);
        }*/
        //设置缓存目录
        //$this->config->set_item('cache_path',APPPATH."cache/$lang/");
    }

    /**
     * 判断是否登陆
     */
    protected function _check_admin_login(){
        if(!$this->session->userdata('adminId')){//如果没有管理员的session，则实行跳转
            return false;
        }else{
            return true;
        }
        /*
        if(!$this->session->userdata('uid'))
        {
            redirect(base_url('manage/login'));
            exit("no right!");
        }
        else
        {
            $session_roleid=$this->session->userdata('roleid');
            $session_uid=$this->session->userdata('uid');
            $this->_admin = $this->db->select('user.roleid')
                ->from('user')
                ->where('user.uid' , $this->session->userdata('uid') )
                ->get()
                ->row();

        }*/
    }

    protected function _check_business_login(){
        if(!$this->session->userdata('businessId')){//如果没有管理员的session，则实行跳转
            return false;
        }else{
            return true;
        }
    }

    protected function _check_user_login(){
        if(!$this->session->userdata('userNick')){//如果没有管理员的session，则实行跳转
            return false;
        }else{
            return true;
        }
    }

    protected function getResult()
    {
        return $this->_result;
    }

    protected function setResult($data = array())
    {
        $this->_result = $data;
    }

    protected function getTotalData()
    {
        return $this->_db->get_total();
    }

    protected function getLimit()
    {
        $result = $this->input->get('limit');
        $result = ($result === false) ? 20 : $result;
        return $result;
    }


    protected function show_tipinfo($title=array(),$list=array()){
        $data=array();
        $data['title']=$title;
        $data['url']=$list;
        $this->load->view('admin/tipinfo',$data);
    }



}
