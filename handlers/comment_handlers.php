<?php

// Get comments by article ID
function getCommentsByArticle($article_id)
{
    global $conn;
    $query = "SELECT c.*, u.username FROM comments c 
              JOIN users u ON c.user_id = u.id 
              WHERE c.article_id = ? ORDER BY c.id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Add new comment
function addComment($article_id, $user_id, $message)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comments (article_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $article_id, $user_id, $message);
    return $stmt->execute();
}

// Delete comment
function deleteComment($comment_id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    return $stmt->execute();
}
