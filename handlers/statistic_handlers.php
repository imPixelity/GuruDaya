<?php

// Get total articles
function getTotalArticles()
{
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM articles");
    return $result->fetch_assoc()['total'];
}

// Get total users
function getTotalUsers()
{
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    return $result->fetch_assoc()['total'];
}

// Get total comments
function getTotalComments()
{
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM comments");
    return $result->fetch_assoc()['total'];
}

// Get featured article (article with most comments)
function getFeaturedArticle()
{
    global $conn;
    $query = "SELECT a.*, u.username as author, COUNT(c.id) as comment_count 
              FROM articles a 
              JOIN users u ON a.author_id = u.id 
              LEFT JOIN comments c ON a.id = c.article_id 
              GROUP BY a.id 
              ORDER BY comment_count DESC, a.id DESC LIMIT 1";
    $result = $conn->query($query);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
