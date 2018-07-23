<?php

namespace InstagramGrab\Model;

use InstagramGrab\InstagramCONST;
use PDO;

class MySQL
{
    private function connectionMysql()
    {
        $db = new PDO('mysql:host='.InstagramCONST::DB_HOST.'; dbname='.InstagramCONST::DB_NAME, InstagramCONST::DB_USER, InstagramCONST::DB_PASSWORD);
        return $db;
    }

    private function prepareMysql($data)
    {
        $stmt = $this->connectionMysql() -> prepare ($data);
        return $stmt;
    }

    public function insertIntoTableFollowers($username)
    {
        $stmt = $this->prepareMysql("INSERT IGNORE INTO followers (username) VALUES (?)");
        foreach ($username as $value)
        {
        $stmt -> bindParam(1 , $value, PDO::PARAM_STR);
        $stmt -> execute();
        }

        $stmt = $this->prepareMysql("UPDATE followers SET follower=0");
        $stmt -> execute();

        $stmt = $this->prepareMysql("SELECT username FROM followers");
        $stmt->execute();
        $res=$stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $stmt = $this->prepareMysql("UPDATE followers SET follower=1 WHERE username=?");
        foreach ($res as $item)
        {
            if(in_array($item,$username))
            {
                $stmt -> bindParam(1 , $item, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

    public function insertIntoTablePosts($short_code)
    {
        $stmt = $this->prepareMysql("INSERT IGNORE INTO posts (shortcode) VALUES (?)");
        $stmt -> bindParam(1 , $short_code, PDO::PARAM_STR);
        $stmt -> execute();
    }

    public function insertIntoTablePostsLiked($users, $shortcode)
    {
        $sub_like_count = 0;
        $not_sub_like_count = 0;

        $stmt = $this->prepareMysql("SELECT username FROM followers");
        $stmt->execute();
        $res=$stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($users as $item)
        {
            if(in_array($item,$res))
            {
                $sub_like_count++;
            }
            else $not_sub_like_count++;
        }
        $stmt = $this->prepareMysql("UPDATE posts SET sub_like=?, not_sub_like=? WHERE shortcode=?");
        $stmt -> bindParam(1 , $sub_like_count, PDO::PARAM_STR);
        $stmt -> bindParam(2 , $not_sub_like_count, PDO::PARAM_STR);
        $stmt -> bindParam(3 , $shortcode, PDO::PARAM_STR);
        $stmt->execute();
    }
}