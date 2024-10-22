<?php
use PHPUnit\Framework\TestCase;

class BookManagementTest extends TestCase {
    private $db;

    protected function setUp(): void {
        // Create a connection to the database
        $this->db = new mysqli('localhost', 'root', '', 'elibrary', 3307);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Cleanup the table before each test to avoid duplicate entries
        $this->db->query("DELETE FROM books WHERE isbn = '1234567890'");
        $this->db->query("DELETE FROM books WHERE isbn = '0987654321'");
    }

    public function testAddBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Call the function with test data
        $result = addBook('Test Book', 'Test Author', '1234567890', 'A test book description.', $this->db);

        // Assert that the function returns the expected result
        $this->assertEquals('Book Added Successfully', $result);
    }

    public function testEditBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Add a book to edit
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

    public function testDeleteBook() {
        // Include the book logic function
        require_once 'book_logic.php';

        // Add a book to delete
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