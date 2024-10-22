<?php
use PHPUnit\Framework\TestCase;

class DeleteBookTest extends TestCase {
    private $db;

    protected function setUp(): void {
        // Create a connection to the database
        $this->db = new mysqli('localhost', 'root', '', 'elibrary', 3307);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Cleanup the table before each test
        $this->db->query("DELETE FROM books WHERE isbn = '1234567890'");
    }

    public function testDeleteBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Insert a book to delete
        $this->db->query("INSERT INTO books (title, author, isbn, description) VALUES ('Delete Title', 'Delete Author', '1234567890', 'Delete Description')");

        // Get the book ID of the inserted book
        $bookId = $this->db->insert_id;

        // Call the function to delete the book
        $result = deleteBook($bookId, $this->db);

        // Assert that the function returns the expected result
        $this->assertEquals('Book Deleted Successfully', $result);

        // Verify the book is deleted
        $deletedBook = $this->db->query("SELECT * FROM books WHERE id = $bookId");
        $this->assertEquals(0, $deletedBook->num_rows);
    }

    protected function tearDown(): void {
        // Close the database connection after each test
        $this->db->close();
    }
}
