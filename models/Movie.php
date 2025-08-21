<?php
class Movie {
    private $conn;
    private $table_name = "movies";
    
    public $id;
    public $title;
    public $description;
    public $release_year;
    public $duration;
    public $genre;
    public $director;
    public $cast;
    public $thumbnail;
    public $video_url;
    public $uploaded_by;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create new movie
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET title=:title, description=:description, release_year=:release_year, 
                duration=:duration, genre=:genre, director=:director, cast=:cast, 
                thumbnail=:thumbnail, video_url=:video_url, uploaded_by=:uploaded_by";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->release_year = htmlspecialchars(strip_tags($this->release_year));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->genre = htmlspecialchars(strip_tags($this->genre));
        $this->director = htmlspecialchars(strip_tags($this->director));
        $this->cast = htmlspecialchars(strip_tags($this->cast));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        $this->uploaded_by = htmlspecialchars(strip_tags($this->uploaded_by));
        
        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":release_year", $this->release_year);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":genre", $this->genre);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":cast", $this->cast);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":uploaded_by", $this->uploaded_by);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Get all movies
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get single movie
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->release_year = $row['release_year'];
            $this->duration = $row['duration'];
            $this->genre = $row['genre'];
            $this->director = $row['director'];
            $this->cast = $row['cast'];
            $this->thumbnail = $row['thumbnail'];
            $this->video_url = $row['video_url'];
            $this->uploaded_by = $row['uploaded_by'];
            $this->created_at = $row['created_at'];
            
            return true;
        }
        
        return false;
    }
    
    // Search movies
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE title LIKE ? OR description LIKE ? OR genre LIKE ? OR director LIKE ? OR cast LIKE ? 
                ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
        
        $stmt->execute();
        
        return $stmt;
    }
    
    // Update movie
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET title=:title, description=:description, release_year=:release_year, 
                duration=:duration, genre=:genre, director=:director, cast=:cast, 
                thumbnail=:thumbnail, video_url=:video_url 
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->release_year = htmlspecialchars(strip_tags($this->release_year));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->genre = htmlspecialchars(strip_tags($this->genre));
        $this->director = htmlspecialchars(strip_tags($this->director));
        $this->cast = htmlspecialchars(strip_tags($this->cast));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        
        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":release_year", $this->release_year);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":genre", $this->genre);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":cast", $this->cast);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete movie
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>