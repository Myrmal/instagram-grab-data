<?php

namespace InstagramGrab\Model;

use InstagramGrab\InstagramCONST;
use InstagramGrab\Model\MySQL;
use InstagramGrab\Model\Connection;

class Instagram
{
    private $user_id = '';

    private $result_followers = [];

    private $short_codes = [];

    private $result_users_liked_post = [];

    protected function newDataBase()
    {
        return new MySQL();
    }

    protected function newConnection()
    {
        return new Connection();
    }

    /*получаем userid*/
    private function getUserID($username)
    {
        $result = $this->newConnection()->getJSON("https://www.instagram.com/$username/?__a=1");
        $this->user_id = $result->graphql->user->id;
        return;
    }

    /*получаем посты и достаем шорткоды*/
    private function getPosts ($count)
    {
        $result=$this->newConnection()->getJSON("https://www.instagram.com/graphql/query/?query_hash=".InstagramCONST::GET_POSTS."&variables={\"id\":\"{$this->user_id}\",\"first\":$count}");

        foreach ($result->data->user->edge_owner_to_timeline_media->edges as $val)
        {
            foreach ($val as $val2)
            {
                foreach ($val2 as $key => $val3)
                {
                    if ($key == "shortcode")
                    {
                        array_push($this->short_codes, $val3);
                    }
                }
            }
        }
        return;
    }

    /*получаем шорткоды и достаем юзеров*/
    private function usersPostLikes($shortcode, $after ='')
    {
        $result=$this->newConnection()->getJSON("https://www.instagram.com/graphql/query/?query_hash=".InstagramCONST::GET_POST_LIKES."&variables={\"shortcode\":\"$shortcode\",\"first\":50$after}");

        $has_next_page = $result->data->shortcode_media->edge_liked_by->page_info->has_next_page;

        foreach ($result->data->shortcode_media->edge_liked_by->edges as $val)
        {
            foreach ($val as $val2)
            {
                foreach ($val2 as $key => $val3)
                {
                    if ($key == "username")
                    {
                        array_push($this->result_users_liked_post, $val3);
                    }
                }
            }
        }
        if ($has_next_page == 1)
        {
            $end_cursor = $result->data->shortcode_media->edge_liked_by->page_info->end_cursor;
            $after = ",\"after\":\"$end_cursor\"";
            $this->usersPostLikes($shortcode, $after);
        }
        else
        {
            $this->putPostsFollowersIntoTable($shortcode);
            $this->result_users_liked_post = [];
            return;
        }
    }

    private function putPostsFollowersIntoTable($shortcode)
    {
        $db = $this->newDataBase();
        $db->insertIntoTablePosts($shortcode);
        $db->insertIntoTablePostsLiked($this->result_users_liked_post, $shortcode);
        return;
    }

    /*получение лайков постов*/

    public function getPostLikes($username, $count)
    {
        if($this->user_id == '')
        {
            $this->getUserID($username);
        }

        $this->getPosts($count);
        for ($i=0; $i<count($this->short_codes); $i++)
        {
            //передаем шорт-коды постов
            $this->usersPostLikes($this->short_codes[$i]);
        }
    }

    /*получение подписчиков*/
    public function getFollowers($username, $after = "")
    {
        $db = $this->newDataBase();
        if($this->user_id == '')
        {
            $this->getUserID($username);
        }

        $result=$this->newConnection()->getJSON("https://www.instagram.com/graphql/query/?query_hash=".InstagramCONST::GET_FOLLOWERS."&variables={\"id\":\"{$this->user_id}\",\"first\":50$after}");

        foreach ($result->data->user->edge_followed_by->edges as $val)
        {
            foreach ($val as $val2)
            {
                foreach ($val2 as $key => $val3)
                {
                    if ($key == "username")
                    {
                        array_push($this->result_followers, $val3);
                    }
                }
            }
        }

        $has_next_page = $result->data->user->edge_followed_by->page_info->has_next_page;
        if ($has_next_page == 1)
        {
            $end_cursor = $result->data->user->edge_followed_by->page_info->end_cursor;
            $after = ",\"after\":\"$end_cursor\"";
            $this->getFollowers($username, $after);
        }
        else
        {
            $db->insertIntoTableFollowers($this->result_followers);
            return;
        }
    }
}