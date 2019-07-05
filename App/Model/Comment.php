<?php
/**
 * Created by PhpStorm.
 * User: zwyl
 * Date: 2019/6/26
 * Time: 11:35
 */
namespace  App\Model;

Class Comment extends Base {

    public $tableName = "comment";

    public function getCommentList($video_id){
       return $this->db->where('video_id',$video_id)->get($this->tableName);
    }

}