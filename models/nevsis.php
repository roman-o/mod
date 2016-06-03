<?php

class Nevsis extends Model
{
    public function getList($only_published = false)
    {
        $sql = 'SELECT * FROM news';
        if ($only_published) {
            $sql = 'SELECT * FROM news WHERE is_published=1';
        }
        return $this->db->query($sql);
    }

    public function getListNews()
    {
        $sql = 'SELECT  id_news, title FROM news';

        return $this->db->query($sql);
    }


    public function getListNewsForEdit($id_news)
    {
        $id_news = $this->db->escape($id_news);
        $sql = "SELECT n.id_news, n.title, n.alias, n.id_category, n.content, n.is_active, n.img_news, c.title AS titlecat
         FROM news n
         LEFT JOIN category c ON n.id_category = c.id_category
         WHERE n.id_news =  '{$id_news}'";

        return $this->db->query($sql);
    }


    public function getCategoreById($id_category)
    {
        $id_category = $this->db->escape($id_category);
        $sql = "SELECT title,alias,description FROM category WHERE id_category='{$id_category}'";
        return $this->db->query($sql);
    }

    public function getCategory($layot, $limit = null)
    {
        if ($limit != null) {
            $l = "limit $limit ";
        } else {
            $l = "";
        }
        $sql = "SELECT n.id_news, n.title, n.alias, n.id_category, c.alias, c.title as titlecat
FROM news n
LEFT JOIN category c ON n.id_category = c.id_category
WHERE c.alias ='{$layot}' " . $l . " ";

        return $this->db->query($sql);
    }

    public function getListCategory()
    {
        $sql = 'SELECT id_category ,title, alias,description  FROM category';

        return $this->db->query($sql);
    }

    public function mainSlaider()
    {
        $sql = "SELECT id_news, title, alias, is_active, data, img_news
FROM  `news`
ORDER BY data DESC
LIMIT 0 , 3";
        return $this->db->query($sql);
    }
    public function topcoment()
    {
        $sql = "SELECT COUNT( c.TEXT ) AS r, c.id_user, u.email
FROM  `coments` c
LEFT JOIN users u ON c.id_user = u.id_user
GROUP BY c.id_user
ORDER BY r DESC
LIMIT 0 , 3";
        return $this->db->query($sql);
    }

    public function topnews()
    {
        $sql = "SELECT c.id_news, COUNT( c.TEXT ) AS cnt, n.title
FROM  `coments` c
LEFT JOIN news n ON c.id_news = n.id_news
GROUP BY id_news
ORDER BY cnt DESC
LIMIT 0 , 5";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT * FROM news WHERE id_news='{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getTags($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT n.id_tegs, t.name
FROM tegs_to_news n
LEFT JOIN tegs t ON n.id_tegs = t.id_tegs
WHERE id_news =  '{$id}'
GROUP BY id_tegs";

        return $this->db->query($sql);
        // return $layot;
    }

    public function getTagsByName($name1)
    {
        $name1 = $this->db->escape($name1);
        $sql = " SELECT t.id_tegs_to_news, t.id_news, n.id_news,te.name, t.id_tegs, n.title
FROM tegs_to_news t
LEFT JOIN news n ON t.id_news = n.id_news
LEFT JOIN tegs te ON t.id_tegs = te.id_tegs
WHERE te.name =  '{$name1}'";


        return $this->db->query($sql);
        // return $layot;
    }

    public function getTagByName($name1)
    {
        $name1 = $this->db->escape($name1);
        $sql = " SELECT  name,id_tegs FROM tegs WHERE name='{$name1}'";


        return $this->db->query($sql);
        // return $layot;
    }

    public function getAllTags()
    {

        $sql = " SELECT  * FROM tegs ";


        return $this->db->query($sql);
        // return $layot;
    }

    public function getTagByIdNews($id_news, $name2)
    {
        $id_news = $this->db->escape($id_news);
        $name2 = $this->db->escape($name2);
        $sql = " SELECT * FROM  `tegs_to_news` WHERE id_news =  '{$id_news}' AND id_tegs =  '{$name2}'";

        return $this->db->query($sql);
        // return $layot;
    }


    public function getComentsById($id)
    {
        $id = $this->db->escape($id);
        $sql = " SELECT  c.id_user,c.text,c.id_coments, c.is_active ,u.id_user,u.email  FROM coments c left join users u on c.id_user= u.id_user
  WHERE id_news='{$id}'";


        return $this->db->query($sql);
        // return $layot;
    }

    public function saveComents($data, $is_active)
    {

        $text = $this->db->escape($data['text']);
        $id_user = $this->db->escape($data['id_user']);
        $id_news = $this->db->escape($data['id_news']);
        $is_active = $this->db->escape($is_active);
        $id_user = (int)$id_user;
        $id_news = (int)$id_news;
        $is_active = (int)$is_active;

        $sql = "INSERT INTO coments SET id_user='{$id_user}',id_news='{$id_news}', text='{$text}', is_active='{$is_active}' ";
        //return  $is_active;
        return $this->db->query($sql);
    }

    public function updateNews($data, $id_news)
    {

        $title = $this->db->escape($data['title']);
        $alias = $this->db->escape($data['alias']);
        $id_category = $this->db->escape($data['id_category']);
        $content = $this->db->escape($data['content']);
        $is_active = $this->db->escape($data['is_active']);
        $id_news = $this->db->escape($id_news);

        $id_category = (int)$id_category;
        $id_news = (int)$id_news;
        $is_active = (int)$is_active;

        $sql = "UPDATE `news` SET `title`='{$title}',`alias`='{$alias}',`id_category`='{$id_category}',`content`='{$content}',
         `is_active`='{$is_active}' WHERE id_news ='{$id_news}' ";
        //return  $is_active;
        return $this->db->query($sql);
    }

    public function saveNews($data)
    {
        $title = $this->db->escape($data['title']);
        $alias = $this->db->escape($data['alias']);
        $id_category = $this->db->escape($data['id_category']);
        $content = $this->db->escape($data['content']);
        $is_active = $this->db->escape($data['is_active']);
        $sql = "INSERT INTO `news`( `title`, `alias`, `id_category`, `content`, `is_active`)
          VALUES ('{$title}','{$alias}','{$id_category}','{$content}','{$is_active}') ";
        //return  $is_active;
        return $this->db->query($sql);
    }

    public function maxIdNews()
    {
        $sql = 'SELECT MAX( id_news ) as max FROM  `news`';
        return $this->db->query($sql);
    }

    public function updateTags($tags, $id_news)
    {

        $id_news = (int)$id_news;
        $sql = "DELETE FROM `tegs_to_news` WHERE id_news = '{$id_news}' ";

        $this->db->query($sql);

        foreach ($tags as $item) {
            $item = $this->db->escape($item);
            $item = (int)$item;
            $sql = " INSERT INTO `tegs_to_news`( `id_news`, `id_tegs`) VALUES ('{$id_news}','{$item}')";

            $this->db->query($sql);
        }


    }


    public function sorting($mouth, $year, $only_published = false)
    {
        $mouth = $this->db->escape($mouth);
        $year = $this->db->escape($year);
        if ($mouth AND $year) {
            $sql = "SELECT *
FROM  `news`
WHERE MONTH( data ) ='{$mouth}' AND YEAR(data)='{$year}'";
        } else {
            $sql = "SELECT * FROM  `news`";
        }
        if ($only_published) {
            $sql = 'SELECT * FROM pages WHERE is_published=1';
        }
        return $this->db->query($sql);
    }

    public function saveLike($params1, $params2)
    {
        $params1 = $this->db->escape($params1);
        $params2 = $this->db->escape($params2);
        $sql = " INSERT INTO `voite_coments`( `status`, `id_coments`) VALUES ('{$params1}','{$params2}')";


        return $this->db->query($sql);
        // return $layot;
    }

    public function countLike($id_coments, $status)
    {
        $id_coments = $this->db->escape($id_coments);
        $status = $this->db->escape($status);
        $sql = " SELECT COUNT(
STATUS ) as num , id_coments
FROM  `voite_coments`
WHERE id_coments =  '{$id_coments}' AND status = '{$status}'
GROUP BY STATUS ";


        return $this->db->query($sql);
        // return $layot;
    }

    public function comentOfUser($id_user)
    {
        $id_user = $this->db->escape($id_user);

        $sql = " SELECT c.id_user, c.id_coments, c.text, c.id_news, u.email, n.title, n.id_category, ca.alias, ca.title as namecat
FROM  `coments` c
LEFT JOIN users u ON c.id_user = u.id_user
LEFT JOIN news n ON c.id_news = n.id_news
LEFT JOIN category ca ON n.id_category = ca.id_category
WHERE c.id_user =  '{$id_user}'";


        return $this->db->query($sql);
        // return $layot;
    }

    public function updateCategory($data, $id_category)
    {
        $title = $this->db->escape($data['title']);
        $alias = $this->db->escape($data['alias']);
        $description = $this->db->escape($data['description']);
        $is_active = $this->db->escape($data['is_active']);
        $id_category = $this->db->escape($id_category);

        $sql = "UPDATE `category` SET `title`='{$title}',`alias`='{$alias}',`description`='{$description}',
         `is_active`='{$is_active}' WHERE id_category ='{$id_category}' ";
        //return  $is_active;
        return $this->db->query($sql);
    }

    public function saveCategory($data)
    {
        $title = $this->db->escape($data['title']);
        $alias = $this->db->escape($data['alias']);
        $description = $this->db->escape($data['description']);
        $is_active = $this->db->escape($data['is_active']);
        $sql = "INSERT INTO `category`( `title`, `alias`, `description`,  `is_active`)
          VALUES ('{$title}','{$alias}','{$description}','{$is_active}') ";
        //return  $is_active;
        return $this->db->query($sql);
    }

    public function maxIdCategory()
    {
        $sql = 'SELECT MAX( id_category ) as max FROM  `category`';
        return $this->db->query($sql);
    }


    public function comentsAllWithNews()
    {

        $sql = 'SELECT c.id_coments, c.id_user, c.id_news, c.text, c.is_active,u.email, n.title as news_name FROM `coments` c
left join news n ON c.id_news= n.id_news left join users u ON c.id_user = u.id_user order by n.title';
        return $this->db->query($sql);
    }
    public function comentsedit($id_coments)
    {
        $id_coments = $this->db->escape($id_coments);
        $sql = "SELECT c.id_coments, c.id_user, c.id_news, c.text, c.is_active,u.email, n.title as news_name FROM `coments` c
left join news n ON c.id_news= n.id_news left join users u ON c.id_user = u.id_user where c.id_coments='{$id_coments}'";
        return $this->db->query($sql);
    }

    public function updateComents($data, $id_coments)
    {
        $id_user = $this->db->escape($data['id_user']);
        $is_active = $this->db->escape($data['is_active']);
        $id_news = $this->db->escape($data['id_news']);
        $text = $this->db->escape($data['text']);
        $id_coments = $this->db->escape($id_coments);

        $sql = "UPDATE `coments` SET `id_user`='{$id_user}',`is_active`='{$is_active}',
`id_news`='{$id_news}',
         `text`='{$text}' WHERE id_coments ='{$id_coments}' ";
        //return  $is_active;
        return $this->db->query($sql);
    }

}