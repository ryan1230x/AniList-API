<?php

class CollectionItem {
    private $conn;

    function __construct($db) {
        $this->conn = $db;
    }
    
    public function read($user_id) {
        
        // Get data
        $this->user_id = $user_id;
        
        $query = "SELECT * FROM collection_item JOIN user_collection_item USING(collection_item_id) WHERE user_id = :user_id ORDER BY collection_item.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":user_id", $this->user_id);
        $stmt->execute();
        $num = $stmt->rowCount();
    
        if($num > 0) {
            $collection_item_arr =  array();
        
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $collection_item_item = array(
                    'collection_item_name' => $collection_item_name,
                    'collection_item_url' => $collection_item_url,
                    'collection_item_updatedAt' => $collection_item_updatedAt,
                    'collection_item_picture' => $collection_item_picture,
                    'collection_item_id' => $collection_item_id
                );
                array_push($collection_item_arr, $collection_item_item);
            }
            echo json_encode($collection_item_arr);
            
        } else echo json_encode(array('msg'=>'none found'));
        
    }

    public function create() {        

        // Get data
        $data = json_decode(file_get_contents('php://input'), true);

        $this->collection_item_name = $data["collection_item_name"];
        $this->collection_item_url = $data["collection_item_url"];
        $this->collection_item_id = $data["collection_item_id"];
        $this->collection_item_updatedAt = $data["collection_item_updatedAt"];
        $this->collection_item_picture = $data["collection_item_picture"];
        // Variables
        $this->user_id = $data["user_id"];

        // Sanitize the data
        $this->collection_item_name = filter_var($this->collection_item_name, FILTER_SANITIZE_STRING);
        $this->collection_item_url = filter_var($this->collection_item_url, FILTER_SANITIZE_URL);
        $this->collection_item_id = filter_var($this->collection_item_id, FILTER_SANITIZE_NUMBER_INT);
        $this->collection_item_picture = filter_var($this->collection_item_picture, FILTER_SANITIZE_URL);
        $this->collection_item_updatedAt = filter_var($this->collection_item_updatedAt, FILTER_SANITIZE_NUMBER_INT);

        // Error Handling
        if(empty($this->collection_item_name) || empty($this->collection_item_url)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }

        if(empty($this->collection_item_id)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }

        if(empty($this->collection_item_updatedAt) || empty($this->collection_item_picture)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }

        if(!filter_var($this->collection_item_url, FILTER_VALIDATE_URL)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }
        
        if(!filter_var($this->collection_item_picture, FILTER_VALIDATE_URL)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }

        if(!filter_var($this->collection_item_updatedAt, FILTER_VALIDATE_INT)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }

        if(!filter_var($this->collection_item_id, FILTER_VALIDATE_INT)) {
            echo json_encode(array('msg' => 'Please fill in the fields'));
            exit();
        }      
        
        // Check to see if already exists
        $query = "SELECT * FROM user_collection_item WHERE user_id = :user_id AND collection_item_id = :collection_item_id";        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":user_id", $this->user_id);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);

        if(!$stmt->execute()) {
            echo json_encode(array('msg' => 'Query could not prepare'));
            exit();
        }

        if($stmt->rowCount() > 0) {
            echo json_encode(array('msg' => 'This has already been added!'));
            exit();
        }

        // Insert into the database
        $query = "INSERT INTO collection_item 
        SET
            collection_item_name = :collection_item_name,
            collection_item_url = :collection_item_url,
            collection_item_id = :collection_item_id,
            collection_item_picture = :collection_item_picture,
            collection_item_updatedAt = :collection_item_updatedAt";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":collection_item_name", $this->collection_item_name);        
        $stmt->bindValue(":collection_item_url", $this->collection_item_url);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);
        $stmt->bindValue(":collection_item_updatedAt", $this->collection_item_updatedAt);
        $stmt->bindValue(":collection_item_picture", $this->collection_item_picture);
        
        if(!$stmt->execute()) {
            echo json_encode(array('msg' => 'Error!'));
            exit();
        }
        
        $query = "INSERT INTO user_collection_item 
        SET
           collection_item_id = :collection_item_id,
           user_id = :user_id";
           
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);
        $stmt->bindValue(":user_id", $this->user_id);

        if($stmt->execute()) echo json_encode(array('msg' => 'Added Successfully'));
        else printf("Error: Query could not be executed");
    }

    public function delete() {

        // GET data
        $data = json_decode(file_get_contents('php://input'), true);

        $this->collection_item_id = $data["collection_item_id"];
        $this->user_id = $data["user_id"];

        // Sanitize the Input
        $this->collection_item_id = filter_var($this->collection_item_id, FILTER_SANITIZE_NUMBER_INT);
        
        // Error handling + validation
        if(empty($this->collection_item_id) || empty($this->user_id)) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }

        if(!filter_var($this->collection_item_id, FILTER_VALIDATE_INT)) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }

        // Check if exists
        $query = "SELECT * FROM user_collection_item WHERE collection_item_id = :collection_item_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);
        $stmt->bindValue(":user_id", $this->user_id);
        if(!$stmt->execute()) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }
        if(!$stmt->rowCount() > 0) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }

        $query = "DELETE FROM collection_item WHERE collection_item_id = :collection_item_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);        
        if(!$stmt->execute()) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }

        $query = "DELETE FROM user_collection_item WHERE collection_item_id = :collection_item_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":collection_item_id", $this->collection_item_id);
        $stmt->bindValue(":user_id", $this->user_id);
        if(!$stmt->execute()) {
            echo json_encode(array('msg' => 'Error Please try again'));
            exit();
        }

        echo json_encode(array('msg' => 'Successfully Deleted'));
        exit();
    }
}

?>