
<?php

require("connect.php");

$dsn = "mysql:host=" . SERVER . ";dbname=" . BASE;

try {
    $conn = new PDO($dsn, USER, PASSWD);
} catch (PDOException $e) {
    echo "Echec de la connexion : " . $e->getMessage();
    exit();
}

class Comment {
    private $id;
    private $text;
    private $author;
    private $createdAt;

    public function __construct($id, $text, $author, $createdAt = null) {
        $this->id = $id;
        $this->text = $text;
        $this->author = $author;
        $this->createdAt = $createdAt ? new DateTime($createdAt) : new DateTime();
    }

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}

class Post {
    private $id;
    private $title;
    private $content;
    private $comments = [];

    public function __construct($id, $title, $content) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    public function addComment($id, $text, $author) {
        $comment = new Comment($id, $text, $author);
        $this->comments[] = $comment;
        // Store the comment in the database
        $this->storeCommentInDatabase($id, $text, $author);
    }

    private function storeCommentInDatabase($id, $text, $author) {
        global $conn;
        $query = "INSERT INTO comments (post_id, text, author, created_at) VALUES (:post_id, :text, :author, NOW())";
        $stmt = $conn->prepare($query);
        $id1 = $this->getId();
        $stmt->bindParam(':post_id', $id1);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':author', $author);
        $stmt->execute();
    }

    public function getComments(): array
    {
        $postId = $this->getId();
        $this->comments = $this->getCommentsForPost($postId);
        // Sort comments array based on creation date
        usort($this->comments, function($a, $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });
        return $this->comments;
    }

    private function getCommentsForPost($postId): array
    {
        global $conn;
        $query = "SELECT * FROM comments WHERE post_id = :post_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($row['id'], $row['text'], $row['author'], $row['created_at']);
            $comments[] = $comment;
        }
        return $comments;
    }


    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }
}

// Example usage:
$post = new Post(1, "Publier vos Commentaires ", "Rejoigner le debat sur l'état actuel de la bande de Gaza");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $text = $_POST["comment"];
    $author = $_POST["author"];
    $post->addComment(12, $text, $author);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post with Comments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylepost.css">
</head>

<body>
    <nav class="nav">
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="acceuil.html" class="link active">Home</a></li>
                <li><a href="index.html" class="link">Blog</a></li>
                <li class="right"><a href="acceuil.html" class="logout">Log out</a></li> <!-- Bouton de déconnexion -->
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <!-- Partie pour afficher les anciens commentaires (partie gauche) -->
            <div class="col-md-6">
    <div class="jumbotron">
        <h1 class="display-4"><?php echo $post->getTitle(); ?></h1>
        <p class="lead"><?php echo $post->getContent(); ?></p>
        <ul class="list-group">
            <?php foreach ($post->getComments() as $comment): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo $comment->getAuthor(); ?>:</strong>
                        <?php echo $comment->getText(); ?>
                    </div>
                    <span class="badge badge-secondary time"><?php echo $comment->getCreatedAt()->format('Y-m-d H:i:s'); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
            <!-- Partie pour publier un commentaire (partie droite) -->
            <div class="col-md-6">
                <div class="card mt-4">
                    <h5 class="card-header">Ajouter un Commentaire</h5>
                    <div class="card-body">
                        <form method="post" action="post.php">
                            <div class="form-group">
                                <label for="author">Votre Nom:</label>
                                <input type="text" class="form-control" id="author" name="author">
                            </div>
                            <div class="form-group">
                                <label for="comment">Commentaire:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Publier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor\components\jquery/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="vendor\twbs\bootstrap\js/bootstrap.min.js"></script>
</body>

</html>
