<h1>Contacts</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clear($_POST['name'] ?? '');
    $email = clear($_POST['email'] ?? '');
    $message = clear($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {        
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['message'] = $message;
        $_SESSION['infoMessage'] = ['All fields are required', 'danger'];
    } else {
        mail("kudriashova.ag@gmail.com", "From contacts", "$name $email $message");
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['message']);
        $_SESSION['infoMessage'] = ['Thank!', 'success'];
    }
    redirect('contacts');
}
?>


<?php
if (isset($_SESSION['infoMessage'])) {
    list($text, $type) = $_SESSION['infoMessage'];
    echo "<div class='text-$type'>$text</div>";
    unset($_SESSION['infoMessage']);
}
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
?>

<form action="/contacts" method="POST">
    <div class="mb-3">
        <label for="form-label">Name:</label>
        <input type="text" name="name" class="form-control" <?php if ($name !== '') : ?>value="<?php echo $name; ?>" <?php endif; ?>>
    </div>

    <div class="mb-3">
        <label for="form-label">Email:</label>
        <input type="text" name="email" class="form-control" <?php if ($email !== '') : ?>value="<?php echo $email; ?>" <?php endif; ?>>
    </div>

    <div class="mb-3">
        <label for="form-label">Message:</label>
        <textarea class="form-control" name="message"><?php echo $message; ?></textarea>
    </div>

    <button class="btn btn-primary mt-3">Send</button>
</form>