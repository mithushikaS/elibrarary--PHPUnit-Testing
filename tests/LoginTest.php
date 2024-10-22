<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        // Mock database connection
        $this->mockConnection = $this->getMockBuilder(mysqli::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // Mock the statement
        $this->mockStmt = $this->getMockBuilder(mysqli_stmt::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSuccessfulLogin()
    {
        $this->mockConnection->method('prepare')->willReturn($this->mockStmt);
        $this->mockStmt->method('bind_param')->willReturn(true);
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('bind_result')->willReturn(true);
        
        // Assume a valid hashed password
        $hashed_password = password_hash('validpassword', PASSWORD_DEFAULT);
        $this->mockStmt->method('fetch')->willReturn(true);
        $this->mockStmt->method('close')->willReturn(true);

        // Simulate form input
        $_POST['username'] = 'validuser';
        $_POST['password'] = 'validpassword';

        // Include the login script (or refactor to call a function)
        include 'login.php';  // Adjust the path if necessary

        $this->assertEquals('validuser', $_SESSION['username']);
        $this->assertNotNull($_SESSION['member_id']);
    }

    public function testInvalidLogin()
    {
        $this->mockConnection->method('prepare')->willReturn($this->mockStmt);
        $this->mockStmt->method('bind_param')->willReturn(true);
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('bind_result')->willReturn(true);
        $this->mockStmt->method('fetch')->willReturn(false);
        $this->mockStmt->method('close')->willReturn(true);

        // Simulate invalid login
        $_POST['username'] = 'invaliduser';
        $_POST['password'] = 'wrongpassword';

        // Include the login script (or refactor to call a function)
        include 'login.php';  // Adjust the path if necessary

        $this->assertEquals("Invalid username or password", $error);
    }
}