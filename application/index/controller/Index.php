<?php
namespace app\index\controller;

use think\Db;
use think\Exception;
use think\Session;
use think\View;

class Index
{
    public function index()
    {
        $rand = rand(1,6);
        $sql = 'SELECT b.name as country_name , c.name as period_name , a.* FROM item_list a, country b, period c where a.country=b.id and a.period=c.id and is_download = 2 and status = 2 LIMIT '.($rand).',1';
        $result = Db::query($sql);
        $view = new View();
        $view->img_src = $result[0]['image_url'];
        $view->item_title = $result[0]['title'];
        $view->item_desc = $result[0]['desc_text'];
        $view->item_author = $result[0]['painter'];
        $view->item_country = $result[0]['country_name'];
        $view->item_period = $result[0]['period_name'];
        $view->item_id = $result[0]['id'];
        $view->last_id = Session::get('last_id');


        try {
            return $view->fetch();
        } catch (Exception $e) {
        }
    }
    public function submit(){

        $id = input("post.item_id");
        $radio = input("post.reason");
        $text = input("post.desc");
        Session::set('last_id', $id);
        Db::table('item_list')->where('id', $id)->update(['status' => $radio]);
        if ($text != ''){
            Db::table('item_reason')->insert(['id' => $id, 'text' => $text]);
        }
        echo '<meta http-equiv="Refresh" content="0; url=./index.php"/>';
    }
}
