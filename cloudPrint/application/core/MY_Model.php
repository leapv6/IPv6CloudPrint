<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{

    protected $_tbl = NULL;    // 数据表
    protected $_pk = NULL;     // 主键
    protected $_uptime='uptime';//表的更新或添加时间字段，为以后离线数据处理

    /**
     * 初始化数据对象
     * 根据库名初始化数据对象，记载库对应对象，默认是数据配置文件的数据对象
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建数据
     */

    public function create($data)
    {
        // 判断主键是否是自增类型
        if($this->_pk){
            $time_uuid=$this->time_uuid();
            if( ! isset($data[$this->_pk]) or empty($data[$this->_pk]))
            {
                $data[$this->_pk] = $time_uuid;
            }
            //数据字段的更新时间
            $data[$this->_uptime]=time();
            //判断
            $this->db->select($this->_pk)
                    ->from($this->_tbl)
                    ->where($this->_pk,$data[$this->_pk]);
            if($this->db->count_all_results() > 0) {
                return 0;
            }
        }
        $this->db->insert($this->_tbl,$data);
        return $this->db->affected_rows();
    }

    /**
     * 更新数据
     */

    public function update($data)
    {
        if($this->_pk){
            $this->db->where($this->_pk,$data[$this->_pk]);
        }
        //假如输入数据没有定义表的更新时间
        if( ! isset($data[$this->_uptime]) or empty($data[$this->_uptime]))
        {
            $data[$this->_uptime] = time();
        }
        $this->db->update($this->_tbl,$data);
        return $this->db->affected_rows();
    }

    /**
     * 根据where条件更新数据库数据
     * @param $where
     * @param $data
     */
    public function update_by_where($where,$data){
        $this->db->where($where);
        //假如输入数据没有定义表的更新时间
        if( ! isset($data[$this->_uptime]) or empty($data[$this->_uptime]))
        {
            $data[$this->_uptime] = time();
        }
        $this->db->update($this->_tbl,$data);
        return $this->db->affected_rows();
    }

    /*替换数据*/

    public function replace($data){
        //如果数据不存在，则创建
        if( ! isset($data[$this->_pk]) or empty($data[$this->_pk]))
        {
            return $this->create($data);
        }
        //如果数据存在,则更新
        if($this->get_by_primary($data[$this->_pk])){
            return $this->update($data);
        }else{
            return $this->create($data);
        }

    }
    /**
     * 删除数据
     */

    public function delete($data)
    {
        $this->db
            ->where($this->_pk,$data[$this->_pk])
            ->delete($this->_tbl);
        return $this->db->affected_rows();
    }

    /**
     * 根据where条件删除相应的数据
     * @param $where
     */
    public function delete_by_where($where){
        $this->db->where($where);
        $this->db->delete($this->_tbl);
        return $this->db->affected_rows();
    }

    /**
    * 取记录总数
    */
    public function get_total()
    {
        return $this->db->count_all ( $this->_tbl );
    }

    /**
     * 分页查询
     */
    public function get_page($limit,$start)
    {
        $query = $this->db
            ->get($this->_tbl,$limit,$start);
        return $query->result_array();
    }

    /**
     * 获取全部数据
     *
     * @param bool 返回模式 true:数组 false:对象
     * @return mixed
     */
    public function get_all($arr = false)
    {
        $query = $this->db
            ->get($this->_tbl);
        return $arr ? $query->result_array() : $query->result();
    }

    /**
     * 根据where条件获取全部的数据
     * $number 如果不为0则 取部分值
     * @param $where
     * @return mixed
     */
    public function get_all_by_where($where,$order_arr=array(),$number=0){
        if(!empty($order_arr)){
            foreach($order_arr as $key=>$val){
                $this->db->order_by($key,$val);
            }
        }
        if($number!=0){
            $this->db->limit($number);
        }
        $this->db->where($where);
        $query = $this->db
            ->get($this->_tbl);
        return $query->result_array();
    }

    /**
     * 获取最大的主键
     */
    public function get_max_primary()
    {
        $query = $this->db->select_max($this->_pk)->get($this->_tbl);
        $re = $query->row();
        return $re->{$this->_pk};
    }

    /**
     * 主键查询
     */

    public function get_by_primary($id)
    {
        $query = $this->db
            ->get_where($this->_tbl,
                array(
                    $this->_pk => $id
                ),1);
        return $query->row_array();
    }

    /**
     * 根据当前col栏目的值得到记录值
     * @param $col
     * @param $value
     */
    public function get_item_by_column($column,$value){
        $query = $this->db
            ->get_where($this->_tbl,
                array(
                    "$column" => $value
                ),1);
        $re = $query->row_array();
        return $re;
    }
    /**
     * 单段查询
     */
    public function get_item($column,$id)
    {
        $query = $this->db
            ->select($column)
            ->get($this->_tbl,
                array(
                    $this->_pk => $id
                ),1);
        $re = $query->row();
        return $re[$column];
    }

    /*
    *清空数据表
    */
    public function truncate(){
        return $this->db->truncate($this->_tbl);
    }

    /**
     * 得到当前根据时间得到的uuid，用于表的主键以及表的更新时间
     */
    public function time_uuid(){
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}