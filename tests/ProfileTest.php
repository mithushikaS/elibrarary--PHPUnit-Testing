<?php

use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    private $dbConnection;
    private $profileData;

    protected function setUp(): void
    {
        // Start session and set a mock member ID
        $this->startSession(1);

        // Create a mock MySQLi connection to avoid a real database connection
        $this->dbConnection = $this->createMock(mysqli::class);
        
        // Simulate a mock profile data array
        $this->profileData = [
            'fullname' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'address' => '123 Library St.',
            'dob' => '1990-01-01'
        ];
    }

    protected function tearDown(): void
    {
        // Unset the session and any other globals after each test
        session_destroy();
        unset($this->profileData);
    }

    // Start a mock session
    private function startSession($memberId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['member_id'] = $memberId;
    }

    // Test if session exists and member is logged in
    public function testSessionExists()
    {
        $this->assertArrayHasKey('member_id', $_SESSION, "Member ID session does not exist.");
        $this->assertNotEmpty($_SESSION['member_id'], "Member is not logged in.");
    }

    // Test if the profile page correctly loads member data
    public function testFetchMemberData()
    {
        // Mock the prepared statement and result
        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);
        
        // Set expectations for statement methods
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('i', $_SESSION['member_id']);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($result);

        // Mock result fetching
        $result->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn($this->profileData);

        // Assuming $conn->prepare() returns a statement
        $this->dbConnection->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Call the method or script responsible for fetching profile data
        $profileData = $this->fetchProfileData($this->dbConnection, $_SESSION['member_id']);
        $this->assertEquals($this->profileData, $profileData, "Failed to fetch correct member data.");
    }

    // Test form input validation
    public function testFormValidation()
    {
        // Test case for valid form input
        $_POST = [
            'fullname' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'address' => '123 Library St.',
            'dob' => '1990-01-01'
        ];

        // Check if all required fields are not empty
        $this->assertNotEmpty($_POST['fullname'], "Full Name is required.");
        $this->assertNotEmpty($_POST['email'], "Email is required.");
        $this->assertNotEmpty($_POST['phone'], "Phone Number is required.");
        $this->assertNotEmpty($_POST['address'], "Address is required.");
        $this->assertNotEmpty($_POST['dob'], "Date of Birth is required.");

        // Validate the email format
        $isValidEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $this->assertTrue($isValidEmail !== false, "Invalid email format.");
    }

    // Test updating profile information
    public function testUpdateProfile()
    {
        // Mock the prepared statement and update success
        $updateStmt = $this->createMock(mysqli_stmt::class);
        $updateStmt->expects($this->once())
            ->method('bind_param')
            ->with(
                $this->equalTo('sssssi'),
                $this->equalTo('John Doe'),
                $this->equalTo('johndoe@example.com'),
                $this->equalTo('1234567890'),
                $this->equalTo('123 Library St.'),
                $this->equalTo('1990-01-01'),
                $this->equalTo($_SESSION['member_id'])
            );
        $updateStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        // Assume $conn->prepare() returns a statement
        $this->dbConnection->expects($this->once())
            ->method('prepare')
            ->willReturn($updateStmt);

        // Execute the profile update method
        $updateStatus = $this->updateProfile(
            $this->dbConnection,
            'John Doe',
            'johndoe@example.com',
            '1234567890',
            '123 Library St.',
            '1990-01-01',
            $_SESSION['member_id']
        );
        $this->assertTrue($updateStatus, "Profile update failed.");
    }

    // Helper method to simulate fetching member data
    private function fetchProfileData($conn, $memberId)
    {
        $stmt = $conn->prepare("SELECT fullname, email, phone, address, dob FROM users WHERE id = ?");
        $stmt->bind_param("i", $memberId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Helper method to simulate updating member profile
    private function updateProfile($conn, $fullname, $email, $phone, $address, $dob, $memberId)
    {
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, address = ?, dob = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $fullname, $email, $phone, $address, $dob, $memberId);
        return $stmt->execute();
    }
}



