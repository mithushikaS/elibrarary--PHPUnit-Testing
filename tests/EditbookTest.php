<?php
use PHPUnit\Framework\TestCase;

class EditBookTest extends TestCase {
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

    public function testEditBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Insert a book to edit
        $this->db->query("INSERT INTO books (title, author, isbn, description) VALUES ('Original Title', 'Original Author', '0987654321', 'Original Description')");

        // Get the book ID of the inserted book
        $bookId = $this->db->insert_id;

        // Call the function with updated data
        $result = editBook($bookId, 'Updated Title', 'Updated Author', '0987654321', 'Updated Description', $this->db);

        // Assert that the function returns the expected result
        $this->assertEquals('Book Updated Successfully', $result);

        // Verify the book details were updated
        $updatedBook = $this->db->query("SELECT * FROM books WHERE id = $bookId")->fetch_assoc();
        $this->assertEquals('Updated Title', $updatedBook['title']);
        $this->assertEquals('Updated Author', $updatedBook['author']);
        $this->assertEquals('Updated Description', $updatedBook['description']);
    }

    protected function tearDown(): void {
        // Close the database connection after each test
        $this->db->close();
    }
}
