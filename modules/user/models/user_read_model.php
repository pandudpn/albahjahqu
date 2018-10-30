<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class user_read_model extends MY_Model {

	protected $table         = 'user_reads';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function analytic_pageview_areachart()
    {
    	return $this->db->query("select count(*) as total, 
								concat(year(created_on), ' ',monthname(created_on), ' ',LPAD(day(created_on), 2, '0')) as `date` 
								from user_reads
								group by 
								concat(year(created_on), ' ',monthname(created_on), ' ',LPAD(day(created_on), 2, '0'))
								order by 2 desc
								limit 7")->result();
    }

    public function shared_posts()
    {
        return $this->db->query("select title, count(*) as share
                                from user_sharings 
                                join posts on posts.id = user_sharings.news
                                group by title order by 2 desc limit 10")->result();
    }

    public function viewed_categories()
    {
        return $this->db->query("select name, count(*) as view
                                from user_reads 
                                join categories on categories.id = user_reads.cat
                                group by name order by 2 desc limit 5")->result();
    }

    public function shared_categories()
    {
        return $this->db->query("select name, count(*) as share
                                from user_sharings 
                                join categories on categories.id = user_sharings.cat
                                group by name order by 2 desc limit 5")->result();
    }

    public function media_share()
    {
        return $this->db->query("select media, count(*) as share
                                    from user_sharings 
                                    group by media order by 2 desc")->result();
    }
}