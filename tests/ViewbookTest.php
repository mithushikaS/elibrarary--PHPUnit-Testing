<?php
use PHPUnit\Framework\TestCase;

class ViewBookTest extends TestCase {
    private $db;

    protected function setUp(): void {
        // Create a connection to the database
        $this->db = new mysqli('localhost', 'root', '', 'elibrary', 3307);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Cleanup the table before each test
        $this->db->query("DELETE FROM books WHERE isbn = '0987654321'");
    }

    public function testViewBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Insert a book to view
        $this->db->query("INSERT INTO books (title, author, isbn, description) VALUES ('View Title', 'View Author', '0987654321', 'View Description')");

        // Get the book ID of the inserted book
        $bookId = $this->db->insert_id;

        // Call the function to view the book details
        $book = viewBook($bookId, $this->db);

        // Assert that the function returns the correct book details
        $this->assertEquals('View Title', $book['title']);
        $this->assertEquals('View Author', $book['author']);
        $this->assertEquals('0987654321', $book['isbn']);
        $this->assertEquals('View Description', $book['description']);
    }

    protected function tearDown(): void {
        // Close the database connection after each test
        $this->db->close();
    }
}
