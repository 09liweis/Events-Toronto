<?php


class Visual {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function insert($visuals) {
        foreach ($visuals as $visual) {
            $fields = $visual['fields']; 
            $title = $fields['title'];
            $douban_id = $fields['douban_id'];
            $imdb_id = $fields['imdb_id'];
            $date_updated = $fields['date_updated'];
            $date_watched = $fields['date_watched'];
            $original_title = $fields['original_title'];
            $year = $fields['year'];
            $rating = $fields['rating'];
            $poster = $fields['images'];
            $summary = $fields['summary'];
            $online_source = $fields['online_source'];
            $episodes = $fields['episodes'];
            $current_episode = $fields['current_episode'];
            $sql = 'INSERT INTO visuals ( 
                                        title, 
                                        douban_id, 
                                        imdb_id, 
                                        date_watched, 
                                        date_updated, 
                                        original_title, 
                                        year, 
                                        rating,
                                        poster, 
                                        summary,
                                        online_source,
                                        episodes,
                                        current_episode
                                        ) 
                                        VALUES (
                                        :title, 
                                        :douban_id, 
                                        :imdb_id, 
                                        :date_watched, 
                                        :date_updated, 
                                        :original_title,
                                        :year, 
                                        :rating,
                                        :poster, 
                                        :summary,
                                        :online_source,
                                        :episodes,
                                        :current_episode
                                        )';
            $pdostmt = $this->db->prepare($sql);
            $pdostmt->bindValue(':title', $title, PDO::PARAM_STR);
            $pdostmt->bindValue(':douban_id', $douban_id, PDO::PARAM_STR);
            $pdostmt->bindValue(':imdb_id', $imdb_id, PDO::PARAM_STR);
            $pdostmt->bindValue(':date_watched', $date_watched, PDO::PARAM_STR);
            $pdostmt->bindValue(':date_updated', $date_updated, PDO::PARAM_STR);
            $pdostmt->bindValue(':original_title', $description, PDO::PARAM_STR);
            $pdostmt->bindValue(':year', $year, PDO::PARAM_STR);
            $pdostmt->bindValue(':rating', $rating, PDO::PARAM_STR);
            $pdostmt->bindValue(':poster', $poster, PDO::PARAM_STR);
            $pdostmt->bindValue(':summary', $summary, PDO::PARAM_STR);
            $pdostmt->bindValue(':online_source', $online_source, PDO::PARAM_STR);
            $pdostmt->bindValue(':episodes', $episodes, PDO::PARAM_STR);
            $pdostmt->bindValue(':current_episode', $current_episode, PDO::PARAM_STR);
            $pdostmt->execute();
        }
    }
}