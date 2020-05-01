<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Wordpress
 *
 * @author developer pc
 */

namespace harrykdeveloper\Wordpress;

use harrykdeveloper\Wordpress\Database\Mysql as DbMysql;

class DbMigration {

    /**
     *
     * @var type | Name
     */
    protected $db_name = "";

    /**
     *
     * @var type 
     */
    protected $db_user = "";

    /**
     *
     * @var type | Password
     */
    protected $db_pass = "";

    /**
     *
     * @var type | Host
     */
    protected $db_host = "localhost";

    /**
     *
     * @var type | prefix | wp_
     */
    protected $db_tables_prefix = "wp_";

    /**
     *
     * @var type | Old Url
     */
    protected $old_domain = "";

    /**
     *
     * @var type | New Url
     */
    protected $new_domain = "";
    protected $errors = [];

    /**
     * 
     * @param type $options
     */
    public function setOptions($options = array()) {
        if (isset($options['db_name'])) {
            $this->db_name = $options['db_name'];
        }

        if (isset($options['db_user'])) {
            $this->db_user = $options['db_user'];
        }

        if (isset($options['db_pass'])) {
            $this->db_pass = $options['db_pass'];
        }

        if (isset($options['db_host'])) {
            $this->db_host = $options['db_host'];
        }

        if (isset($options['db_tables_prefix'])) {
            $this->db_tables_prefix = $options['db_tables_prefix'];
        }

        if (isset($options['old_domain'])) {
            $this->old_domain = $options['old_domain'];
        }

        if (isset($options['new_domain'])) {
            $this->new_domain = $options['new_domain'];
        }

        return $this;
    }

    /**
     * 
     * @param type $string
     * @return type
     */
    protected function error($string) {
        return '<p style="color:red;font-weight:bold;">Error: ' . $string . '</p><hr />';
    }

    /**
     * 
     * @param type $string
     * @return type
     */
    protected function success($string) {
        return '<p style="color:green;font-weight:bold;">Success: ' . $string . '</p><hr />';
    }

    /**
     * 
     * @throws Exception
     */
    protected function validator() {
        try {
            // check empty params.
            if (empty($this->db_name))
                throw new Exception("Provide Database Name!");

            if (empty($this->db_user))
                throw new Exception("Provide Database User!");

            if (empty($this->db_pass))
                throw new Exception("Provide Database Password!");

            if (empty($this->db_host))
                throw new Exception("Provide Database Host!");

            if (empty($this->db_tables_prefix))
                throw new Exception("Provide Database Table Prefix!");

            if (empty($this->old_domain))
                throw new Exception("Provide Old Domain Name!");

            if (empty($this->new_domain))
                throw new Exception("Provide New Domain Name!");

            return $this;
            
        } catch (\Exception $ex) {
            echo $this->error($ex->getMessage()); exit;
        }
    }

    public function migrate() {
        $db = new DbMysql();

        try {
            $connection = $db->connection($this->db_host, $this->db_name, $this->db_user, $this->db_pass);
        } catch (\Exception $ex) {
            echo $this->error($ex->getMessage());
            exit;
        }
        echo $this->success('Rows Updated');
        // options
        try {
            $q1 = $connection->prepare("UPDATE {$this->db_tables_prefix}options SET option_value = replace(option_value, :old_url, :new_url) WHERE option_name = 'home' OR option_name = 'siteurl'");
            $q1->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}options` `option_value` Total rows updated : " . $q1->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}options` `option_value` Error : " . $ex->getMessage());
        }

        // posts
        try {
            $q2 = $connection->prepare("UPDATE {$this->db_tables_prefix}posts SET post_content = replace(post_content, :old_url, :new_url)");
            $q2->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}posts` `post_content` Total rows updated : " . $q2->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}posts` `post_content` Error : " . $ex->getMessage());
        }

        // postmeta
        try {
            $q3 = $connection->prepare("UPDATE {$this->db_tables_prefix}postmeta SET meta_value = replace(meta_value, :old_url, :new_url)");
            $q3->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}postmeta` `meta_value` Total rows updated : " . $q3->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}postmeta` `meta_value` Error : " . $ex->getMessage());
        }

        // usermeta
        try {
            $q8 = $connection->prepare("UPDATE {$this->db_tables_prefix}usermeta SET meta_value = replace(meta_value, :old_url, :new_url)");
            $q8->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}usermeta` `meta_value` Total rows updated : " . $q8->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}usermeta` `meta_value` Error : " . $ex->getMessage());
        }

        // links : link_url
        try {
            $q4 = $connection->prepare("UPDATE {$this->db_tables_prefix}links SET link_url = replace(link_url, :old_url, :new_url)");
            $q4->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}links` `link_url` Total rows updated : " . $q4->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}links` `link_url` Error : " . $ex->getMessage());
        }

        // comments
        try {
            $q5 = $connection->prepare("UPDATE {$this->db_tables_prefix}comments SET comment_content = replace(comment_content , :old_url, :new_url)");
            $q5->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}comments` `comment_content` Total rows updated : " . $q5->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}comments` `comment_content` Error : " . $ex->getMessage());
        }

        // links : link_image
        try {
            $q6 = $connection->prepare("UPDATE {$this->db_tables_prefix}links SET link_image = replace(link_image, :old_url, :new_url)");
            $q6->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}links` `link_image` Total rows updated : " . $q6->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}links` `link_image` Error : " . $ex->getMessage());
        }

        // posts : guid
        try {
            $q7 = $connection->prepare("UPDATE {$this->db_tables_prefix}posts SET guid = replace(guid, :old_url, :new_url)");
            $q7->execute(array(':old_url' => $this->old_domain, ':new_url' => $this->new_domain));
            echo $this->success("Table : `{$this->db_tables_prefix}posts` `guid` Total rows updated : " . $q7->rowCount());
        } catch (\PDOException $ex) {
            echo $this->error("Table : `{$this->db_tables_prefix}posts` `guid` Error : " . $ex->getMessage());
        }
    }

}
