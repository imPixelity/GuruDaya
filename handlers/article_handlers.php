<?php

// Get all articles
function getAllArticles()
{
    global $conn;
    $query = "SELECT a.*, u.username as author FROM articles a 
              JOIN users u ON a.author_id = u.id ORDER BY a.id DESC";
    return $conn->query($query);
}

// Get article by ID
function getArticleById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT a.*, u.username as author FROM articles a 
                           JOIN users u ON a.author_id = u.id WHERE a.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

// Search articles by title or content
function searchArticles($keyword)
{
    global $conn;
    $search_param = "%$keyword%";
    $stmt = $conn->prepare("SELECT a.*, u.username as author FROM articles a 
                           JOIN users u ON a.author_id = u.id 
                           WHERE a.title LIKE ? OR a.content LIKE ? 
                           ORDER BY a.id DESC");
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    return $stmt->get_result();
}

// Create article
function createArticle($title, $content, $author_id)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $content, $author_id);
    return $stmt->execute();
}

// Update article
function updateArticle($id, $title, $content)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);
    return $stmt->execute();
}

// Delete article
function deleteArticle($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Get all articles with admin info
function getArticlesForAdmin()
{
    global $conn;
    $query = "SELECT a.*, u.username as author FROM articles a 
              JOIN users u ON a.author_id = u.id ORDER BY a.id DESC";
    return $conn->query($query);
}
