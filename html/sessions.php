<?php
    /* 
    Database schema:
        Table name: sessions
        session_id, session_data, date_touched
    */
    //$db = new PDO("mysql:host=mysql:3306;dbname=phpdb", "root", "pass");
    include_once('connection.php');

    function sess_open($sess_path, $sess_name) {
        return true;
    }

    function sess_close() {
        return true;
    }

    function sess_read($sess_id) {
        GLOBAL $db;

        $statement = $db->prepare("SELECT * FROM sessions WHERE
        session_id = :id;");

        $statement->execute([":id" => $sess_id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $currentTime = time();

        if ($result['session_id'] == '') {
            $statement = $db->prepare("INSERT INTO sessions (session_id, date_touched) 
            VALUES (:id, :timeTouched);");

            $statement->execute([":id" => $sess_id, ":timeTouched" => $currentTime]);

            return '';

        } else {

            extract($result, EXTR_PREFIX_ALL, 'sess');

            $statement = $db->prepare("UPDATE sessions SET data_touched = :timeTouched
            WHERE session_id = :id;");

            $statement->execute([":id" => $sess_id, ":timeTouched" => $currentTime]);

            return $sess_session_data;
        }
    }

    function sess_write($sess_id, $data) {
        GLOBAL $db;

        $currentTime = time();

        $statement = $db->prepare("UPDATE sessions SET session_data = :sessData,
        date_touched = :timeTouched WHERE session_id = :id;");

        $statement->execute([":sessData" => $data, ":timeTouched" => $currentTime, 
        ":id" => $sess_id]);

        return true;
    }

    function sess_destroy($sess_id) {
        GLOBAL $db;

        $statement = $db->prepare("DELETE FROM sessions WHERE session_id = 
        :id;");

        $statement->execute([":id" => $sess_id]);

        return true;
    }

    function sess_gc($sess_maxlifetime) {
        GLOBAL $db;

        $currentTime = time();

        $statement = $db->prepare("DELETE FROM sessions WHERE date_touched +
        :maxLife < :timeTouched;");

        $statement->execute([":maxLife" => $sess_maxlifetime, ":timeTouched" => $currentTime]);

        return true;
    }

    session_set_save_handler("sess_open", "sess_close", "sess_read",
    "sess_write", "sess_destroy", "sess_gc");
    
    session_start();
?>