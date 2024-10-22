<?php
// book_logic.php
function addBook($title, $author, $isbn, $description, $db) {
    // Assume $db is the mysqli connection passed into the function
    $stmt = $db->prepare("INSERT INTO books (title, author, isbn, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $author, $isbn, $description);

    if ($stmt->execute()) {
        return "Book Added Successfully";
    } else {
        return "Error adding book: " . $stmt->error;
    }
}
function editBook($bookId, $title, $author, $isbn, $description, $db) {
    $stmt = $db->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, description = ? WHERE id = ?");
    if ($stmt === false) {
        return "Error preparing statement: " . htmlspecialchars($db->error);
    }

    $stmt->bind_param("ssssi", $title, $author, $isbn, $description, $bookId);
    if ($stmt->execute()) {
        return 'Book Updated Successfully';
    } else {
        return "Error updating book: " . htmlspecialchars($stmt->error);
    }
}

function deleteBook($bookId, $db) {
    $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
    if ($stmt === false) {
        return "Error preparing statement: " . htmlspecialchars($db->error);
    }

    $stmt->bind_param("i", $bookId);
    if ($stmt->execute()) {
        return 'Book Deleted Successfully';
    } else {
        return "Error deleting book: " . htmlspecialchars($stmt->error);
    }
}
function viewBook($bookId, $db) {
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}