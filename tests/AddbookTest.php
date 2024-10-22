<?php
use PHPUnit\Framework\TestCase;

class AddBookTest extends TestCase {
    private $db;

    protected function setUp(): void {
        // Create a connection to the database
        $this->db = new mysqli('localhost', 'root', '', 'elibrary', 3307);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Cleanup the table before each test to avoid duplicate entries
        $this->db->query("DELETE FROM books WHERE isbn = '1234567890'");
    }

    public function testAddBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Call the function with test data
        $result = addBook('Test Book', 'Test Author', '1234567890', 'A test book description.', $this->db);

        // Assert that the function returns the expected result
        $this->assertEquals('Book Added Successfully', $result);

        // Verify that the book was added to the database
        $book = $this->db->query("SELECT * FROM books WHERE isbn = '1234567890'")->fetch_assoc();
        $this->assertEquals('Test Book', $book['title']);
        $this->assertEquals('Test Author', $book['author']);
        $this->assertEquals('A test book description.', $book['description']);
    }

    protected function tearDown(): void {
        // Close the database connection after each test
        $this->db->close();
    }
}





