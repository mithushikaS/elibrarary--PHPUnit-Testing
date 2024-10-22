<?php
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->conn = new mysqli("localhost", "root", "", "elibrary", 3307);

        // Ensure the connection is successful
        if ($this->conn->connect_error) {
            $this->fail("Connection failed: " . $this->conn->connect_error);
        }
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testUserRegistration()
    {
        // Data for the new user (using unique values to avoid clashes)
        $fullname = "John Doe";
        $email = "johndoe_" . uniqid() . "@example.com"; // Unique email
        $username = "johndoe_" . uniqid(); // Unique username
        $password = "securePassword123";
        $confirm_password = $password; // Passwords should match
        $phone = "1234567890";
        $address = "123 Main St";
        $dob = "1990-01-01";

        // Simulate the registration form submission
        $_POST['fullname'] = $fullname;
        $_POST['email'] = $email;
        $_POST['username'] = $username;
        $_POST['password'] = $password;
        $_POST['confirm_password'] = $confirm_password;
        $_POST['phone'] = $phone;
        $_POST['address'] = $address;
        $_POST['dob'] = $dob;

        // Check if the username or email already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        // Assert that the user doesn't already exist
        $this->assertEquals(0, $stmt->num_rows, "User already exists.");

        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (fullname, email, username, password, phone, address, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullname, $email, $username, $hashed_password, $phone, $address, $dob);

        $this->assertTrue($stmt->execute(), "Error registering user: " . $stmt->error);

        // Cleanup the test user
        $this->conn->query("DELETE FROM users WHERE username = '$username'");

        $stmt->close();
    }
}
