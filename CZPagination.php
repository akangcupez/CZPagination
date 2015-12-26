<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Created by   : Aji Subastian (aKanG cuPez)
 * Mobile Phone : +62 812 888 33996
 * Email        : akangcupez@gmail.com
 * Website      : http://akangcupez.com
 * Date         : 19/12/2015 12:19 AM
 */
/**
 * <h4>Class CZPagination</h4>
 *
 * <p style="font-weight:bold;">Dependencies:
 * <ul>
 * <li>Twitter Bootstrap (optional)</li>
 * <li>Font Awesome (default, change first,prev,next and last if not using font-awesome)</li>
 * </ul>
 * </p>
 * <p style="margin-top:15px;font-weight:bold;">Notes:</p>
 * <ul>
 * <li>Required loaded and connected database</li>
 * <li>Row Number (num_row) will be automatically added to each rows generated in get_data()</li>
 * <li>set_config() can be chained with initialize(). Please note that if calling initialize()
 * with passing config variable, the values called previously from set_config() will be over-written.</li>
 * <li>initialize() can be chained with get_data()</li>
 * <li><span style="font-weight:bold;">TIPS:</span> For some servers (such as Linux), calling this class
 * might required to be written in case-sensitive (e.g $this->load->library('CZPagination')</li>
 * </ul>
 *
 * <p style="margin-top:15px;font-weight:bold;">Example:</p>
 * <p>set_config($config)->initialize()->get_data()</p>
 * <p>or</p>
 * <p>initialize($config)->get_data()</p>
 *
 * @link http://akangcupez.com
 *
 * @property CI_DB_driver $db
 *
 */
class CZPagination extends CI_Model
{
    private $config = array
    (
        'base_url'          => '',
        'sql'               => '',
        'params'            => false,
        'page_number'       => 1,
        'per_page'          => 25,
        'num_links'         => 5,
        'full_tag_open'     => '<ul class="pagination">',
        'full_tag_close'    => '</ul>',
        'page_tag_open'     => '<li>',
        'page_tag_close'    => '</li>',
        'first_link'        => '<span><i class="fa fa-angle-double-left"></i></span>',
        'last_link'         => '<span><i class="fa fa-angle-double-right"></i></span>',
        'prev_link'         => '<span><i class="fa fa-angle-left"></i></span>',
        'next_link'         => '<span><i class="fa fa-angle-right"></i></span>',
        'cur_tag_open'      => '<li class="active">',
        'cur_tag_close'     => '</li>'
    );

    private $sql            = null;
    private $params         = false;
    private $pagination     = null;
    private $data_table     = null;

    private $page;
    private $active_page;
    private $row_per_page;
    private $num_links;
    private $total_links;
    private $row_number;
    private $total_rows;

    private $pager          = null;

    function __construct()
    {
        parent::__construct();
    }

    private function validate_value($value) { return (!(is_null($value) || empty($value))); }
    private function validate_array($value) { return ($this->validate_value($value) && is_array($value) && count($value) > 0); }
    private function validate_string($value) { return ($this->validate_value($value) && is_string($value)); }
    private function validate_int($value) { return ($this->validate_value($value) && is_int($value)); }
    private function validate_num($value) { return ($this->validate_int($value) && intval($value) > 0); }

    private function set_trailing_url($string, $symbol) { return (substr($string, -1, 1) !== $symbol) ? $string . $symbol : $string; }

    private function get_total_rows()
    {
        if($this->validate_string($this->sql))
        {
            if($qry = $this->db->query($this->sql, $this->params))
            {
                return $qry->num_rows();
            }
        }
        return 0;
    }

    private function set_row_number()
    {
        $page_number = ($this->page <= $this->total_links) ? $this->page : 1;
        return ceil(($this->row_per_page * $page_number) - ($this->row_per_page));
    }

    private function set_limit()
    {
        $start_limit = ($this->active_page - 1) * $this->row_per_page;
        $start_offset = ($start_limit > -1) ? $start_limit : 0;

        return ' LIMIT ' . $start_offset . ', ' . $this->row_per_page;
    }

    /**
     * <h4>Get all config</h4>
     *
     * @return array
     */
    public function get_config() { return $this->config; }

    /**
     * Get config value from config key
     *
     * @param string $key
     * @return mixed
     */
    public function get_config_item($key) { return $this->config[$key]; }

    /**
     * <h4>Set config</h4>
     * Call this method before calling initialize() in chained mode.
     * DO NOT pass another config var to initialize() if using this method
     *
     * @param array $config
     * @return $this
     */
    public function set_config(array $config)
    {
        if($this->validate_array($config))
        {
            foreach($config as $key => $val)
            {
                if(array_key_exists($key, $this->config))
                {
                    $this->config[$key] = $val;
                }
            }
        }
        return $this;
    }

    /**
     * <h4>Initialize pagination</h4>
     * Can be chained with get_data() method.
     *
     * @param null $config
     * @return $this
     */
    public function initialize($config = null)
    {
        if($this->validate_array($config)) $this->set_config($config);

        $base_url = ($this->validate_string($this->config['base_url'])) ? $this->config['base_url'] : null;
        if(!is_null($base_url)) $this->config['base_url'] = $this->set_trailing_url($base_url, '/');

        $this->sql          = $this->config['sql'];
        $this->params       = $this->validate_array($this->config['params']) ? $this->config['params'] : false;
        $this->total_rows   = $this->get_total_rows();

        $this->num_links    = ceil((intval($this->config['num_links']) / 2) - 1);
        $this->row_per_page = (intval($this->config['per_page']));
        $this->row_per_page = ($this->row_per_page < 1) ? 1 : $this->row_per_page;
        $this->total_links  = ceil($this->total_rows / $this->row_per_page);
        $this->page         = intval($this->config['page_number']);
        $this->row_number   = $this->set_row_number();

        $this->active_page  = $this->page;
        if($this->active_page < 1)
        {
            $this->active_page = 1;
        }
        elseif($this->active_page > $this->total_links)
        {
            $this->active_page = $this->total_links;
        }

        $sql = $this->sql . $this->set_limit();
        $params = $this->params;
        if($qry = $this->db->query($sql, $params))
        {
            $data = $qry->result_array();
            if($this->validate_array($data))
            {
                for($idx = 0; $idx < count($data); $idx++)
                {
                    $this->row_number++;

                    $this->data_table[$idx] = $data[$idx];
                    $this->data_table[$idx]['row_num'] = $this->row_number;
                }
                $this->create_pager();
            }
            $qry->free_result();
        }
        return $this;
    }

    private function show_first() { return ($this->active_page > ($this->num_links + 1)); }

    private function show_last() { return ($this->active_page < ($this->num_links + 2)); }

    private function create_pager()
    {
        $pager = null;

        if($this->total_links > 1)
        {
            $pager = array();
            if($this->active_page > 1)
            {
                $pager['first'] = ($this->show_first() === true) ? 1 : '';
                $pager['prev']  = ($this->active_page - 1);
                for($idx = ($this->active_page - $this->num_links); $idx < $this->active_page; $idx++)
                {
                    if($idx > 0) $pager['pages'][] = $idx;
                }
            }
            else
            {
                $pager['first'] = '';
                $pager['prev']  = '';
            }

            $pager['active']    = $this->active_page;
            $pager['pages'][]   = $this->active_page;

            for($idx = ($this->active_page + 1); $idx <= $this->total_links; $idx++)
            {
                $pager['pages'][] = $idx;
                if($idx >= ($this->active_page + $this->num_links)) break;
            }

            if($this->active_page != $this->total_links)
            {
                $pager['next']  = $this->active_page + 1;
                $pager['last']  = ($this->show_last() === true) ? $this->total_links : '';
            }
            else
            {
                $pager['next']  = '';
                $pager['last']  = '';
            }
        }
        $this->pager = $pager;
    }

    private function generate_list($page_number, $display_text)
    {
        if($this->validate_num($page_number))
        {
            $prefix = $this->config['page_tag_open'];
            $suffix = $this->config['page_tag_close'];
            $prefix_active = $this->config['cur_tag_open'];
            $suffix_active = $this->config['cur_tag_close'];

            $tag_open = ($page_number == $this->active_page) ? $prefix_active : $prefix;
            $tag_close = ($page_number == $this->active_page) ? $suffix_active : $suffix;

            return $tag_open . $this->generate_link($page_number, $display_text) . $tag_close;
        }
        return null;
    }

    private function generate_link($page_number, $display_text)
    {
        if($page_number == $this->active_page)
        {
            return "<a>{$display_text}</a>";
        }
        else
        {
            return "<a href=\"{$this->config['base_url']}{$page_number}\">{$display_text}</a>";
        }
    }

    /**
     * <h4>Get data from query result</h4>
     * <p>Call this method after initialize()</p>
     * <p>row number (row_num) will be automatically generated in the result</p>
     *
     * @return null|array
     */
    public function get_data() { return $this->data_table; }

    /**
     * <h4>Get non-formatted pagination</h4>
     * <p>Call this method after initialize()</p>
     *
     * @return null|array
     */
    public function get_pager(){ return $this->pager; }

    /**
     * <h4>Generate pagination links</h4>
     * Call this method after initialize()
     *
     * @return null|string
     */
    public function create_links()
    {
        $pagination = null;

        if($this->validate_array($this->pager))
        {
            $pagination = $this->config['full_tag_open'];

            $pagination .= $this->generate_list($this->pager['first'], $this->config['first_link']);
            $pagination .= $this->generate_list($this->pager['prev'], $this->config['prev_link']);

            if($this->validate_array($this->pager['pages']))
            {
                foreach($this->pager['pages'] as $page)
                {
                    $pagination .= $this->generate_list($page, $page);
                }
            }

            $pagination .= $this->generate_list($this->pager['next'], $this->config['next_link']);
            $pagination .= $this->generate_list($this->pager['last'], $this->config['last_link']);

            $pagination .= $this->config['full_tag_close'];
        }
        return $pagination;
    }

}
